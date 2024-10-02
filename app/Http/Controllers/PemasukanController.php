<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index()
    {
        // Mengambil data pemasukan yang dipaginate menjadi 5 data per halaman
        $data = Pemasukan::paginate(5);
        // Memasukkan data ke view 'pemasukan' jangan di ganti ke viewpemasukan
        return view('pemasukan', compact('data'));
    }

    public function view(Request $request)
    {

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

        $query = Pemasukan::query();

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


        // Hitung total pemasukan
        $totalPemasukan = $query->sum('jumlah');

        // Dapatkan data dengan paginasi
        $data = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        // dd($data);

        // Kirim data dan total pemasukan ke view
        return view('ViewPemasukan', ['data' => $data, 'totalPemasukan' => $totalPemasukan]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'tanggal' => 'required',
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

        pemasukan::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'username' => 'Keyvano',
        ]);

        // Pemasukan::create($data);

        // return redirect()->route('view-pemasukan')->with('success', 'Berhasil simpan data');
        // return redirect()->route('view-pemasukan')->with('notify_success', 'Berhasil simpan data');
        notify()->success('Data pemasukan berhasil disimpan!', 'Sukses');
        return redirect()->route('add-pemasukan');
    }

    public function edit(string $id)
    {
        $pemasukan = Pemasukan::where('id', $id)->first();
        return view('editpemasukan', compact('pemasukan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'tanggal' => 'required',
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

        Pemasukan::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'sumber' => $request->sumber,
            'jumlah' => $request->jumlah,
            'username' => 'Keyvano',
        ]);

        return redirect()->route('view-pemasukan')->with('success', 'Data Berhasil Di Update');
    }

    public function destroy($id)
    {
        Pemasukan::find($id)->delete();
        return redirect()->route('view-pemasukan')->with('success', 'Data Berhasil Di Hapus');
    }
    public function getMonthlyPemasukan()
    {
        return Pemasukan::selectRaw('strftime("%m", tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
    }
}
