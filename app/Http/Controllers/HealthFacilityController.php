<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthFacilityController extends Controller
{
    // Cek otorisasi admin secara terpusat
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Hentikan eksekusi dengan pesan 'Forbidden'
            abort(403, 'You are not authorized to perform this action.');
        }
    }

    public function index(Request $request)
    {
        $query = Hospital::query();

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply filter by type (public/private)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Get hospitals along with average rating
        $hospitals = $query->withAvg('reviews', 'rating')->get();

        // Return the view with hospitals data
        return view('healthfacilities.index', [
            'hospitals' => $hospitals,
            'search' => $request->search,
            'type' => $request->type
        ]);
    }

    public function showDetailsAndReviews(Hospital $hospital)
    {
        // Muat semua review beserta data user-nya secara efisien
        $hospital->load('reviews.user');

        // Hitung rata-rata rating
        $hospital->average_rating = $hospital->reviews->avg('rating');

        // Mengirim data ke view 'show.blade.php' Anda
        // Kita namai ulang view-nya agar tidak bentrok, misalnya 'healthfacilities.details'
        return view('healthfacilities.show', [
            'hospital' => $hospital,
            'reviews' => $hospital->reviews, // Kirim juga variabel reviews agar view Anda berfungsi
        ]);
    }

    public function show(Hospital $hospital)
    {
        // Muat semua review beserta data user-nya secara efisien
        $hospital->load('reviews.user');

        // Hitung rata-rata rating
        $hospital->average_rating = $hospital->reviews->avg('rating');

        // INI BAGIAN KUNCINYA: Pastikan Anda mengirim 'reviews' ke view.
        return view('healthfacilities.show', [
            'hospital' => $hospital,
            'reviews'  => $hospital->reviews, // Variabel ini harus dikirim.
        ]);
    }


    public function create()
    {
        $this->checkAdmin(); // Panggil fungsi cek admin
        return view('healthfacilities.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin(); // Panggil fungsi cek admin

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'required|string',
            'type' => 'nullable|string', // Sesuaikan jika perlu
        ]);

        Hospital::create($validatedData);
        return redirect()->route('healthfacilities.index')->with('success', 'Hospital added successfully!');
    }

    public function edit($id)
    {
        $this->checkAdmin(); // Panggil fungsi cek admin
        $hospital = Hospital::findOrFail($id);
        return view('healthfacilities.edit', compact('hospital'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin(); // Panggil fungsi cek admin

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $hospital = Hospital::findOrFail($id);
        $hospital->update($validatedData);
        return redirect()->route('healthfacilities.index')->with('success', 'Hospital updated successfully!');
    }

    public function destroy($id)
    {
        $this->checkAdmin(); // Ensure the user is an admin

        $hospital = Hospital::findOrFail($id);
        $hospital->delete(); // Delete the hospital record

        return redirect()->route('healthfacilities.index')->with('success', 'Hospital deleted successfully!');
    }
}
