@extends('components.layout')

@section('title', 'Edit Keluarga')

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

        <!-- Konten utama -->
        <div class="container-content">
            {{-- Form untuk mengatur keluarga --}}
            <div class="row">
                <div class="col-md-6">
                    <form id="keluarga-form" action="{{ route('edit-keluarga.update') }}" method="post">
                        @csrf

                        <!-- Input Nama Keluarga -->
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-4 col-form-label">Nama Keluarga</label>
                            <div class="col-sm-8">
                                <input name="nama" type="text" class="form-control" id="nama"
                                    placeholder="Masukkan Nama Keluarga" required
                                    value="{{ old('nama', $keluarga['nama']) }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Input Target Maksimal Pengeluaran -->
                        <div class="row mb-3">
                            <label for="target_pengeluaran" class="col-sm-4 col-form-label">Target Maksimal
                                Pengeluaran</label>
                            <div class="col-sm-8">
                                <input name="target_pengeluaran" type="number" class="form-control" id="target_pengeluaran"
                                    placeholder="Masukkan Target Maksimal Pengeluaran" required
                                    value="{{ old('target_pengeluaran', $keluarga['target_pengeluaran']) }}">
                                @error('target_pengeluaran')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol untuk menyimpan perubahan -->
                        <button type="submit" class="btn btn-success">Simpan Pengaturan</button>
                    </form>
                </div>
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
