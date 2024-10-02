<?php

namespace App\Http\Controllers;

use App\Exports\ExportLaporan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan');
    }

    public function export_excel(Request $request)
    {
        $awal = $request->input('awal');
        $akhir = $request->input('akhir');
        $jenis = $request->input('jenis-laporan');

        return Excel::download(new ExportLaporan($awal, $akhir, $jenis), 'laporan.xlsx');
    }
}
