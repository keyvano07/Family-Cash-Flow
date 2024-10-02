@extends('components.layout')

@section('title', 'Tambah Pemasukan')

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
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="subtitle">
                        <h1>Pemasukan</h1>
                    </div>
                    <form id="pemasukan-form" action="{{ url('view-pemasukan') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <label for="tanggal" class="col-sm-4 col-form-label">Tanggal Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="tanggal" type="date" class="form-control" id="tanggal" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="keterangan" class="col-sm-4 col-form-label">Keterangan Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="keterangan" type="text" class="form-control" id="keterangan"
                                    placeholder="Masukkan Keterangan Pemasukan" required value="{{ old('keterangan') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="sumber" class="col-sm-4 col-form-label">Sumber Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="sumber" type="text" class="form-control" id="sumber"
                                    placeholder="Masukkan Sumber Pemasukan" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jumlah" class="col-sm-4 col-form-label">Jumlah Pemasukkan</label>
                            <div class="col-sm-8">
                                <input name="jumlah" type="number" class="form-control" id="jumlah"
                                    placeholder="Masukkan Jumlah Pemasukan" min="0" required>
                            </div>
                        </div>
                        <button type="submit" class="btn mb-5">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
