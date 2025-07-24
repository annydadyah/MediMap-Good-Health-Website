@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Menampilkan pesan sukses setelah update profil --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">User Profile</h3>
                </div>

                <div class="card-body">
                    {{-- Profile Info --}}
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-1">Full Name</h5>
                        <input type="text" class="form-control mb-3" value="{{ $user->name }}" readonly>

                        <h5 class="fw-bold text-dark mb-1">Email Address</h5>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>

                    {{-- Review Activity --}}
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark">Review Activity</h5>
                        <p>You have written a total of 
                            <strong class="text-primary">{{ $totalReviews }}</strong> review(s).
                        </p>
                    </div>

                    {{-- Edit Profile Button --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
