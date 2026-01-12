@extends('layouts.app')

@section('title', 'Particular Prices Management')

@section('page-title', 'Particular Prices Management')

@push('styles')
<style>
    .prices-container {
        padding: 20px;
    }

    .prices-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        color: #1f2937;
        font-size: 28px;
        font-weight: 700;
    }

    .header-title i {
        color: #667eea;
        font-size: 32px;
    }

    .btn-add-price {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-add-price:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }

    .prices-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .prices-table {
        width: 100%;
        border-collapse: collapse;
    }

    .prices-table thead {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .prices-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .prices-table td {
        padding: 16px;
        border-bottom: 1px solid #e9ecef;
    }

    .prices-table tbody tr {
        transition: all 0.3s ease;
    }

    .prices-table tbody tr:hover {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .particular-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: linear-gradient(135deg, #e0e7ff, #ddd6fe);
        color: #4c1d95;
        border: 1px solid #c4b5fd;
    }

    .amount-display {
        font-size: 18px;
        font-weight: 700;
        color: #059669;
    }

    .date-range {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .date-text {
        font-size: 13px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .date-text i {
        color: #667eea;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        color: white;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        transform: translateY(-2px);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f87171, #ef4444);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 16px 16px 0 0;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-group label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .date-range-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .modal-footer {
        padding: 20px 30px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-modal {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 72px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #1f2937;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 15px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
</style>
@endpush

@section('content')
<div class="prices-container">
    <!-- Success/Error Messages -->
    <div id="alertContainer"></div>

    <div class="prices-header">
        <h1 class="header-title">
            <i class="fas fa-tags"></i> Particular Prices Management
        </h1>
        <button class="btn-add-price" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            <span>Add New Price</span>
        </button>
    </div>

    <div class="prices-table-card">
        @if($prices->count() > 0)
            <table class="prices-table">
                <thead>
                    <tr>
                        <th>Particular</th>
                        <th>Amount</th>
                        <th>Date Range</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prices as $price)
                        <tr>
                            <td>
                                <div class="particular-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $price->formatted_particular }}
                                </div>
                            </td>
                            <td>
                                <div class="amount-display">₱{{ number_format($price->amount, 2) }}</div>
                            </td>
                            <td>
                                <div class="date-range">
                                    <div class="date-text">
                                        <i class="fas fa-calendar-alt"></i>
                                        From: {{ $price->start_date->format('M d, Y') }}
                                    </div>
                                    <div class="date-text">
                                        <i class="fas fa-calendar-check"></i>
                                        To: {{ $price->end_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $price->status }}">
                                    <i class="fas fa-circle"></i>
                                    {{ ucfirst($price->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $price->creator ? $price->creator->name : 'N/A' }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-edit" onclick="editPrice({{ $price->id }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deletePrice({{ $price->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if($prices->hasPages())
                <div style="padding: 20px;">
                    {{ $prices->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Price Settings Yet</h3>
                <p>Start by adding price settings for particulars.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="priceModal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>
                <i class="fas fa-tag"></i>
                <span id="modalTitle">Add New Price</span>
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="priceForm" onsubmit="submitForm(event)">
            <div class="modal-body">
                <input type="hidden" id="priceId" name="price_id">

                <div class="form-group">
                    <label for="particular">Particular <span class="required">*</span></label>
                    <select class="form-control" id="particular" name="particular" required>
                        <option value="">Select Particular</option>
                        @foreach($particularTypes as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount (₱) <span class="required">*</span></label>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label>Date Range <span class="required">*</span></label>
                    <div class="date-range-inputs">
                        <div>
                            <label for="start_date" style="font-weight: normal; font-size: 12px; color: #6b7280;">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div>
                            <label for="end_date" style="font-weight: normal; font-size: 12px; color: #6b7280;">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="statusGroup" style="display: none;">
                    <label for="status">Status <span class="required">*</span></label>
                    <select class="form-control" id="status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes">Notes (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="500"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-modal btn-submit">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let editingPriceId = null;
const prices = @json($prices->items());

function openAddModal() {
    editingPriceId = null;
    document.getElementById('modalTitle').textContent = 'Add New Price';
    document.getElementById('priceForm').reset();
    document.getElementById('priceId').value = '';
    document.getElementById('statusGroup').style.display = 'none';
    document.getElementById('priceModal').classList.add('active');
}

function editPrice(priceId) {
    editingPriceId = priceId;
    const price = prices.find(p => p.id === priceId);

    if (!price) {
        showAlert('Price not found', 'danger');
        return;
    }

    document.getElementById('modalTitle').textContent = 'Edit Price';
    document.getElementById('priceId').value = price.id;
    document.getElementById('particular').value = price.particular;
    document.getElementById('amount').value = price.amount;
    document.getElementById('start_date').value = price.start_date.split(' ')[0];
    document.getElementById('end_date').value = price.end_date.split(' ')[0];
    document.getElementById('status').value = price.status;
    document.getElementById('notes').value = price.notes || '';
    document.getElementById('statusGroup').style.display = 'block';

    document.getElementById('priceModal').classList.add('active');
}

function closeModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('priceModal').classList.remove('active');
    document.getElementById('priceForm').reset();
    editingPriceId = null;
}

async function submitForm(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);

    const url = editingPriceId
        ? `{{ route('treasurer.particular-prices.store') }}`.replace('/particular-prices', `/particular-prices/${editingPriceId}`)
        : '{{ route('treasurer.particular-prices.store') }}';

    const method = editingPriceId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            showAlert(result.message, 'success');
            closeModal();
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showAlert(result.message || 'An error occurred', 'danger');
        }
    } catch (error) {
        showAlert('An error occurred while saving', 'danger');
        console.error(error);
    }
}

async function deletePrice(priceId) {
    if (!confirm('Are you sure you want to delete this price setting?')) {
        return;
    }

    try {
        const response = await fetch(`{{ route('treasurer.particular-prices.store') }}`.replace('/particular-prices', `/particular-prices/${priceId}`), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            showAlert(result.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showAlert(result.message || 'An error occurred', 'danger');
        }
    } catch (error) {
        showAlert('An error occurred while deleting', 'danger');
        console.error(error);
    }
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        </div>
    `;

    document.getElementById('alertContainer').innerHTML = alertHtml;

    setTimeout(() => {
        document.getElementById('alertContainer').innerHTML = '';
    }, 5000);
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
