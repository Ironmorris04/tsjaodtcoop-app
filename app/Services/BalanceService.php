<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    /**
     * Calculate operator's current balance
     * Positive = Available / Overpaid
     * Negative = Outstanding / Unpaid
     */
    public static function calculateOperatorBalance($operatorId)
    {
        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        $totalObligations = Transaction::where('operator_id', $operatorId)
            ->whereIn('particular', $monthlyParticulars)
            ->sum('amount');

        $totalPayments = Transaction::where('operator_id', $operatorId)
            ->where('particular', 'monthly_dues_payment')
            ->sum('amount');

        return (float) ($totalPayments - $totalObligations);
    }

    /**
     * Calculate operator balance with full breakdown
     */
    public static function calculateOperatorBalanceWithBreakdown($operatorId)
    {
        $monthlyParticulars = [
            'subscription_capital',
            'management_fee',
            'monthly_dues',
            'office_rental'
        ];

        // Get monthly breakdown
        $monthlyData = Transaction::where('operator_id', $operatorId)
            ->selectRaw("
                YEAR(transaction_date) as year,
                MONTHNAME(transaction_date) as month,
                MONTH(transaction_date) as month_number,
                SUM(CASE 
                    WHEN particular IN ('" . implode("','", $monthlyParticulars) . "') 
                    THEN amount 
                    ELSE 0 
                END) as obligations,
                SUM(CASE 
                    WHEN particular = 'monthly_dues_payment' 
                    THEN amount 
                    ELSE 0 
                END) as payments
            ")
            ->groupBy('year', 'month', 'month_number')
            ->orderBy('year', 'desc')
            ->orderBy('month_number', 'desc')
            ->get();

        $monthlyBalances = $monthlyData->map(function($row) {
            $obligations = (float) $row->obligations;
            $payments = (float) $row->payments;
            $balance = $payments - $obligations;

            // Determine status
            if ($balance === 0.0) {
                $status = 'paid';
            } elseif ($balance > 0) {
                $status = 'overpaid';
            } elseif ($payments > 0 && $balance < 0) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            return [
                'year' => $row->year,
                'month' => $row->month,
                'obligations' => $obligations,
                'payments' => $payments,
                'balance' => $balance,
                'status' => $status
            ];
        });

        // Calculate totals
        $totalObligations = $monthlyBalances->sum('obligations');
        $totalPayments = $monthlyBalances->sum('payments');
        $overallBalance = $totalPayments - $totalObligations;

        // Determine overall status
        if ($overallBalance === 0.0) {
            $overallStatus = 'paid';
        } elseif ($overallBalance > 0) {
            $overallStatus = 'overpaid';
        } else {
            $overallStatus = 'unpaid';
        }

        return [
            'total_obligations' => $totalObligations,
            'total_payments' => $totalPayments,
            'overall_balance' => $overallBalance,
            'overall_status' => $overallStatus,
            'monthly_balances' => $monthlyBalances->values()->toArray()
        ];
    }

    /**
     * Format balance for display with color
     */
    public static function formatBalanceDisplay($balance)
    {
        $color = $balance >= 0 ? '#27ae60' : '#e74c3c';
        $formatted = '₱' . number_format(abs($balance), 2);
        
        return [
            'formatted' => $formatted,
            'color' => $color,
            'is_positive' => $balance >= 0
        ];
    }
}