<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $pemasukanController;
    protected $pengeluaranController;

    public function __construct(PemasukanController $pemasukanController, PengeluaranController $pengeluaranController)
    {
        $this->pemasukanController = $pemasukanController;
        $this->pengeluaranController = $pengeluaranController;
    }

    public function dashboard()
    {
        // Daftar nama bulan
        $monthNames = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        // Ambil data pemasukan dalam 12 bulan terakhir dari PemasukanController
        $pemasukan = $this->pemasukanController->getMonthlyPemasukan();

        // Ambil data pengeluaran dalam 12 bulan terakhir dari PengeluaranController
        $pengeluaran = $this->pengeluaranController->getMonthlyPengeluaran();

        // Gabungkan dan urutkan data berdasarkan bulan
        $data = collect(range(1, 12))->mapWithKeys(function ($month) use ($pemasukan, $pengeluaran, $monthNames) {
            $bulan = str_pad($month, 2, '0', STR_PAD_LEFT);
            return [
                $bulan => [
                    'bulan_nama' => $monthNames[$bulan],
                    'pemasukan' => $pemasukan->firstWhere('bulan', $bulan)->total ?? 0,
                    'pengeluaran' => $pengeluaran->firstWhere('bulan', $bulan)->total ?? 0,
                ]
            ];
        });

        $months = $data->pluck('bulan_nama');
        $pemasukanData = $data->pluck('pemasukan');
        $pengeluaranData = $data->pluck('pengeluaran');

        // Ambil total pemasukan
        $total_pemasukan = $pemasukan->sum('total');

        // Ambil total pengeluaran
        $total_pengeluaran = $pengeluaran->sum('total');

        // Hitung saldo
        $saldo = $total_pemasukan - $total_pengeluaran;

        // Format saldo dengan pemisah ribuan koma dan tanpa desimal
        $saldo_dompet = number_format($saldo, 0, ',', '.');

        // Cek jika total pengeluaran melebihi total pemasukan
        // if ($total_pemasukan < $total_pengeluaran) {
        //     // Simpan notifikasi dalam session bahwa pengeluaran melebihi pemasukan
        //     session()->flash('notifikasi', 'Total pengeluaran melebihi total pemasukan. Anda tidak dapat menambahkan pengeluaran lagi.');

        //     // Redirect ke halaman sebelumnya atau halaman dashboard
        //     return redirect()->back();
        // }

        // Mengembalikan saldo pemasukan, pengeluaran, dan saldo dompet, serta nama bulan
        return view('dashboard', compact('total_pemasukan', 'total_pengeluaran', 'saldo_dompet', 'months', 'pemasukanData', 'pengeluaranData', 'monthNames'));
    }
}
