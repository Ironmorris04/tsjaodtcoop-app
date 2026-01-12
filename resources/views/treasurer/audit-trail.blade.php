@extends('layouts.app')

@section('title', 'Audit Trail')

@section('page-title', 'Audit Trail / Recent Activities')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter"></i> Filters
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route(request()->route()->getName()) }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="action">Action Type</label>
                            <select name="action" id="action" class="form-control">
                                <option value="">All Actions</option>
                                <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="model">Module</label>
                            <select name="model" id="model" class="form-control">
                                <option value="">All Modules</option>
                                <option value="Operator" {{ request('model') == 'Operator' ? 'selected' : '' }}>Operators</option>
                                <option value="Transaction" {{ request('model') == 'Transaction' ? 'selected' : '' }}>Transactions</option>
                                <option value="AnnualReport" {{ request('model') == 'AnnualReport' ? 'selected' : '' }}>Annual Report</option>
                                <option value="Officer" {{ request('model') == 'Officer' ? 'selected' : '' }}>Officers</option>
                                <option value="Meeting" {{ request('model') == 'Meeting' ? 'selected' : '' }}>Meetings</option>
                                <option value="User" {{ request('model') == 'User' ? 'selected' : '' }}>Users</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route(request()->route()->getName()) }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Trail Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> Recent Activities
            </h3>
            <div class="card-tools">
                <span class="badge badge-info">{{ $auditTrails->total() }} Total Records</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="15%">Date & Time</th>
                            <th width="8%">User ID</th>
                            <th width="15%">User</th>
                            <th width="10%">Action</th>
                            <th width="12%">Module</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditTrails as $audit)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $audit->created_at->timezone('Asia/Manila')->format('M d, Y') }}<br>
                                    {{ $audit->created_at->timezone('Asia/Manila')->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $audit->user?->user_id ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $audit->user_name ?? 'System' }}</strong><br>
                                <small class="text-muted">{{ $audit->user?->role ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'login' => 'success',
                                        'logout' => 'secondary',
                                        'download' => 'info',
                                        'created' => 'success',
                                        'updated' => 'info',
                                        'deleted' => 'danger',
                                        'approved' => 'success',
                                        'rejected' => 'warning',
                                    ][$audit->action] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($audit->action) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $audit->model ?? 'System' }}</span>
                            </td>
                            <td>
                                {{ $audit->description }}
                                @if($audit->changes)
                                    <button class="btn btn-sm btn-link p-0 ml-2" type="button" data-toggle="collapse" data-target="#changes-{{ $audit->id }}">
                                        <i class="fas fa-chevron-down"></i> View Changes
                                    </button>
                                    <div class="collapse mt-2" id="changes-{{ $audit->id }}">
                                        <div class="card card-body bg-light">
                                            <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">{{ json_encode($audit->changes, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No audit trail records found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($auditTrails->hasPages())
        <div class="card-footer">
            {{ $auditTrails->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
    border-radius: 0.25rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1.25rem;
}

.card-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 500;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

.badge {
    font-size: 0.85rem;
    padding: 0.35em 0.65em;
}

pre {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.5rem;
}
</style>
@endsection
