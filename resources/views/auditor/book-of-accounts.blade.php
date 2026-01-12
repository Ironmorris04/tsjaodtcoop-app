@extends('layouts.app')

@section('content')
<div style="padding: 0;">
    <!-- Page Header -->
    <div style="margin-bottom: 20px;">
        <h2 style="color: #343a40; font-size: 24px; font-weight: 700; margin: 0 0 8px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-file-invoice" style="color: #667eea;"></i>
            Book of Accounts
        </h2>
        <p style="color: #6c757d; margin: 0; font-size: 14px;">
            Comprehensive financial statements and account balances overview
        </p>
    </div>

    <!-- Financial Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Cash on Hand</div>
                <div style="font-size: 24px; font-weight: 700; color: #2c3e50; line-height: 1;">₱{{ number_format($cashOnHand, 2) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-landmark"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Cash in Bank</div>
                <div style="font-size: 24px; font-weight: 700; color: #2c3e50; line-height: 1;">₱{{ number_format($cashInBank, 2) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-coins"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Total Assets</div>
                <div style="font-size: 24px; font-weight: 700; color: #2c3e50; line-height: 1;">₱{{ number_format($totalAssets, 2) }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; flex-shrink: 0;">
                <i class="fas fa-piggy-bank"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 13px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px;">Equity</div>
                <div style="font-size: 24px; font-weight: 700; color: #2c3e50; line-height: 1;">₱{{ number_format($equity, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Financial Chart -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden; margin-bottom: 20px;">
        <div style="padding: 20px 25px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #343a40; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-area"></i> Monthly Cash Flow Trend Analysis
            </h3>
            <span style="background: #f8f9fa; color: #495057; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                <i class="fas fa-calendar-alt"></i> 12 Months
            </span>
        </div>
        <div style="padding: 25px; min-height: 350px;">
            <canvas id="cashFlowChart" height="80"></canvas>
        </div>
    </div>

    <!-- Balance Sheets -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
        <!-- Assets Statement -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden;">
            <div style="padding: 20px 25px; border-bottom: 2px solid #f0f0f0;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #343a40; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-pie"></i> Assets Statement
                </h3>
            </div>
            <div style="padding: 25px;">
                <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #155724; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> <strong>Assets</strong> represent resources owned by the cooperative
                    </p>
                </div>
                <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">Account</th>
                            <th style="padding: 12px; text-align: right; font-weight: 700; font-size: 13px; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="transition: background 0.2s ease;">
                            <td style="padding: 12px; color: #495057; font-size: 14px;">
                                <i class="fas fa-money-bill-wave" style="color: #2ecc71; margin-right: 8px;"></i>
                                Cash on Hand
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #2c3e50;">₱{{ number_format($cashOnHand, 2) }}</td>
                        </tr>
                        <tr style="transition: background 0.2s ease;">
                            <td style="padding: 12px; color: #495057; font-size: 14px;">
                                <i class="fas fa-landmark" style="color: #3498db; margin-right: 8px;"></i>
                                Cash in Bank
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #2c3e50;">₱{{ number_format($cashInBank, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 15px; font-weight: 700; border-top: 2px solid #dee2e6;">Total Assets</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700; color: #2ecc71; border-top: 2px solid #dee2e6;">₱{{ number_format($totalAssets, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Liabilities & Equity Statement -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden;">
            <div style="padding: 20px 25px; border-bottom: 2px solid #f0f0f0;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #343a40; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-balance-scale"></i> Liabilities & Equity
                </h3>
            </div>
            <div style="padding: 25px;">
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <p style="margin: 0; color: #856404; font-size: 14px;">
                        <i class="fas fa-info-circle"></i> <strong>Liabilities</strong> and <strong>Equity</strong> represent claims against assets
                    </p>
                </div>
                <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                    <thead style="background: #f8f9fc;">
                        <tr>
                            <th style="padding: 12px; font-weight: 700; font-size: 13px; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">Account</th>
                            <th style="padding: 12px; text-align: right; font-weight: 700; font-size: 13px; color: #2c3e50; border-bottom: 2px solid #e3e6f0;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="transition: background 0.2s ease;">
                            <td style="padding: 12px; color: #495057; font-size: 14px;">
                                <i class="fas fa-file-invoice-dollar" style="color: #e74c3c; margin-right: 8px;"></i>
                                Total Liabilities
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #2c3e50;">₱{{ number_format($totalLiabilities, 2) }}</td>
                        </tr>
                        <tr style="transition: background 0.2s ease;">
                            <td style="padding: 12px; color: #495057; font-size: 14px;">
                                <i class="fas fa-wallet" style="color: #e67e22; margin-right: 8px;"></i>
                                Owner's Equity
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #2c3e50;">₱{{ number_format($equity, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 15px; font-weight: 700; border-top: 2px solid #dee2e6;">Total Liabilities & Equity</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700; color: #e67e22; border-top: 2px solid #dee2e6;">₱{{ number_format($totalLiabilities + $equity, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background: #f8f9fc !important;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('cashFlowChart').getContext('2d');

    const monthlyData = @json($monthlyData);

    const cashFlowChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [
                {
                    label: 'Cash on Hand',
                    data: monthlyData.cashOnHand,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2ecc71',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Cash in Bank',
                    data: monthlyData.cashInBank,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + (value / 1000) + 'k';
                        },
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
