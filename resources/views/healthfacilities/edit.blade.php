@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Tombol Kembali --}}
    <a href="{{ route('healthfacilities.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <h2 class="text-primary mb-4" style="font-size: 3rem;">Edit Hospital: {{ $hospital->name }}</h2>


    <form action="{{ route('healthfacilities.update', $hospital->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Hospital Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $hospital->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address', $hospital->address) }}" class="form-control @error('address') is-invalid @enderror" required>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $hospital->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $hospital->latitude) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $hospital->longitude) }}" class="form-control">
        </div>

        <!-- Type Dropdown (Public/Private) -->
        <div class="mb-3">
            <label for="type" class="form-label">Hospital Type</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="" disabled>Select Type</option>
                <option value="public" {{ old('type', $hospital->type) == 'public' ? 'selected' : '' }}>Public</option>
                <option value="private" {{ old('type', $hospital->type) == 'private' ? 'selected' : '' }}>Private</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Hospital</button>
    </form>
</div>
@endsection
