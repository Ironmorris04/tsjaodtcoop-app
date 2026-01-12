<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Operator;
use App\Models\AuditTrail;
use App\Models\Penalty;
use App\Models\PenaltyPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Store transactions (supports both bulk and single entry)
     */
    public function store(Request $request)
    {
        if ($request->has('transactions')) {
            // Bulk transaction entry
            return $this->storeBulkTransactions($request);
        } else {
            // Single transaction entry
            return $this->storeSingleTransaction($request);
        }
    }

    /**
     * Store a single transaction (from treasurer)
     */
    private function storeSingleTransaction(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'nullable|exists:operators,id',
            'type' => 'required|in:receipt,disbursement',
            'category' => 'nullable|string',
            'date' => 'required|date',
            'particular' => 'required|string',
            'month' => 'nullable|string',
            'or_number' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $transaction = Transaction::create([
            'operator_id' => $validated['operator_id'] ?? null,
            'type' => $validated['type'],
            'category' => $validated['category'] ?? null,
            'date' => $validated['date'],
            'particular' => $validated['particular'],
            'month' => $validated['month'] ?? now()->format('F Y'),
            'or_number' => $validated['or_number'] ?? null,
            'amount' => $validated['amount'],
            'created_by' => auth()->id()
        ]);

        // If this is a fine payment, also create a penalty payment record
        if ($validated['particular'] === 'fine' && $validated['operator_id']) {
            // Find the oldest unpaid or partial penalty for this operator
            $penalty = Penalty::where('operator_id', $validated['operator_id'])
                ->whereIn('status', ['unpaid', 'partial'])
                ->orderBy('due_date', 'asc')
                ->first();

            if ($penalty) {
                // Create penalty payment record
                PenaltyPayment::create([
                    'penalty_id' => $penalty->id,
                    'operator_id' => $validated['operator_id'],
                    'received_by' => auth()->id(),
                    'amount' => $validated['amount'],
                    'payment_date' => $validated['date'],
                    'payment_method' => 'cash', // Default to cash, can be updated later
                    'reference_number' => $validated['or_number'] ?? null,
                    'notes' => 'Auto-created from transaction entry'
                ]);

                // Update penalty status
                $penalty->updatePaymentStatus();
            }
        }

        // Log transaction creation
        AuditTrail::log(
            'created',
            "Created {$transaction->type} transaction: {$transaction->particular} (Php {$transaction->amount})",
            'Transaction',
            $transaction->id
        );

        // Recalculate unpaid balance automatically
        $balanceData = null;
        if ($validated['operator_id']) {
            $balanceData = $this->calculateUnpaidBalance($validated['operator_id']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction entry added successfully!',
            'transaction' => $transaction,
            'balance' => $balanceData
        ]);
    }

    /**
     * Store multiple transactions (from operator)
     */
    private function storeBulkTransactions(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'type' => 'nullable|in:receipt,disbursement',
            'transactions' => 'required|array|min:1',
            'transactions.*.particular' => 'required|in:subscription_capital,management_fee,membership_fee,monthly_dues,business_permit,office_rental,fine,misc',
            'transactions.*.month' => 'required|string',
            'transactions.*.or_number' => 'required|string',
            'transactions.*.amount' => 'required|numeric|min:0',
            'transactions.*.from_month' => 'required|string',
            'transactions.*.to_month' => 'required|string',
        ]);

        // Validate that from_month is not after to_month
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($validated['transactions'] as $index => $transactionData) {
            $fromMonthIndex = array_search($transactionData['from_month'], $months);
            $toMonthIndex = array_search($transactionData['to_month'], $months);

            if ($fromMonthIndex === false || $toMonthIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid month selection in transaction row ' . ($index + 1)
                ], 422);
            }

            if ($fromMonthIndex > $toMonthIndex) {
                return response()->json([
                    'success' => false,
                    'message' => 'From Month cannot be after To Month in transaction row ' . ($index + 1)
                ], 422);
            }
        }

        $createdTransactions = [];
        
        foreach ($validated['transactions'] as $transactionData) {
            $transaction = Transaction::create([
                'operator_id' => $validated['operator_id'],
                'type' => 'receipt',
                'date' => now(),
                'particular' => $transactionData['particular'],
                'month' => $transactionData['month'],
                'or_number' => $transactionData['or_number'] ?? null,
                'amount' => $transactionData['amount'],
                'created_by' => auth()->id()
            ]);

            // If this is a fine payment, create penalty payment record
            if ($transactionData['particular'] === 'fine') {
                $penalty = Penalty::where('operator_id', $validated['operator_id'])
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->orderBy('due_date', 'asc')
                    ->first();

                if ($penalty) {
                    PenaltyPayment::create([
                        'penalty_id' => $penalty->id,
                        'operator_id' => $validated['operator_id'],
                        'received_by' => auth()->id(),
                        'amount' => $transactionData['amount'],
                        'payment_date' => now(),
                        'payment_method' => 'cash',
                        'reference_number' => $transactionData['or_number'] ?? null,
                        'notes' => 'Auto-created from bulk transaction entry'
                    ]);

                    $penalty->updatePaymentStatus();
                }
            }

            $createdTransactions[] = $transaction;
        }

        // Log bulk transactions
        $operator = Operator::find($validated['operator_id']);
        AuditTrail::log(
            'created',
            "Created " . count($createdTransactions) . " transaction(s) for operator: {$operator->business_name}",
            'Transaction',
            null
        );

        // Recalculate unpaid balance after bulk transactions
        $balanceData = $this->calculateUnpaidBalance($validated['operator_id']);

        return response()->json([
            'success' => true,
            'message' => 'Transactions completed successfully!',
            'transactions' => $createdTransactions,
            'balance' => $balanceData
        ]);
    }

    /**
     * Get transactions for a specific operator
     */
    public function getOperatorTransactions($operatorId)
    {
        $transactions = Transaction::where('operator_id', $operatorId)
            ->with('creator')
            ->orderBy('date', 'desc')
            ->get();

        $total = $transactions->sum('amount');

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'total' => $total
        ]);
    }

    /**
     * Get transactions for the currently authenticated operator
     */
    public function getMyTransactions()
    {
        $operator = auth()->user()->operator;

        if (!$operator) {
            return response()->json([
                'success' => false,
                'message' => 'No operator profile found for current user',
                'transactions' => []
            ], 404);
        }

        $transactions = Transaction::where('operator_id', $operator->id)
            ->with('creator')
            ->orderBy('date', 'desc')
            ->get();

        $total = $transactions->sum('amount');

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'total' => $total
        ]);
    }

    /**
     * Delete a transaction
     */
    public function destroy(Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user->isTreasurer() && !$user->isAdmin() && $transaction->operator_id != $user->operator?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $transactionDetails = "{$transaction->type} - {$transaction->particular} (Php {$transaction->amount})";

        $transaction->delete();

        AuditTrail::log(
            'deleted',
            "Deleted transaction: {$transactionDetails}",
            'Transaction',
            $transaction->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully!'
        ]);
    }

    /**
     * Get unpaid balance for a specific operator
     */
    public function getUnpaidBalance($operatorId)
    {
        $operator = Operator::find($operatorId);

        if (!$operator) {
            return response()->json([
                'success' => false,
                'message' => 'Operator not found'
            ], 404);
        }

        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        $monthlyTransactions = Transaction::where('operator_id', $operatorId)
            ->whereIn('particular', $monthlyParticulars)
            ->get();

        $grouped = $monthlyTransactions->groupBy(function ($t) {
            return (int) $t->month . '-' . (int) ($t->year ?? now()->year);
        });

        $monthlyBalances = [];

        foreach ($grouped as $transactions) {
            $month = (int) $transactions->first()->month;
            $year  = (int) ($transactions->first()->year ?? now()->year);

            $obligations = $transactions->sum('amount');

            $payments = Transaction::where('operator_id', $operatorId)
                ->where('type', 'receipt')
                ->whereIn('particular', $monthlyParticulars)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $balance = $payments - $obligations;

            $status = $balance < 0 && $payments == 0
                ? 'unpaid'
                : ($balance < 0 ? 'partial' : 'paid');

            $monthlyBalances[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'year' => $year,
                'obligations' => (float) $obligations,
                'payments' => (float) $payments,
                'balance' => (float) $balance,
                'status' => $status
            ];
        }

        return response()->json([
            'success' => true,
            'operator' => $operator->business_name,
            'monthly_balances' => $monthlyBalances
        ]);
    }

    /**
     * Get unpaid balance for the currently authenticated operator
     */
    public function getMyUnpaidBalance()
    {
        $operator = auth()->user()->operator;

        if (!$operator) {
            return response()->json([
                'success' => false,
                'message' => 'No operator profile found for current user'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'operator' => $operator->business_name,
            'monthly_balances' => $this->calculateUnpaidBalance($operator->id)
        ]);
    }

    /**
     * Calculate unpaid balance grouped by month and year for an operator
     */
    private function calculateUnpaidBalance($operatorId)
    {
        $operator = Operator::find($operatorId);
        if (!$operator) return [];

        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        $monthlyTransactions = Transaction::where('operator_id', $operatorId)
            ->whereIn('particular', $monthlyParticulars)
            ->get();

        $grouped = $monthlyTransactions->groupBy(function ($t) {
            return (int) $t->month . '-' . (int) ($t->year ?? now()->year);
        });

        $monthlyBalances = [];

        foreach ($grouped as $transactions) {
            $month = (int) $transactions->first()->month;
            $year  = (int) ($transactions->first()->year ?? now()->year);

            $obligations = $transactions->sum('amount');

            $payments = Transaction::where('operator_id', $operatorId)
                ->where('type', 'receipt')
                ->whereIn('particular', $monthlyParticulars)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $balance = $payments - $obligations;

            $status = $balance < 0 && $payments == 0
                ? 'unpaid'
                : ($balance < 0 ? 'partial' : 'paid');

            $monthlyBalances[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'year' => $year,
                'obligations' => (float) $obligations,
                'payments' => (float) $payments,
                'balance' => (float) $balance,
                'status' => $status
            ];
        }

        return $monthlyBalances;
    }

    /**
     * Update unpaid balance (record payment from unpaid balance modal)
     */
    public function updateUnpaidBalance(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'from_month' => 'required|array',
            'to_month' => 'required|array',
            'year' => 'required|array',
            'amount' => 'required|array',
            'category' => 'nullable|string',
        ]);

        $operatorId = $validated['operator_id'];
        $count = count($validated['amount']);
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $createdTransactions = [];

        for ($i = 0; $i < $count; $i++) {
            $fromMonth = $validated['from_month'][$i];
            $toMonth = $validated['to_month'][$i];
            $year = $validated['year'][$i];
            $amount = $validated['amount'][$i];

            $fromIndex = array_search($fromMonth, $months);
            $toIndex = array_search($toMonth, $months);

            if ($fromIndex === false || $toIndex === false || $fromIndex > $toIndex) {
                continue;
            }

            $monthCount = $toIndex - $fromIndex + 1;
            $amountPerMonth = $amount / $monthCount;

            for ($m = $fromIndex; $m <= $toIndex; $m++) {
                $monthName = $months[$m];
                $monthYearString = "$monthName $year";
                
                $transaction = Transaction::create([
                    'operator_id' => $operatorId,
                    'type' => 'receipt',
                    'category' => 'payment',
                    'particular' => 'monthly_dues_payment',
                    'date' => now(),
                    'month' => $monthYearString,
                    'amount' => $amountPerMonth,
                    'created_by' => auth()->id(),
                ]);

                $createdTransactions[] = $transaction;
            }
        }

        $operator = Operator::find($operatorId);
        AuditTrail::log(
            'created',
            "Recorded payment of ₱" . array_sum($validated['amount']) . " for operator: {$operator->business_name}",
            'Transaction',
            null
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'transactions_created' => count($createdTransactions)
        ]);
    }

    /**
     * Get total subscription capital for a specific operator
     * Calculates total based on all subscription_capital transactions
     */
    public function getTotalSubscriptionCapital($operatorId)
    {
        $operator = Operator::find($operatorId);

        if (!$operator) {
            return response()->json([
                'success' => false,
                'message' => 'Operator not found'
            ], 404);
        }

        // Get all subscription capital transactions
        $subscriptionTransactions = Transaction::where('operator_id', $operatorId)
            ->where('particular', 'subscription_capital')
            ->get();

        // Calculate total subscription capital
        $totalSubscriptionCapital = $subscriptionTransactions->sum('amount');

        // Get month count breakdown (how many months paid)
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $monthsPaid = [];

        foreach ($subscriptionTransactions as $transaction) {
            $monthString = $transaction->month;

            // Parse "January - May 2024" or "January 2024"
            if (preg_match('/^(\w+)\s*-\s*(\w+)\s+(\d{4})$/', $monthString, $matches)) {
                // Range format
                $fromMonth = $matches[1];
                $toMonth = $matches[2];
                $year = $matches[3];

                $fromIndex = array_search($fromMonth, $months);
                $toIndex = array_search($toMonth, $months);

                if ($fromIndex !== false && $toIndex !== false) {
                    for ($i = $fromIndex; $i <= $toIndex; $i++) {
                        $key = $months[$i] . ' ' . $year;
                        $monthsPaid[$key] = true;
                    }
                }
            } elseif (preg_match('/^(\w+)\s+(\d{4})$/', $monthString, $matches)) {
                // Single month format
                $key = $monthString;
                $monthsPaid[$key] = true;
            }
        }

        $totalMonthsPaid = count($monthsPaid);

        return response()->json([
            'success' => true,
            'operator' => $operator->business_name,
            'total_subscription_capital' => (float) $totalSubscriptionCapital,
            'total_months_paid' => $totalMonthsPaid,
            'transactions_count' => $subscriptionTransactions->count(),
            'months_paid' => array_keys($monthsPaid)
        ]);
    }

    /**
     * Get total subscription capital for the currently authenticated operator
     */
    public function getMyTotalSubscriptionCapital()
    {
        $operator = auth()->user()->operator;

        if (!$operator) {
            return response()->json([
                'success' => false,
                'message' => 'No operator profile found for current user'
            ], 404);
        }

        return $this->getTotalSubscriptionCapital($operator->id);
    }
}