@extends('layouts.app')

@section('title', 'Unit Details')
@section('page-title', 'Transport Unit Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('units.index') }}">Transport Units</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Transport Unit Information</h3>
                <div class="card-tools">
                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('units.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Vehicle Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Plate Number</th>
                                <td><strong>{{ $unit->plate_no }}</strong></td>
                            </tr>
                            <tr>
                                <th>Vehicle Type</th>
                                <td><span class="badge badge-info">{{ ucfirst($unit->type) }}</span></td>
                            </tr>
                            <tr>
                                <th>Brand</th>
                                <td>{{ $unit->brand }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $unit->model }}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $unit->year }}</td>
                            </tr>
                            <tr>
                                <th>Passenger Capacity</th>
                                <td>{{ $unit->capacity }} passengers</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Status & Operator Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Status</th>
                                <td>
                                    @if($unit->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif($unit->status == 'maintenance')
                                        <span class="badge badge-warning">Maintenance</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                            <th>Operator</th>
                            <td>{{ $unit->operator ? $unit->operator->full_name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $unit->operator ? $unit->operator->phone : 'N/A' }}</td>
                        </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Record Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">Created At</th>
                                <td>{{ $unit->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $unit->updated_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Unit
                </a>
                <button type="button" class="btn btn-danger" onclick="deleteUnit()">
                    <i class="fas fa-trash"></i> Delete Unit
                </button>
                <a href="{{ route('units.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                <form id="delete-form" action="{{ route('units.destroy', $unit) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUnit() {
    if (confirm('Are you sure you want to delete this transport unit?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush