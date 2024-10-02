@extends('components.layout')

@section('title', 'Informasi Keluarga')

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
            <!-- Konten utama -->
            <div class="container-content">
                <div class="title">
                    <h1>Informasi Keluarga</h1>
                </div>

                <!-- Menampilkan informasi keluarga -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <h4>Nama Keluarga:</h4>
                            <p>{{ $keluarga['nama'] }}</p>
                            <h4>Target Maksimal Pengeluaran:</h4>
                            <p>{{ number_format($keluarga['target_pengeluaran'], 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('edit-keluarga') }}" class="btn btn-primary">Edit Data Keluarga</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
