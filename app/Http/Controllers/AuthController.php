<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\VerifyEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Tampilkan form registrasi
    public function showLoginForm()
{
    return view('auth.login');
}

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    // Proses registrasi
public function register(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone_number' => 'required|string|max:15',
        'password' => 'required|string|confirmed|min:8',
    ]);

    // Buat user baru dengan token verifikasi
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone_number' => $validated['phone_number'],
        'password' => Hash::make($validated['password']),
        'role' => 'user',
        'verification_token' => Str::random(32),  // Generate token verifikasi
    ]);

    // Kirim email verifikasi
    Mail::to($user->email)->send(new VerifyEmail($user));

    // Redirect ke halaman verifikasi setelah registrasi
    return redirect()->route('verify.pending')->with('status', 'Regiatrasi berhasil, segera cek email anda!');
}
public function resendVerification(Request $request)
{
    // Jika user sudah login, bisa ambil dari Auth::user(), 
    // tapi karena Anda pakai $request->email, gunakan email itu saja

    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'User tidak ditemukan.']);
    }

    if ($user->email_verified_at !== null) {
        return redirect()->route('dashboard.index')->with('status', 'Email sudah terverifikasi.');
    }

    // Generate token verifikasi baru dan simpan
    $user->verification_token = Str::random(32);
    $user->save();

    // Kirim email verifikasi dengan token baru
    Mail::to($user->email)->send(new VerifyEmail($user));

    return back()->with('status', 'Email verifikasi telah dikirim ulang. Silakan cek inbox Anda.');
}



    // Proses verifikasi email
    public function verify($token)
{
    // Cari user berdasarkan token verifikasi
    $user = User::where('verification_token', $token)->first();

    if ($user) {
        // Set email_verified_at dan hapus token
        $user->email_verified_at = now();
        $user->verification_token = null;  // Menghapus token setelah verifikasi
        $user->save();

        // Redirect ke halaman login dengan status verifikasi sukses
        return redirect()->route('login.form')->with('status', 'Email terVerifikasi!');
    } else {
        // Jika token tidak valid
        return redirect()->route('login.form')->with('error', 'Verifikasi tidak valid.');
    }
}



    // Proses login
  public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Cek apakah kredensial valid
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah admin, jika ya, langsung arahkan ke panel admin Filament
        if ($user->role === 'admin') {
            return redirect('redirect'); // Filament default route untuk admin
        }

        // Pastikan email sudah diverifikasi
        if (!$user->email_verified_at) {
            return redirect()->route('verify.pending'); // Jika email belum diverifikasi
        }

        // Cek apakah akun diblokir
        if ($user->is_blocked) {
            Auth::logout(); // Logout user yang diblokir
            return redirect()->route('login.form')->withErrors(['email' => 'Akun Anda telah diblokir. Hubungi email admin : hearthorizoncourse@gmail.com']);
        }

        // Jika sudah terverifikasi dan tidak diblokir, arahkan ke dashboard user biasa
        return redirect()->route('dashboard.index');
    }

    // Jika login gagal, kembali ke login page dengan error
    return back()->withErrors(['email' => 'Kredensial tidak sesuai']);
}

    // Proses logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
    public function showForgotPasswordForm()
{
    return view('auth.forgot-password'); // Halaman form lupa password
}
public function sendResetLink(Request $request)
{
    // Validasi email
    $request->validate(['email' => 'required|email|exists:users,email']); // Validasi email

    // Ambil data user berdasarkan email
    $user = User::where('email', $request->email)->first();

    // Generate token untuk reset password
    $token = Str::random(32);

    // Simpan token di tabel password_resets
    PasswordReset::create([
        'email' => $request->email,
        'token' => $token,
        'created_at' => now(),
    ]);

    // Kirimkan email reset password
    try {
        // Mengirim email reset password dengan user dan token
        Mail::to($request->email)->send(new ResetPassword($user, $token));
    } catch (\Exception $e) {
        // Menangani error jika email gagal dikirim
        return back()->withErrors(['email' => 'Gagal mengirim email reset password.']);
    }

    // Kembali dengan status sukses
    return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
}

    public function showResetForm($token)
{
    // Menampilkan form reset password dengan token
    return view('auth.reset-password', ['token' => $token]);
}
public function resetPassword(Request $request)
{
    // Validasi input
    $request->validate([
        'token' => 'required|string',
        'password' => 'required|string|confirmed|min:8', // Validasi password
    ]);

    // Cek token di tabel password_resets
    $reset = PasswordReset::where('token', $request->token)->first();

    if (!$reset) {
        return redirect()->route('login.form')->with('error', 'Token reset tidak valid.');
    }

    // Cek pengguna berdasarkan email
    $user = User::where('email', $reset->email)->first();
    if (!$user) {
        return redirect()->route('login.form')->with('error', 'Pengguna tidak ditemukan.');
    }

    // Cek jika password baru sama dengan password lama
    if (Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama.']);
    }

    // Update password pengguna di tabel users
    $user->password = Hash::make($request->password);
    $user->save();

    // Hapus token reset setelah digunakan
    $reset->delete();

    // Redirect ke halaman login dengan status reset password berhasil
    return redirect()->route('login.form')->with('status', 'Password berhasil direset.');
}


}
