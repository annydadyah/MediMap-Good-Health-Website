<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your profile.');
        }

        $user = Auth::user();
        $totalReviews = $user->reviews()->count();

        // PERBAIKAN: Gunakan with('hospital') untuk eager loading
        // Ini akan mencegah N+1 query problem di dalam view
        $recentReviews = $user->reviews()
            ->with('hospital') // Muat data rumah sakit terkait secara efisien
            ->latest()
            ->take(5)
            ->get();

        return view('profile.show', compact('user', 'totalReviews', 'recentReviews'));
    }

    public function edit()
    {
        // PERBAIKAN: Cek login secara manual
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit your profile.');
        }

        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // PERBAIKAN: Cek login secara manual
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // PERBAIKAN: Validasi email dibuat lebih baik
        // Rule 'unique' akan mengabaikan email milik user saat ini
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Kode ini akan berfungsi setelah cache dibersihkan
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
