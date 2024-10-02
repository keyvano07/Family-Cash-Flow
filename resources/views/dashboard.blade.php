@extends('components.layout')

@section('title', 'Dashboard')

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
                        @if ($errors->any())
                            <div class="dropdown-item">
                                <i class='bx bx-bell'></i>
                                {{ $errors->first() }}
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
            <div class="title">
                <h1>Beranda</h1>
            </div>
            <!-- Cards Section -->
            <div class="row justify-content-center align-items-center">
                <!-- Wallet Balance Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-wallet icon-big"></i>
                            <div class="card-category">Saldo Dompet</div>
                            <div class="card-title">Rp{{ $saldo_dompet }}</div>
                        </div>
                    </div>
                </div>
                <!-- Expenses Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-dollar-sign icon-big"></i>
                            <div class="card-category">Pengeluaran</div>
                            <div class="card-title">Rp{{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
                            <div class="overlay">
                                <a href="{{ url('pengeluaran') }}" class="btn">Tambah Data</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Income Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-money-bill-wave icon-big"></i>
                            <div class="card-category">Pemasukkan</div>
                            <div class="card-title">Rp{{ number_format($total_pemasukan, 0, ',', '.') }}</div>
                            <div class="overlay">
                                <a href="{{ url('pemasukan') }}" class="btn">Tambah Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-6"> <!-- Mengatur lebar kontainer -->
                    <div>
                        <canvas id="lineChart" style="max-width: 100%; height: 500px;"></canvas>
                    </div>
                </div>
                <div class="col-md-6"> <!-- Mengatur lebar kontainer -->
                    <div>
                        <canvas id="barChart" style="max-width: 100%; height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Mendapatkan konteks dari canvas
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const barCtx = document.getElementById('barChart').getContext('2d');

        // Slicing data menggunakan metode slice() dari Laravel Collection
        const labels = @json($months->slice(0, 12)->values()->toArray());
        const pemasukanData = @json($pemasukanData->slice(0, 12)->values()->toArray());
        const pengeluaranData = @json($pengeluaranData->slice(0, 12)->values()->toArray());

        const notification = document.querySelector('.notification');
        const dropdown = notification.querySelector('.dropdown-menu');

        // Konfigurasi data untuk Chart.js
        const lineDataConfig = {
            labels: labels,
            datasets: [{
                    label: 'Pemasukan',
                    data: pemasukanData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2
                }
            ]
        };

        const barDataConfig = {
            labels: labels,
            datasets: [{
                    label: 'Pemasukan',
                    data: pemasukanData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaranData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        };

        // Konfigurasi chart garis
        const lineChartConfig = {
            type: 'line',
            data: lineDataConfig,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                elements: {
                    line: {
                        tension: 0.1
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            }
        };

        // Konfigurasi chart bar
        const barChartConfig = {
            type: 'bar',
            data: barDataConfig,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                barThickness: 30,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            }
        };

        // Inisialisasi chart
        new Chart(lineCtx, lineChartConfig);
        new Chart(barCtx, barChartConfig);

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
