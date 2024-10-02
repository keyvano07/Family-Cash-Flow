<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportLaporan implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $awal;
    protected $akhir;
    protected $jenis;

    // Konstruktor untuk menerima parameter awal, akhir, dan jenis laporan
    public function __construct($awal, $akhir, $jenis)
    {
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->jenis = $jenis;
    }

    // Mengambil data koleksi untuk diekspor
    public function collection()
    {
        $awal = $this->awal . ' 00:00:00';
        $akhir = $this->akhir . ' 23:59:59';
        $data = [];

        // ******************** MENGAMBIL DATA PEMASUKAN ********************

        if ($this->jenis == 'pemasukan' || $this->jenis == 'semua') {
            // Menambahkan judul untuk pemasukan
            $data[] = ['jenis' => 'PEMASUKAN', 'tanggal' => null, 'keterangan' => null, 'sumber' => null, 'jumlah' => null];

            // Mengambil data pemasukan dari database berdasarkan rentang tanggal
            $pemasukan = Pemasukan::select(DB::raw("'Pemasukan' as jenis"), 'tanggal', 'keterangan', 'sumber', 'jumlah')
                ->whereBetween('tanggal', [$awal, $akhir])
                ->get();

            // Menambahkan data pemasukan ke dalam array
            foreach ($pemasukan as $item) {
                $data[] = [
                    'jenis' => $item->jenis,
                    'tanggal' => $item->tanggal,
                    'keterangan' => $item->keterangan,
                    'sumber' => $item->sumber,
                    'jumlah' => $item->jumlah,
                ];
            }

            // Menghitung total pemasukan dan menambahkannya ke array data
            $totalPemasukan = collect($data)->where('jenis', 'Pemasukan')->sum('jumlah');
            $data[] = [
                'jenis' => 'Total Pemasukan',
                'tanggal' => null,
                'keterangan' => null,
                'sumber' => null,
                'jumlah' => $totalPemasukan
            ];
        }

        // ******************** MENGAMBIL DATA PENGELUARAN ********************

        if ($this->jenis == 'pengeluaran' || $this->jenis == 'semua') {
            // Menambahkan baris kosong jika laporan adalah semua
            if ($this->jenis == 'semua') {
                $data[] = ['jenis' => null, 'tanggal' => null, 'keterangan' => null, 'sumber' => null, 'jumlah' => null];
            }

            // Menambahkan judul untuk pengeluaran
            $data[] = ['jenis' => 'PENGELUARAN', 'tanggal' => null, 'keterangan' => null, 'sumber' => null, 'jumlah' => null];

            // Mengambil data pengeluaran dari database berdasarkan rentang tanggal
            $pengeluaran = Pengeluaran::select(DB::raw("'Pengeluaran' as jenis"), 'tanggal', 'keterangan', 'sumber', 'jumlah')
                ->whereBetween('tanggal', [$awal, $akhir])
                ->get();

            // Menambahkan data pengeluaran ke dalam array
            foreach ($pengeluaran as $item) {
                $data[] = [
                    'jenis' => $item->jenis,
                    'tanggal' => $item->tanggal,
                    'keterangan' => $item->keterangan,
                    'sumber' => $item->sumber,
                    'jumlah' => $item->jumlah,
                ];
            }

            // Menghitung total pengeluaran dan menambahkannya ke array data
            $totalPengeluaran = collect($data)->where('jenis', 'Pengeluaran')->sum('jumlah');
            $data[] = [
                'jenis' => 'Total Pengeluaran',
                'tanggal' => null,
                'keterangan' => null,
                'sumber' => null,
                'jumlah' => $totalPengeluaran
            ];
        }

        // Menghitung saldo jika jenis laporan adalah semua
        if ($this->jenis == 'semua') {
            $saldo = $totalPemasukan - $totalPengeluaran;
            $data[] = [
                'jenis' => 'Saldo',
                'tanggal' => null,
                'keterangan' => null,
                'sumber' => null,
                'jumlah' => $saldo
            ];
        }

        // Format jumlah dengan menggunakan number_format
        foreach ($data as &$row) {
            // Periksa jika 'jumlah' tidak null dan merupakan angka sebelum memformat
            if (!is_null($row['jumlah']) && is_numeric($row['jumlah'])) {
                $row['jumlah'] = number_format($row['jumlah'], 0, '.', ',');
            }
        }

        return collect($data);
    }

    // Menentukan header untuk kolom Excel
    public function headings(): array
    {
        return [
            'Jenis',
            'Tanggal',
            'Keterangan',
            'Sumber',
            'Jumlah',
        ];
    }

    // Menentukan gaya untuk worksheet Excel
    public function styles(Worksheet $sheet)
    {
        $pengeluaranRow = null;

        // Menentukan baris untuk judul "PENGELUARAN" berdasarkan jenis laporan
        if ($this->jenis == 'pengeluaran') {
            $pengeluaranRow = 2;
        } elseif ($this->jenis == 'semua') {
            // Hitung jumlah baris data pemasukan dan tambahkan offset untuk baris pengeluaran
            $pengeluaranRow = count(Pemasukan::whereBetween('tanggal', [$this->awal . ' 00:00:00', $this->akhir . ' 23:59:59'])->get()) + 4;
        }

        // Menerapkan gaya jika baris untuk "PENGELUARAN" ditemukan
        if ($pengeluaranRow) {
            return [
                1 => ['font' => ['bold' => true]], // Gaya untuk header
                'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Rata tengah kolom A
                'E' => ['font' => ['bold' => true, 'color' => ['argb' => 'FF0000']]], // Gaya untuk kolom E (jumlah)
                $pengeluaranRow => [
                    'font' => ['bold' => true, 'size' => 16], // Gaya untuk judul "PENGELUARAN"
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], // Rata tengah untuk judul
                ],
            ];
        }

        // Gaya default jika baris untuk "PENGELUARAN" tidak ditemukan
        return [
            1 => ['font' => ['bold' => true]], // Gaya untuk header
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // Rata tengah kolom A
            'E' => ['font' => ['bold' => true, 'color' => ['argb' => 'FF0000']]], // Gaya untuk kolom E (jumlah)
        ];
    }

    // Menentukan lebar kolom untuk worksheet Excel
    public function columnWidths(): array
    {
        return [
            'A' => 20, // Lebar kolom untuk Jenis
            'B' => 20, // Lebar kolom untuk Tanggal
            'C' => 30, // Lebar kolom untuk Keterangan
            'D' => 20, // Lebar kolom untuk Sumber
            'E' => 20, // Lebar kolom untuk Jumlah
        ];
    }
}
