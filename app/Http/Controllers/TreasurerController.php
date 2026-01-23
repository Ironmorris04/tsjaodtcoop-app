<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Operator;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TreasurerController extends Controller
{
    /**
     * Cash Treasurer's Book - Overview of all cash positions (Monthly)
     */
    public function cashTreasurersBook(Request $request)
    {
        // Get month and year from request or default to current
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        // Get sort direction
        $sort = $request->input('sort', 'desc'); // default desc

        // Get transactions for the selected month
        $query = Transaction::with(['operator.user'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        $transactions = $query->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->paginate(50);

        // Calculate monthly totals
        $totalIn = Transaction::where('type', 'receipt')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $totalOut = Transaction::where('type', 'disbursement')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        // Get all active and approved operators for the dropdown
        $operators = Operator::with('user')
            ->where('status', 'active')
            ->where('approval_status', 'approved')
            ->orderBy('business_name', 'asc')
            ->get();

        return view('treasurer.cash-treasurers-book', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'operators',
            'month',
            'year'
        ));
    }

    /**
     * Cash Receipts Journal - All incoming transactions (Monthly)
     */
    public function cashReceiptsJournal(Request $request)
    {
        $sort = $request->input('sort', 'desc'); // default desc

        // Handle search query - search all data if search term provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Search across all receipts in the database
            $foundTransaction = Transaction::where('type', 'receipt')
                ->where('or_number', 'LIKE', "%{$searchTerm}%")
                ->first();

            if ($foundTransaction) {
                // Update month and year based on found transaction
                $month = (int) $foundTransaction->date->month;
                $year = (int) $foundTransaction->date->year;

                // Get all receipts for that month/year with search filter
                $receipts = Transaction::with(['operator.user'])
                    ->where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->paginate(50);

                $totalReceipts = Transaction::where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->sum('amount');

                return view('treasurer.cash-receipts-journal', compact('receipts', 'totalReceipts', 'month', 'year'));
            } else {
                // No results found - return to default month/year with empty results
                $month = (int) $request->input('month', now()->month);
                $year = (int) $request->input('year', now()->year);

                $receipts = Transaction::with(['operator.user'])
                    ->where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->paginate(50);

                $totalReceipts = Transaction::where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->sum('amount');

                return view('treasurer.cash-receipts-journal', compact('receipts', 'totalReceipts', 'month', 'year'));
            }
        }

        // No search - use selected or default month/year
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $receipts = Transaction::with(['operator.user'])
            ->where('type', 'receipt')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', $sort)
            ->paginate(50);

        $totalReceipts = Transaction::where('type', 'receipt')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        return view('treasurer.cash-receipts-journal', compact('receipts', 'totalReceipts', 'month', 'year'));
    }

    /**
     * Cash Disbursement Book - All outgoing transactions (Annual)
     */
    public function cashDisbursementBook(Request $request)
    {
        $sort = $request->input('sort', 'desc'); // default desc

        // Handle search query - search all data if search term provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Search across all disbursements in the database
            $foundTransaction = Transaction::where('type', 'disbursement')
                ->where('or_number', 'LIKE', "%{$searchTerm}%")
                ->first();

            if ($foundTransaction) {
                // Update year based on found transaction
                $year = (int) $foundTransaction->date->year;

                // Get all disbursements for that year with search filter
                $disbursements = Transaction::with(['operator.user'])
                    ->where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->paginate(50);

                $totalDisbursements = Transaction::where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->sum('amount');

                return view('treasurer.cash-disbursement-book', compact('disbursements', 'totalDisbursements', 'year'));
            } else {
                // No results found - return to default year with empty results
                $year = (int) $request->input('year', now()->year);

                $disbursements = Transaction::with(['operator.user'])
                    ->where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->paginate(50);

                $totalDisbursements = Transaction::where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->sum('amount');

                return view('treasurer.cash-disbursement-book', compact('disbursements', 'totalDisbursements', 'year'));
            }
        }

        // No search - use selected or default year
        $year = (int) $request->input('year', now()->year);

        $disbursements = Transaction::with(['operator.user'])
            ->where('type', 'disbursement')
            ->whereYear('date', $year)
            ->orderBy('date', $sort)
            ->paginate(50);

        $totalDisbursements = Transaction::where('type', 'disbursement')
            ->whereYear('date', $year)
            ->sum('amount');

        return view('treasurer.cash-disbursement-book', compact('disbursements', 'totalDisbursements', 'year'));
    }

    /**
     * Cash Book - Combined view of all receipts and disbursements (Annual)
     */
    public function cashBook(Request $request)
    {
        $sort = $request->input('sort', 'desc'); // default desc

        // Handle search query - search all data if search term provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Search across all transactions in the database
            $foundTransaction = Transaction::where('or_number', 'LIKE', "%{$searchTerm}%")
                ->first();

            if ($foundTransaction) {
                // Update year based on found transaction
                $year = (int) $foundTransaction->date->year;

                // Get all transactions for that year with search filter
                $transactions = Transaction::with(['operator.user'])
                    ->whereYear('date', $year)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->orderBy('created_at', $sort)
                    ->paginate(50);

                $totalIn = Transaction::where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->sum('amount');

                $totalOut = Transaction::where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->sum('amount');

                return view('treasurer.cash-book', compact(
                    'transactions',
                    'totalIn',
                    'totalOut',
                    'year'
                ));
            } else {
                // No results found - return to default year with empty results
                $year = (int) $request->input('year', now()->year);

                $transactions = Transaction::with(['operator.user'])
                    ->whereYear('date', $year)
                    ->where('or_number', 'LIKE', "%{$searchTerm}%")
                    ->orderBy('date', $sort)
                    ->orderBy('created_at', $sort)
                    ->paginate(50);

                $totalIn = Transaction::where('type', 'receipt')
                    ->whereYear('date', $year)
                    ->sum('amount');

                $totalOut = Transaction::where('type', 'disbursement')
                    ->whereYear('date', $year)
                    ->sum('amount');

                return view('treasurer.cash-book', compact(
                    'transactions',
                    'totalIn',
                    'totalOut',
                    'year'
                ));
            }
        }

        // No search - use selected or default year
        $year = (int) $request->input('year', now()->year);

        $transactions = Transaction::with(['operator.user'])
            ->whereYear('date', $year)
            ->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->paginate(50);

        $totalIn = Transaction::where('type', 'receipt')
            ->whereYear('date', $year)
            ->sum('amount');

        $totalOut = Transaction::where('type', 'disbursement')
            ->whereYear('date', $year)
            ->sum('amount');

        return view('treasurer.cash-book', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'year'
        ));
    }

    /**
     * Download Cash Book as PDF
     */
    public function downloadCashBookPdf(Request $request)
    {
        // Get year and search from request
        $year = (int) $request->input('year', now()->year);
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'desc'); // default desc

        $query = Transaction::with(['operator.user', 'operator.operatorDetail'])
            ->whereYear('date', $year);

        // Apply search filter if provided
        if ($search != '') {
            $query->where('or_number', 'LIKE', "%{$search}%");
        }

        $transactions = $query->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->get(); // Use get() for PDF

        // Calculate totals for the filtered data
        $totalIn = $transactions->where('type', 'receipt')->sum('amount');
        $totalOut = $transactions->where('type', 'disbursement')->sum('amount');

        $data = compact('transactions', 'totalIn', 'totalOut', 'year', 'search');

        $pdf = Pdf::loadView('treasurer.cash-book-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        // Log PDF download
        $searchFilter = $search ? " (Filtered: {$search})" : '';
        AuditTrail::log(
            'download',
            "Downloaded Cash Book PDF for year {$year}{$searchFilter}"
        );

        return $pdf->download('cash-book-' . $year . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Download Cash Treasurer's Book as PDF
     */
    public function downloadCashTreasurersBookPdf(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $sort = $request->input('sort', 'desc'); // default desc

        $transactions = Transaction::with(['operator.user', 'operator.operatorDetail'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->get();

        // Calculate monthly totals
        $totalIn = $transactions->where('type', 'receipt')->sum('amount');
        $totalOut = $transactions->where('type', 'disbursement')->sum('amount');

        $data = compact('transactions', 'totalIn', 'totalOut', 'month', 'year');

        $pdf = Pdf::loadView('treasurer.cash-treasurers-book-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        AuditTrail::log(
            'download',
            "Downloaded Cash Treasurer's Book PDF for " . date('F', mktime(0, 0, 0, $month, 1)) . " {$year}"
        );

        return $pdf->download('cash-treasurers-book-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Download Cash Receipts Journal as PDF
     */
    public function downloadCashReceiptsJournalPdf(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $sort = $request->input('sort', 'desc'); // default desc

        $transactions = Transaction::with(['operator.user', 'operator.operatorDetail'])
            ->where('type', 'receipt')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->get();

        $totalReceipts = $transactions->sum('amount');

        $data = compact('transactions', 'totalReceipts', 'month', 'year');

        $pdf = Pdf::loadView('treasurer.cash-receipts-journal-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        AuditTrail::log(
            'download',
            "Downloaded Cash Receipts Journal PDF for " . date('F', mktime(0, 0, 0, $month, 1)) . " {$year}"
        );

        return $pdf->download('cash-receipts-journal-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Download Cash Disbursement Book as PDF
     */
    public function downloadCashDisbursementBookPdf(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $sort = $request->input('sort', 'desc'); // default desc

        $transactions = Transaction::with(['operator.user', 'operator.operatorDetail'])
            ->where('type', 'disbursement')
            ->whereYear('date', $year)
            ->orderBy('date', $sort)
            ->orderBy('created_at', $sort)
            ->get();

        $totalDisbursements = $transactions->sum('amount');

        $data = compact('transactions', 'totalDisbursements', 'year');

        $pdf = Pdf::loadView('treasurer.cash-disbursement-book-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        AuditTrail::log(
            'download',
            "Downloaded Cash Disbursement Book PDF for year {$year}"
        );

        return $pdf->download('cash-disbursement-book-' . $year . '-' . date('Y-m-d') . '.pdf');
    }
}
