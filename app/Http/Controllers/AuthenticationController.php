<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import Log

class AuthenticationController extends Controller
{
    // Method untuk menampilkan form registrasi
    public function formRegister()
    {
        return view('register'); // Mengembalikan tampilan form register
    }

    // Method untuk menampilkan form login
    public function index()
    {
        return view('login'); // Mengembalikan tampilan form login
    }

    // Method untuk memproses registrasi
    public function processRegister(Request $request): RedirectResponse
    {
        // Validasi data
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:bendahara,admin,user', // Pastikan nama role sesuai dengan yang ada di database
            ]
        );

        try {
            // Buat pengguna baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            notify()->success('Registratier berhasil, Silahkan coba untuk login', 'Success');
            return redirect()->route('users')->with('success', 'Registrasi berhasil, silakan login.');
        } catch (\Exception $e) {
            // Log error atau tampilkan pesan error
            Log::error('Error during registration: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registrasi gagal, coba lagi.']);
        }
    }

    // ================================  LOGIN  =================================

    // Method untuk memproses login
    public function authenticate(Request $request): RedirectResponse
    {
        // Validasi input dari form login
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah kredensial cocok dengan data di database
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk mencegah session fixation attack
            $request->session()->regenerate();

            // Mengambil total pemasukan dan pengeluaran untuk ditampilkan di dashboard
            $pemasukan = Pemasukan::all();
            $total_pemasukan = $pemasukan->sum('jumlah'); // Menghitung total pemasukan

            $pengeluaran = Pengeluaran::all();
            $total_pengeluaran = $pengeluaran->sum('jumlah'); // Menghitung total pengeluaran

            // Menampilkan notifikasi sukses dan mengarahkan ke dashboard dengan data total pengeluaran dan pemasukan
            notify()->success('Login successful, welcome back!', 'Sukses');
            return redirect()->intended('/dashboard')->with([
                'total_pengeluaran' => $total_pengeluaran,
                'total_pemasukan' => $total_pemasukan
            ]);
        }

        // Jika kredensial tidak cocok, kembali ke halaman login dengan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Method untuk logout pengguna
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regeneraTeToken();

        return redirect('/');
    }
}
