@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold text-gray-800">{{ $hospital->name }} - Reviews</h2>
            <p class="text-gray-500">{{ $hospital->address }}</p>
        </div>

        @auth
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 col-md-10">
                    <form action="{{ route('reviews.store', $hospital->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            {{-- Menggunakan d-flex dan text-center untuk button agar merata dan text di tengah --}}
                            {{-- Tambahkan kelas custom 'rating-buttons-container' untuk styling lebar --}}
                            <div class="d-flex justify-content-between" id="number-rating-container">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="flex-fill text-center"> {{-- flex-fill akan membuat setiap label memiliki lebar
                                        yang sama --}}
                                        <input type="radio" name="rating" value="{{ $i }}" class="d-none rating-input" required />
                                        <div class="btn btn-outline-secondary rating-button w-100 py-3 fs-4" data-value="{{ $i }}">
                                            {{ $i }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Your Review</label>
                            <textarea name="review" id="review" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('reviews.showDetails', $hospital->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Submit Review
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endauth

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="space-y-5">
                    @foreach ($hospital->reviews as $review)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">{{ optional($review->user)->name ?? 'Anonymous' }}</h5>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="card-body">
                                <div>
                                    <span class="badge bg-primary fs-5">{{ $review->rating }}</span>
                                </div>
                                <p class="mt-2 mb-0">{{ $review->review }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if ($hospital->reviews->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-comment-dots fa-3x text-gray-300 mb-3"></i>
                            <p class="text-center text-muted">No reviews yet. Be the first to leave a review!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ratingButtons = document.querySelectorAll('.rating-button');
                const ratingInputs = document.querySelectorAll('.rating-input');

                ratingButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const value = this.getAttribute('data-value');

                        // Set the corresponding radio button as checked
                        document.querySelector(`input[name="rating"][value="${value}"]`).checked = true;

                        // Remove active class from all buttons
                        ratingButtons.forEach(btn => btn.classList.remove('btn-primary'));
                        ratingButtons.forEach(btn => btn.classList.add('btn-outline-secondary'));


                        // Add active class to the clicked button and all previous ones
                        // This creates the "filled" effect from 1 up to the selected number
                        for (let i = 1; i <= value; i++) {
                            const targetButton = document.querySelector(`.rating-button[data-value="${i}"]`);
                            if (targetButton) {
                                targetButton.classList.remove('btn-outline-secondary');
                                targetButton.classList.add('btn-primary');
                            }
                        }
                    });
                });

                // Optional: Handle initial selection if a rating is already set (e.g., for editing)
                const initialRating = document.querySelector('input[name="rating"]:checked');
                if (initialRating) {
                    const value = initialRating.value;
                    for (let i = 1; i <= value; i++) {
                        const targetButton = document.querySelector(`.rating-button[data-value="${i}"]`);
                        if (targetButton) {
                            targetButton.classList.remove('btn-outline-secondary');
                            targetButton.classList.add('btn-primary');
                        }
                    }
                }
            });
        </script>
    @endpush

@endsection