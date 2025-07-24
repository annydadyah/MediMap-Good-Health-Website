@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Edit User Profile</h2>
        <!-- Back to Profile Button -->
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Back to Profile
        </a>
    </div>

    <!-- Row for Form and Instructions -->
    <div class="row">
        <!-- Left Column: Account Information Form -->
        <div class="col-md-8">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="text-lg font-semibold mb-3">Edit Account Information Form</h5>

                <!-- Display Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Section -->
                    <hr>
                    <h5 class="mt-4">Change Password (Optional)</h5>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank if you do not want to change the password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>

                    <!-- Save Changes Button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Filling Instructions -->
        <div class="col-md-4">
            <div class="bg-light p-4 rounded shadow-sm">
                <h5 class="text-lg font-semibold">Filling Instructions</h5>
                <ul class="text-muted">
                    <li>Ensure all fields marked with an asterisk <span class="text-danger">*</span> are filled in correctly.</li>
                    <li>If you wish to change your password, fill in the "New Password" and "Confirm New Password" fields.</li>
                    <li>If not, leave these two fields blank.</li>
                    <li>Changes will be saved after you press the "Save Changes" button.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
