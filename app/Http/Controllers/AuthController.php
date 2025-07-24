<?php

namespace App\Http\Controllers;

use App\Models\User; // <-- PERBAIKAN: Impor model User untuk kode yang lebih bersih
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- PERBAIKAN: Impor Hash facade

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menangani login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // PERBAIKAN PENTING: Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();

            return redirect()->intended('dashboard'); // Redirect ke dashboard atau halaman sebelumnya
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Menampilkan form register
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // PERBAIKAN: Gunakan User::create dan Hash::make
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // Gunakan Hash::make, bukan bcrypt()
        ]);

        // Login pengguna yang baru saja didaftarkan
        Auth::login($user);

        // Regenerasi sesi setelah login otomatis
        $request->session()->regenerate();

        // Redirect ke dashboard
        return redirect()->route('dashboard.index');
    }

    // Menangani logout
    public function logout(Request $request)
    {
        Auth::logout();

        // PERBAIKAN PENTING: Invalidate session dan regenerate token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama atau halaman login
    }
}