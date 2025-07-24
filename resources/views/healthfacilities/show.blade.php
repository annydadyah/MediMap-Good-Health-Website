@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Tombol Kembali --}}
    <a href="{{ route('healthfacilities.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    {{-- Nama Rumah Sakit besar dan bold --}}
    <h1 class="fw-bold display-6 mb-4 text-primary">{{ $hospital->name }}</h1>

    <div class="row">
        <div class="col-md-8">
            <!-- Hospital Description and Address -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold fs-5">Address</h5>
                    <p class="card-text text-justify">{{ $hospital->address }}</p>

                    <h5 class="fw-bold fs-5 mt-3">Description</h5>
                    <p class="card-text text-justify">{{ $hospital->description }}</p>
                    
                    <!-- Star Rating -->
                    <div class="d-flex align-items-center mt-3">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star {{ ($hospital->average_rating ?? 0) > $i ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ms-2 text-muted">{{ number_format($hospital->average_rating, 1) }} / 5</span>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold fs-5">Recent Reviews</h5>
                    @if($reviews->isEmpty())
                        <p class="text-justify">No reviews yet.</p>
                    @else
                        @foreach($reviews as $review)
                            <div class="mb-3 border-bottom pb-2">
                                <p><strong>{{ $review->user->name }}</strong> rated it <span class="text-warning">{{ $review->rating }} / 5</span></p>
                                <p class="text-justify">{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
