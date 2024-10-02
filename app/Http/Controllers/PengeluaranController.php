<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran dengan paginasi.
     */
    public function index()
    {
        // Mengambil data pengeluaran yang dipaginate menjadi 5 data per halaman
        $data = Pengeluaran::paginate(5);
        // Memasukkan data ke view 'pengeluaran'
        return view('pengeluaran', compact('data'));
    }

    /**
     * Menampilkan daftar pengeluaran dengan filter dan notifikasi.
     */
    public function view(Request $request)
    {
        // Menampilkan notifikasi berdasarkan session
        if ($request->session()->has('notify_success')) {
            notify()->success($request->session()->get('notify_success'), 'Success');
        }
        if ($request->session()->has('notify_error')) {
            notify()->error($request->session()->get('notify_error'), 'Error');
        }
        if ($request->session()->has('notify_warning')) {
            notify()->warning($request->session()->get('notify_warning'), 'Warning');
        }
        if ($request->session()->has('notify_info')) {
            notify()->info($request->session()->get('notify_info'), 'Info');
        }

        $query = Pengeluaran::query();

        // Filter berdasarkan kriteria pencarian
        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%')
                ->orWhere('sumber', 'like', '%' . $request->search . '%')
                ->orWhere('jumlah', 'like', '%' . $request->search . '%');
        }

        // Memeriksa apakah kedua input 'start_date' dan 'end_date' diisi oleh pengguna
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika keduanya diisi, filter data berdasarkan rentang tanggal antara 'start_date' dan 'end_date'
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        // Jika hanya 'start_date' yang diisi oleh pengguna
        elseif ($request->filled('start_date')) {
            // Filter data yang tanggalnya sama dengan atau lebih besar dari 'start_date'
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        // Jika hanya 'end_date' yang diisi oleh pengguna
        elseif ($request->filled('end_date')) {
            // Filter data yang tanggalnya sama dengan atau lebih kecil dari 'end_date'
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Hitung total pengeluaran
        $totalPengeluaran = $query->sum('jumlah');

        // Dapatkan data dengan paginasi
        $data = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        // Kirim data dan total pengeluaran ke view
        return view('viewpengeluaran', ['data' => $data, 'totalPengeluaran' => $totalPengeluaran]);
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'tanggal' => 'required|date',
                'keterangan' => 'required|min:3|max:255',
                'sumber' => 'required|min:3|max:255',
                'jumlah' => 'required|numeric|min:0',
            ],
            [
                'tanggal.required' => 'Tanggal tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'keterangan.min' => 'Keterangan minimal 3 karakter',
                'keterangan.max' => 'Keterangan maksimal 255 karakter',
                'sumber.required' => 'Sumber tidak boleh kosong',
                'sumber.min' => 'Sumber minimal 3 karakter',
                'sumber.max' => 'Sumber maksimal 255 karakter',
                'jumlah.required' => 'Jumlah tidak boleh kosong',
                'jumlah.numeric' => 'Jumlah harus berupa angka',
                'jumlah.min' => 'Jumlah harus berupa angka',

            ]
        );

        // Ambil target pengeluaran dari session
        $keluarga = session('keluarga', [
            'nama' => 'Nama Keluarga Default',
            'target_pengeluaran' => 100000, // Target pengeluaran default
        ]);

        // Hitung total pengeluaran saat ini
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        // Cek apakah total pengeluaran ditambah dengan pengeluaran baru melebihi target
        if (($totalPengeluaran + $request->input('jumlah')) > $keluarga['target_pengeluaran']) {
            // Simpan notifikasi ke session
            session()->push('notifikasi', 'Pengeluaran melebihi target pengeluaran.');

            // Redirect dengan notifikasi jika melebihi batas target
            return Redirect::back()->with('notify_error', 'Maaf, tidak dapat input pengeluaran karena melebihi batas target pengeluaran.');
        }

        // Simpan data pengeluaran`
        $data = [
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'username' => 'Keyvano',
        ];

        // Ambil total pemasukan dan pengeluaran
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        // Hitung saldo
        $saldo = $totalPemasukan - $totalPengeluaran;

        // CEK APAKAH PENGELUARAAN MELEBIHI SALDO
        if ($request->jumlah > $saldo) {
            // Jika pengeluaran melebihi pemasukan, munculkan notifikasi error
            notify()->error('Pengeluaran anda melebihi pemasukan, mohon lebih berhemat!', 'Gagal');

            // Redirect kembali ke halaman sebelumnya
            return redirect()->back()->withInput()->with('notify_error', 'Pengeluaran anda melebihi pemasukan, mohon lebih berhemat!');
        }


        Pengeluaran::create($data);

        // Menggunakan notifikasi untuk sukses
        notify()->success('Data pengeluaran berhasil disimpan!', 'Sukses');
        return redirect()->route('view-pengeluaran');
    }

    /**
     * Menampilkan form untuk mengedit data pengeluaran.
     */
    public function edit(string $id)
    {
        $pengeluaran = Pengeluaran::find($id);
        return view('editpengeluaran', compact('pengeluaran'));
    }

    /**
     * Memperbarui data pengeluaran.
     */
    public function update(Request $request, string $id)
    {
       $request->validate(
            [
                'tanggal' => 'required|date ',
                'keterangan' => 'required|min:3|max:255',
                'sumber' => 'required|min:3|max:255',
                'jumlah' => 'required|numeric|min:0',
            ],
            [
                'tanggal.required' => 'Tanggal tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
                'keterangan.min' => 'Keterangan minimal 3 karakter',
                'keterangan.max' => 'Keterangan maksimal 255 karakter',
                'sumber.required' => 'Sumber tidak boleh kosong',
                'sumber.min' => 'Sumber minimal 3 karakter',
                'sumber.max' => 'Sumber maksimal 255 karakter',
                'jumlah.required' => 'Jumlah tidak boleh kosong',
                'jumlah.numeric' => 'Jumlah harus berupa angka', 
                'jumlah.min' => 'Jumlah harus berupa angka',
            ]
        );

        Pengeluaran::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'username' => 'Keyvano',
        ]);

        return redirect()->route('view-pengeluaran')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Menghapus data pengeluaran.
     */
    public function destroy($id)
    {
        Pengeluaran::find($id)->delete();
        return redirect()->route('view-pengeluaran')->with('success', 'Data Berhasil Di Hapus');
    }

    /**
     * Mendapatkan total pengeluaran bulanan.
     */
    public function getMonthlyPengeluaran()
    {
        return Pengeluaran::selectRaw('strftime("%m", tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
    }
}
