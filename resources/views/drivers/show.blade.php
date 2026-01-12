@extends('layouts.app')

@section('title', 'Driver Details')
@section('page-title', 'Driver Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">Drivers</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Driver Information</h3>
                <div class="card-tools">
                    <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('drivers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Personal Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Full Name</th>
                                <td>{{ $driver->full_name }}</td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td>{{ $driver->first_name }}</td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td>{{ $driver->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $driver->phone }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $driver->address }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">License Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">License Number</th>
                                <td><strong>{{ $driver->license_no }}</strong></td>
                            </tr>
                            <tr>
                                <th>License Expiry</th>
                                <td>
                                    @if($driver->license_expiry)
                                        {{ \Carbon\Carbon::parse($driver->license_expiry)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($driver->license_expiry)->isPast())
                                            <span class="badge badge-danger ml-2">EXPIRED</span>
                                        @elseif(now()->diffInDays(\Carbon\Carbon::parse($driver->license_expiry), false) < 30)
                                            <span class="badge badge-warning ml-2">Expiring Soon</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($driver->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Operator</th>
                                <td>{{ $driver->operator->full_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Additional Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">Created At</th>
                                <td>{{ $driver->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $driver->updated_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Driver
                </a>
                <button type="button" class="btn btn-danger" onclick="deleteDriver()">
                    <i class="fas fa-trash"></i> Delete Driver
                </button>
                <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                <form id="delete-form" action="{{ route('drivers.destroy', $driver) }}" method="POST" style="display: none;">
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
function deleteDriver() {
    if (confirm('Are you sure you want to delete this driver?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush