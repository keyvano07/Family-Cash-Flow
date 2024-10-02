@extends('components.layout')

@section('title', 'Edit Pengguna')

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
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div class="subtitle">
                        <h1>Edit Pengguna</h1>
                    </div>
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="bendahara" {{ $user->role === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password (Kosongkan jika tidak diubah):</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
