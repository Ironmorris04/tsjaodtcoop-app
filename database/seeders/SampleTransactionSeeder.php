<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Operator;
use App\Models\User;
use Carbon\Carbon;

class SampleTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $operators = Operator::where('status', 'active')->get();

        if ($operators->isEmpty()) {
            $this->command->warn('No active operators found. Please create operators first.');
            return;
        }

        $admin = User::where('role', 'admin')->first() ??
                 User::where('role', 'treasurer')->first() ??
                 User::first();

        if (!$admin) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        $this->command->info('Creating sample transactions...');

        $disbursementCategories = [
            'cash_advance',
            'due_to_regulatory',
            'miscellaneous_expense',
            'cbu_withdrawal',
            'notarial_fees',
            'license_and_taxes',
            'cash_in_bank',
            'penalties_and_charges'
        ];

        $disbursementParticulars = [
            'cash_advance' => 'Cash advance for operational expenses',
            'due_to_regulatory' => 'Payment to regulatory agencies',
            'miscellaneous_expense' => 'Office supplies and miscellaneous',
            'cbu_withdrawal' => 'CBU share capital withdrawal',
            'notarial_fees' => 'Document notarization fees',
            'license_and_taxes' => 'Business license renewal and taxes',
            'cash_in_bank' => 'Bank deposit',
            'penalties_and_charges' => 'Penalty payment'
        ];

        $receiptParticulars = [
            'subscription_capital',
            'management_fee',
            'membership_fee',
            'monthly_dues',
        ];

        $amounts = [
            'subscription_capital' => 500,
            'management_fee' => 500,
            'membership_fee' => 500,
            'monthly_dues' => 150,
        ];

        $transactionCount = 0;

        for ($year = 2023; $year <= 2025; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                if (Carbon::create($year, $month, 1)->isFuture()) {
                    continue;
                }

                $receiptCount = rand(10, 20);
                for ($i = 0; $i < $receiptCount; $i++) {
                    $operator = $operators->random();
                    $particular = $receiptParticulars[array_rand($receiptParticulars)];
                    $day = rand(1, min(28, Carbon::create($year, $month)->daysInMonth));

                    Transaction::create([
                        'operator_id' => $operator->id,
                        'type' => 'receipt',
                        'category' => null,
                        'date' => Carbon::create($year, $month, $day),
                        'particular' => $particular,
                        'month' => Carbon::create($year, $month)->format('F Y'),
                        'or_number' => 'OR-' . $year . '-' . str_pad($transactionCount + 1, 4, '0', STR_PAD_LEFT),
                        'amount' => $amounts[$particular] ?? rand(100, 1000),
                        'created_by' => $admin->id
                    ]);

                    $transactionCount++;
                }

                $disbursementCount = rand(5, 10);
                for ($i = 0; $i < $disbursementCount; $i++) {
                    $category = $disbursementCategories[array_rand($disbursementCategories)];
                    $day = rand(1, min(28, Carbon::create($year, $month)->daysInMonth));

                    Transaction::create([
                        'operator_id' => null,
                        'type' => 'disbursement',
                        'category' => $category,
                        'date' => Carbon::create($year, $month, $day),
                        'particular' => $disbursementParticulars[$category],
                        'month' => Carbon::create($year, $month)->format('F Y'),
                        'or_number' => 'CD-' . $year . '-' . str_pad($transactionCount + 1, 4, '0', STR_PAD_LEFT),
                        'amount' => rand(500, 5000),
                        'created_by' => $admin->id
                    ]);

                    $transactionCount++;
                }
            }
        }

        $this->command->info("Successfully created {$transactionCount} sample transactions!");
        $this->command->info("  - Years: 2023, 2024, 2025 (up to current month)");
        $this->command->info("  - Receipts: 10-20 per month from operators");
        $this->command->info("  - Disbursements: 5-10 per month from treasurer");
    }
}
