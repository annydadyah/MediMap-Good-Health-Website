@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="text-primary mb-4 text-center font-weight-bold" style="font-size: 2rem;">Health Facilities</h1>

        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('healthfacilities.create') }}" class="btn btn-success mb-4">Add New Hospital</a>
            @endif
        @endauth

        <!-- Search and Filter Form -->
        <form action="{{ route('healthfacilities.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search by hospital name">
                </div>
                <div class="col-md">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="public" @selected(request('type') == 'public')>Public</option>
                        <option value="private" @selected(request('type') == 'private')>Private</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>

        @if($hospitals->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fas fa-hospital-user fa-3x mb-3 text-gray-300"></i>
                <p>No data found matching your criteria.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($hospitals as $hospital)
                    <div class="col">
                        <div class="card shadow-sm h-100 d-flex flex-column">
                            <div class="card-body flex-grow-1">
                                <h5 class="card-title fw-bold fs-5">{{ $hospital->name }}</h5>
                                <p class="card-text text-muted">{{ $hospital->address }}</p>

                                <!-- Star Rating -->
                                <div class="d-flex align-items-center mt-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ ($hospital->reviews_avg_rating ?? 0) >= $i ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">{{ number_format($hospital->reviews_avg_rating ?? 0, 1) }} /
                                        5</span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="{{ route('hospitals.showDetails', $hospital->id) }}"
                                    class="btn btn-info btn-sm w-100 mb-2">View Details & Reviews</a>

                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <div class="d-flex justify-content-between">
                                            <!-- Edit Button -->
                                            <a href="{{ route('healthfacilities.edit', $hospital->id) }}"
                                                class="btn btn-warning btn-sm flex-fill me-1">Edit</a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('healthfacilities.destroy', $hospital->id) }}" method="POST"
                                                class="flex-fill ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                                    onclick="return confirm('Are you sure you want to delete this hospital?')">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection