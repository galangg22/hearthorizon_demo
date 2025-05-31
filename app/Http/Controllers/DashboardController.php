<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Menambahkan pengecekan untuk blokir di semua method
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         // Cek apakah user sedang login dan apakah akun diblokir    
    //         if (Auth::check() && Auth::user()->is_blocked) {
    //             Auth::logout(); // Logout pengguna yang diblokir
    //             return redirect()->route('login.form')->withErrors(['email' => 'Akun Anda telah diblokir.']);
    //         }
    //         return $next($request);
    //     });
    // }

    // Menampilkan halaman dashboard
    public function index()
    {
        // Ambil semua kategori dari database
        $categories = Category::all();

        // Kirim variabel $categories ke view dashboard
        return view('dashboard', compact('categories'));
    }

    // Menampilkan kategori dan video berdasarkan kategori
    public function showCategory(Category $category)
    {
        $videos = $category->videos()->latest()->get(); // Ambil video berdasarkan kategori
        return view('category.show', compact('category', 'videos'));
    }

    // Menampilkan detail video
    public function showVideo(Video $video)
    {
        $parts = $video->videoParts()->orderBy('part_number')->get(); // Ambil part video sesuai urutan
        return view('video.show', compact('video', 'parts'));
    }
}
