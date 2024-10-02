<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditkeluargaController extends Controller
{
    // Menampilkan halaman informasi keluarga
    public function view()
    {
        // Mengambil data dari session atau menggunakan default value
        $keluarga = session('keluarga', [
            'nama' => 'Nama Keluarga Default',
            'target_pengeluaran' => 1000000,
        ]);

        // Menampilkan view informasi keluarga dengan data
        return view('informasikeluarga', compact('keluarga'));
    }

    // Menampilkan halaman pengaturan keluarga
    public function index()
    {
        // Mengambil data yang ada di session atau konfigurasi
        $keluarga = session('keluarga', [
            'nama' => 'Nama Keluarga Default',
            'target_pengeluaran' => 1000000, // Target pengeluaran default
        ]);

        // Menampilkan view editkeluarga dengan data keluarga
        return view('editkeluarga', compact('keluarga'));
    }

    // Memperbarui data keluarga
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'target_pengeluaran' => 'required|numeric|min:0',
        ]);

        // Simpan data ke session
        session([
            'keluarga' => [
                'nama' => $request->input('nama'),
                'target_pengeluaran' => $request->input('target_pengeluaran'),
            ],
        ]);

        // Logika untuk memeriksa jika pengeluaran melebihi pemasukan
        // $pengeluaran = 1200000; // Contoh nilai pengeluaran yang bisa diambil dari database
        // $pemasukan = 1000000; // Contoh nilai pemasukan yang bisa diambil dari database

        // if ($pengeluaran > $pemasukan) {
        //     // Simpan notifikasi ke session
        //     session()->push('notifikasi', 'Pengeluaran melebihi pemasukan.');
        // }

        // Redirect ke halaman informasi keluarga dengan pesan sukses
        return redirect()->route('informasi-keluarga')->with('success', 'Pengaturan keluarga berhasil diperbarui.');
    }
}
