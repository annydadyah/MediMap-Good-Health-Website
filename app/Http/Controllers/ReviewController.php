<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Hospital::query();
        if ($searchTerm) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        }
        $hospitals = $query
            ->withCount('reviews')
            ->with(['reviews' => function ($q) {
                $q->with('user')->latest()->take(5);
            }])
            ->latest()
            ->get();
        return view('reviews.index', ['hospitals' => $hospitals, 'searchTerm' => $searchTerm]);
    }

    // PERBAIKAN: Metode show() telah DIHAPUS dari controller ini.

    public function store(Request $request, Hospital $hospital)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to leave a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $hospital->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // PERBAIKAN: Redirect kembali ke halaman detail yang benar
        return redirect()->route('healthfacilities.show', $hospital->id)->with('success', 'Thank you for your review!');
    }

    public function showDetails(Hospital $hospital)
    {
        // Muat semua review beserta data user-nya secara efisien untuk menghindari N+1 problem
        $hospital->load('reviews.user');
        
        // Hitung rata-rata rating
        $hospital->average_rating = $hospital->reviews->avg('rating');

        // Mengirim data ke view 'reviews.show.blade.php'
        return view('reviews.show', [
            'hospital' => $hospital,
            'reviews' => $hospital->reviews, // Kirim juga variabel reviews agar view Anda berfungsi
        ]);
    }

    public function create(Hospital $hospital)
    {
        // Pastikan pengguna sudah login untuk bisa mengakses halaman form ini
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to leave a review.');
        }

        // Kirim data rumah sakit ke view 'reviews.create'
        return view('reviews.create', compact('hospital'));
    }
}