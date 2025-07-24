<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Gunakan Auth facade yang lebih andal

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('hospitals.index', compact('hospitals'));
    }

    // Menampilkan form untuk menambahkan rumah sakit
    public function create()
    {
        // PERUBAHAN: Hanya cek apakah pengguna sudah login.
        if (!Auth::check()) {
            // Redirect ke halaman login jika belum login
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        return view('hospitals.create');
    }

    // Menyimpan rumah sakit baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'type' => 'required|in:public,private', // Validation for 'type'
        ]);

        $hospital = new Hospital();
        $hospital->name = $request->name;
        $hospital->address = $request->address;
        $hospital->latitude = $request->latitude;
        $hospital->longitude = $request->longitude;
        $hospital->description = $request->description;
        $hospital->type = $request->type;  // Storing the 'type'
        $hospital->save();

        return redirect()->route('healthfacilities.index')->with('success', 'Hospital added successfully!');
    }


    public function show(Hospital $hospital)
    {
        // Load reviews for the specific hospital
        $hospital->load('reviews.user'); // Eager load reviews and associated user

        return view('hospitals.show', compact('hospital'));
    }
}
