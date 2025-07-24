@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold text-gray-800">Hospital Reviews</h2>
            <p class="text-gray-500">Find reviews for your desired health facility.</p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('reviews.index') }}" method="GET" class="mb-5">
            <div class="input-group input-group-lg">
                <input type="text" name="search" value="{{ $searchTerm ?? '' }}" class="form-control"
                    placeholder="Enter hospital name here...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>

        <!-- Header Hasil -->
        <div class="mb-4">
            @if ($searchTerm)
                <h4 class="font-semibold text-gray-700">Showing results for: <span class="text-primary">{{ $searchTerm }}</span>
                </h4>
            @else
                <h4 class="font-semibold text-gray-700">Showing Recent Reviews from All Hospitals</h4>
            @endif
        </div>

        @if ($hospitals->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-hospital-user fa-3x text-gray-300 mb-3"></i>
                <p class="text-center text-muted">No hospitals found matching your criteria.</p>
            </div>
        @else
            <div class="space-y-5">
                {{-- Loop untuk setiap rumah sakit yang ditemukan oleh controller --}}
                @foreach($hospitals as $hospital)
                    {{-- REVISI UTAMA: Kondisi @if yang salah telah DIHAPUS dari sini --}}
                    {{-- Sekarang view akan menampilkan SEMUA rumah sakit yang dikirim oleh controller --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h2 class="mb-0">{{ $hospital->name }}</h2>
                            <small class="text-muted">{{ $hospital->address }}</small>
                        </div>
                        <div class="card-body">
                            @forelse ($hospital->reviews as $review)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="font-weight: bold; font-size: 16px;">{{ optional($review->user)->name ?? 'Anonymous' }}</span>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mt-2 mb-0">{{ $review->review }}</p>
                                </div>
                            @empty
                                <p class="text-muted">No reviews yet for this hospital. Be the first!</p>
                            @endforelse

                            {{-- Menggunakan nama rute baru kita: 'reviews.showDetails' --}}
                            <a href="{{ route('reviews.showDetails', $hospital->id) }}" class="btn btn-outline-primary mt-2">
                                View All Reviews
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection