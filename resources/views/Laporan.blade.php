@extends('components.layout')

@section('title', 'Laporan')

@section('content')
    <div class="notify">
        <x-notify::notify />
        @notifyJs
    </div>
    <div class="container">
        <div class="container-header">
            <div class="right">
                <div class="notification">
                    <i class="bx bx-bell"></i>
                    <!-- Tampilkan jumlah notifikasi -->
                    <span class="badge badge-pill badge-danger">
                        {{ count(session('notifikasi', [])) }}
                    </span>
                    <!-- Dropdown menu notifikasi -->
                    <div class="dropdown-menu">
                        @php
                            $notifikasi = session('notifikasi', []);
                        @endphp

                        @if (count($notifikasi) > 0)
                            @foreach ($notifikasi as $message)
                                <div class="dropdown-item">
                                    <i class='bx bx-bell'></i>
                                    {{ $message }}
                                </div>
                            @endforeach
                        @else
                            <div class="dropdown-item">
                                <i class='bx bx-bell-off'></i>
                                Tidak ada notifikasi
                            </div>
                        @endif
                    </div>
                </div>
                <div class="user-profile">
                    {{-- <span>John Doe</span>
                <i class="bx bx-chevron-down"></i> --}}
                    <img src="https://i.pravatar.cc/300" alt="">
                </div>
            </div>
        </div>

        <div class="container-content">
            <div class="col-md-12 mt-5">
                <form id="laporan" action="{{ route('laporan.export') }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="laporan">
                            <tr>
                                <td>Jenis laporan</td>
                                <td colspan="3">
                                    <select id="jenis-laporan" name="jenis-laporan" class="form-control">
                                        <option value="pemasukan">Pemasukan</option>
                                        <option value="pengeluaran">Pengeluaran</option>
                                        <option value="semua">Semua</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Pilih tanggal</td>
                                <td><input type="date" id="awal" name="awal" class="form-control control">
                                </td>
                                <td>sampai</td>
                                <td><input type="date" id="akhir" name="akhir" class="form-control control">
                                </td>
                                <td><button type="submit" class="btn btn-primary lapor">Tampilkan</button></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
