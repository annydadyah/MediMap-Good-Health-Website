@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Tombol untuk kembali ke halaman daftar review utama --}}
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary mb-4">
            <i class="fas fa-arrow-left me-1"></i> Back to Review Search
        </a>

        {{-- HEADER HALAMAN --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    {{-- Nama Rumah Sakit sebagai Judul Utama --}}
                    <h1 class="card-title fw-bold display-6 text-primary mb-1">{{ $hospital->name }}</h1>
                    <p class="card-subtitle text-muted">{{ $hospital->address }}</p>

                    {{-- Tampilan Rata-rata Rating (Average Rating) --}}
                    <div class="d-flex align-items-center mt-3">
                        <span class="fs-5 me-2">Overall Rating:</span>
                        @for($i = 1; $i <= 5; $i++)
                            <i
                                class="fas fa-star fs-4 {{ ($hospital->average_rating ?? 0) >= $i ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                        <span
                            class="ms-2 fs-5 text-muted fw-bold">{{ number_format($hospital->average_rating ?? 0, 1) }}</span>
                        <span class="ms-1 fs-6 text-muted">/ 5</span>
                    </div>
                </div>

                {{-- Tombol untuk Menambahkan Review Baru --}}
                @auth
                    <<a href="{{ route('reviews.create', $hospital) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-1"></i> Add Your Review
                        </a>
                @endauth
            </div>
        </div>


        {{-- DAFTAR SEMUA REVIEW YANG ADA --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">All Reviews ({{ $hospital->reviews->count() }})</h5>
            </div>
            <div class="card-body">
                {{-- @forelse adalah cara elegan untuk looping, dengan penanganan jika data kosong --}}
                @forelse ($hospital->reviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            {{-- optional() mencegah error jika user yang menulis review telah dihapus --}}
                            <span class="fw-bold">{{ optional($review->user)->name ?? 'Anonymous User' }}</span>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        {{-- Menampilkan rating spesifik untuk review ini --}}
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                            @endfor
                        </div>
                        <p class="mt-2 mb-0">{{ $review->review }}</p>
                    </div>
                    {{-- Blok @empty akan dijalankan jika $hospital->reviews kosong --}}
                @empty
                    <p class="text-muted">There are no reviews for this hospital yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection