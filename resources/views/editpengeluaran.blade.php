@extends('components.layout')

@section('title', 'Edit Pengeluaran')

@section('content')

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
                <img src="https://i.pravatar.cc/300" alt="">
            </div>
        </div>
    </div>


        <div class="container-content">
            {{-- INPUTAN --}}
            <div class="row">
                <div class="col-md-6">
                    <form id="pengeluaran-form" action="{{ url('pengeluaran-update/' . $pengeluaran->id) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="tanggal" class="col-sm-4 col-form-label">Tanggal Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="tanggal" type="date" class="form-control" id="tanggal" required
                                    value="{{ $pengeluaran->tanggal }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="keterangan" class="col-sm-4 col-form-label">Keterangan Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="keterangan" type="text" class="form-control" id="keterangan"
                                    placeholder="Masukkan Keterangan Pemasukan" required
                                    value="{{ $pengeluaran->keterangan }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="sumber" class="col-sm-4 col-form-label">Sumber Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="sumber" type="text" class="form-control" id="sumber"
                                    placeholder="Masukkan Sumber Pemasukan" required value="{{ $pengeluaran->sumber }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jumlah" class="col-sm-4 col-form-label">Jumlah Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="jumlah" type="number" class="form-control" id="jumlah"
                                    placeholder="Masukkan Jumlah Pemasukan" required value="{{ $pengeluaran->jumlah }}">
                            </div>
                        </div>
                        <a href="{{ url('viewpengeluaran/' . $pengeluaran->id) }}">
                            <button type="submit" class="btn btn-success">Update Data</button>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <script>
            const notification = document.querySelector('.notification');
            const dropdown = notification.querySelector('.dropdown-menu');


            // Fungsi untuk toggle dropdown menu saat notifikasi diklik
            notification.addEventListener("click", () => {
                dropdown.classList.toggle("show");
                notifChanged();
            });

            // Fungsi untuk menutup dropdown jika klik di luar elemen notifikasi
            function notifChanged() {
                if (notification.classList.contains("show")) {
                    dropdown.classList.remove("show");
                }
            }
        </script>
    @endsection
