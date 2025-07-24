<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan semua data yang diperlukan.
     */
    public function index()
    {
        // PERBAIKAN: Mengambil semua data yang dibutuhkan dengan cara yang sangat efisien
        // untuk menghindari masalah performa N+1 Query.
        $hospitals = Hospital::query()
            // 1. Hitung rata-rata rating untuk setiap rumah sakit langsung di database.
            //    Hasilnya akan tersedia sebagai properti 'reviews_avg_rating'.
            ->withAvg('reviews', 'rating')
            // 2. Muat 3 review terbaru untuk setiap rumah sakit (untuk popup di peta).
            //    Ini dilakukan dalam satu query tambahan, bukan satu per satu.
            ->with(['reviews' => function ($query) {
                $query->latest()->take(3);
            }])
            ->get();

        // --- SIAPKAN DATA UNTUK KARTU RINGKASAN ---
        // Hitung data ini dari koleksi yang sudah kita ambil, tanpa query baru.
        $publicHospitalsCount = $hospitals->where('type', 'public')->count();
        $privateHospitalsCount = $hospitals->where('type', 'private')->count();
        // Hitung rata-rata dari semua rata-rata rating yang sudah dihitung database.
        $averageRating = $hospitals->avg('reviews_avg_rating');

        // --- SIAPKAN DATA UNTUK DAFTAR "TOP RATED HOSPITALS" ---
        // Urutkan koleksi berdasarkan rata-rata rating (tertinggi ke terendah) dan ambil 3 teratas.
        $topRatedHospitals = $hospitals->sortByDesc('reviews_avg_rating')->take(3);

        // Kirim semua data yang sudah disiapkan ke view
        return view('dashboard.index', [
            'hospitals'             => $hospitals,
            'publicHospitalsCount'  => $publicHospitalsCount,
            'privateHospitalsCount' => $privateHospitalsCount,
            'averageRating'         => $averageRating,
            'topRatedHospitals'     => $topRatedHospitals, // Kirim data top rated ke view
        ]);
    }

    // PERBAIKAN: Metode show() ini telah DIHAPUS.
    // Fungsionalitas untuk menampilkan review lengkap sebuah rumah sakit sudah ditangani
    // oleh ReviewController@showAllReviews sesuai dengan struktur rute yang benar.
    // Menghapusnya dari sini akan membuat controller ini lebih fokus dan mencegah kebingungan.
}