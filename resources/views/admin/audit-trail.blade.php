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
                                @foreach($actionTypes as $actionType)
                                    <option value="{{ $actionType }}" {{ request('action') == $actionType ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $actionType)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="model">Module</label>
                            <select name="model" id="model" class="form-control">
                                <option value="">All Modules</option>
                                @foreach($modelTypes as $modelType)
                                    <option value="{{ $modelType }}" {{ request('model') == $modelType ? 'selected' : '' }}>
                                        {{ $modelType }}
                                    </option>
                                @endforeach
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user">User</label>
                            <select name="user" id="user" class="form-control">
                                <option value="">All Users</option>
                                @foreach($users as $usr)
                                    <option value="{{ $usr->id }}" {{ request('user') == $usr->id ? 'selected' : '' }}>
                                        {{ $usr->name }} ({{ ucfirst($usr->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label>&nbsp;</label><br>
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
                        @php
                            $actionType = $audit->type ?? $audit->action ?? 'unknown';
                            $badgeClass = [
                                'login' => 'success',
                                'logout' => 'secondary',
                                'download' => 'info',
                                'operator_registered' => 'success',
                                'driver_added' => 'success',
                                'unit_added' => 'success',
                                'document_uploaded' => 'info',
                                'document_updated' => 'warning',
                                'meeting_created' => 'primary',
                                'attendance_marked' => 'success',
                                'profile_updated' => 'info',
                                'created' => 'success',
                                'updated' => 'info',
                                'deleted' => 'danger',
                                'approved' => 'success',
                                'rejected' => 'warning',
                                'operator_approved' => 'success',
                                'operator_rejected' => 'danger',
                                'driver_approved' => 'success',
                                'driver_rejected' => 'danger',
                                'unit_approved' => 'success',
                                'unit_rejected' => 'danger',
                            ][$actionType] ?? 'secondary';

                            $modelName = $audit->subject_type ?? $audit->model ?? 'System';
                            if (class_exists($modelName)) {
                                $modelName = class_basename($modelName);
                            }
                        @endphp
                        <tr class="activity-row" style="cursor: pointer;"
                            data-toggle="modal"
                            data-target="#activityModal"
                            data-id="{{ $audit->id }}"
                            data-date="{{ \Carbon\Carbon::parse($audit->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') }}"
                            data-user-id="{{ $audit->user?->user_id ?? 'N/A' }}"
                            data-user-name="{{ $audit->user?->name ?? 'System' }}"
                            data-user-role="{{ ucfirst($audit->user?->role ?? 'N/A') }}"
                            data-action="{{ ucwords(str_replace('_', ' ', $actionType)) }}"
                            data-action-type="{{ $actionType }}"
                            data-badge-class="{{ $badgeClass }}"
                            data-module="{{ $modelName }}"
                            data-description="{{ $audit->description }}"
                            data-properties="{{ json_encode($audit->properties ?? $audit->changes ?? []) }}"
                            data-subject-id="{{ $audit->subject_id }}"
                            data-subject-type="{{ $audit->subject_type }}">
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($audit->created_at)->timezone('Asia/Manila')->format('M d, Y') }}<br>
                                    {{ \Carbon\Carbon::parse($audit->created_at)->timezone('Asia/Manila')->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $audit->user?->user_id ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $audit->user?->name ?? 'System' }}</strong><br>
                                <small class="text-muted">{{ ucfirst($audit->user?->role ?? 'N/A') }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badgeClass }}">
                                    {{ ucwords(str_replace('_', ' ', $actionType)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $modelName }}</span>
                            </td>
                            <td>
                                {{ Str::limit($audit->description, 50) }}
                                @if($audit->properties ?? $audit->changes)
                                    <i class="fas fa-info-circle text-info ml-1" title="Click to view details"></i>
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
        <div class="card-footer" style="background: white; border-top: none;">
            <div class="pagination-container">
                {{ $auditTrails->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Activity Detail Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel">
                    <i class="fas fa-history"></i> Activity Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted d-block">Date & Time</small>
                                <strong id="modal-date"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted d-block">Action Type</small>
                                <span id="modal-action-badge"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted d-block">Performed By</small>
                                <strong id="modal-user-name"></strong>
                                <small class="text-muted d-block" id="modal-user-info"></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <small class="text-muted d-block">Module</small>
                                <span class="badge badge-secondary" id="modal-module"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong><i class="fas fa-align-left"></i> Description</strong>
                    </div>
                    <div class="card-body">
                        <p id="modal-description" class="mb-0"></p>
                    </div>
                </div>

                <div class="card" id="properties-section" style="display: none;">
                    <div class="card-header py-2">
                        <strong><i class="fas fa-code"></i> Additional Details</strong>
                    </div>
                    <div class="card-body">
                        <pre id="modal-properties" class="mb-0" style="max-height: 300px; overflow-y: auto; font-size: 12px;"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
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
    background-color: #e3f2fd;
}

.activity-row:hover {
    background-color: #e3f2fd !important;
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

.pagination-container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

#activityModal .modal-header {
    background-color: #007bff;
    color: white;
}

#activityModal .modal-header .close {
    color: white;
    opacity: 0.8;
}

#activityModal .modal-header .close:hover {
    opacity: 1;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle activity row click
    $('.activity-row').on('click', function() {
        var $row = $(this);

        // Populate modal with data
        $('#modal-date').text($row.data('date'));
        $('#modal-user-name').text($row.data('user-name'));
        $('#modal-user-info').text('User ID: ' + $row.data('user-id') + ' | Role: ' + $row.data('user-role'));
        $('#modal-module').text($row.data('module'));
        $('#modal-description').text($row.data('description'));

        // Set action badge
        var badgeClass = $row.data('badge-class');
        var actionText = $row.data('action');
        $('#modal-action-badge').html('<span class="badge badge-' + badgeClass + '">' + actionText + '</span>');

        // Handle properties/additional details
        var properties = $row.data('properties');
        if (properties && Object.keys(properties).length > 0) {
            $('#properties-section').show();
            $('#modal-properties').text(JSON.stringify(properties, null, 2));
        } else {
            $('#properties-section').hide();
        }
    });
});
</script>
@endpush
@endsection
