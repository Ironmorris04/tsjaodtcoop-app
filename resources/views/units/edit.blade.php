@extends('layouts.app')

@section('title', 'Edit Transport Unit')
@section('page-title', 'Edit Transport Unit')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('units.index') }}">Transport Units</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit Transport Unit Information</h3>
            </div>
            <form action="{{ route('units.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plate_no">Plate Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('plate_no') is-invalid @enderror" 
                                       id="plate_no" name="plate_no" value="{{ old('plate_no', $unit->plate_no) }}" required>
                                @error('plate_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Vehicle Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="bus" {{ old('type', $unit->type) == 'bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="jeepney" {{ old('type', $unit->type) == 'jeepney' ? 'selected' : '' }}>Jeepney</option>
                                    <option value="van" {{ old('type', $unit->type) == 'van' ? 'selected' : '' }}>Van</option>
                                    <option value="taxi" {{ old('type', $unit->type) == 'taxi' ? 'selected' : '' }}>Taxi</option>
                                </select>
                                @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">Brand <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                       id="brand" name="brand" value="{{ old('brand', $unit->brand) }}" required>
                                @error('brand')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model">Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                       id="model" name="model" value="{{ old('model', $unit->model) }}" required>
                                @error('model')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year">Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year', $unit->year) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('year')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacity">Passenger Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', $unit->capacity) }}" min="1" required>
                                @error('capacity')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $unit->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status', $unit->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status', $unit->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update Unit
                    </button>
                    <a href="{{ route('units.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection