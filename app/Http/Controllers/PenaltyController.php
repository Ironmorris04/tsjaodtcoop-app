<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use App\Models\PenaltyPayment;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    /**
     * Display all penalties for treasurer view
     */
    public function index()
    {
        $penalties = Penalty::with(['operator.user', 'meeting', 'payments'])
            ->orderBy('status', 'asc') // unpaid first
            ->orderBy('due_date', 'asc')
            ->get();

        $totalUnpaid = Penalty::whereIn('status', ['unpaid', 'partial'])->sum('remaining_amount');
        $totalPaid = Penalty::sum('paid_amount');

        return view('treasurer.penalties.index', compact('penalties', 'totalUnpaid', 'totalPaid'));
    }

    /**
     * Show payment form for a specific penalty
     */
    public function showPaymentForm(Penalty $penalty)
    {
        $penalty->load(['operator.user', 'meeting', 'payments.receivedBy']);
        return view('treasurer.penalties.payment', compact('penalty'));
    }

    /**
     * Process penalty payment
     */
    public function processPayment(Request $request, Penalty $penalty)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $penalty->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,bank_transfer,gcash,paymaya',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($penalty, $validated) {
            // Create payment record
            PenaltyPayment::create([
                'penalty_id' => $penalty->id,
                'operator_id' => $penalty->operator_id,
                'received_by' => Auth::id(),
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update penalty status
            $penalty->updatePaymentStatus();
        });

        return redirect()->route('treasurer.penalties.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Get penalties for a specific operator (API endpoint)
     */
    public function getOperatorPenalties(Operator $operator)
    {
        $penalties = $operator->penalties()
            ->with(['meeting', 'payments'])
            ->get();

        return response()->json([
            'success' => true,
            'penalties' => $penalties,
            'total_unpaid' => $operator->total_unpaid_penalties,
            'total_paid' => $operator->total_paid_penalties,
        ]);
    }
}
