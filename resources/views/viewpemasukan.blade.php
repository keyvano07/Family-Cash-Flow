@extends('components.layout')

@section('title', 'View Pemasukan')

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
                        <h1>Daftar Pemasukan</h1>
                    </div>
                    <!-- Form Pencarian di dalam tabel -->
                    <form class="form-inline mb-3" method="GET" action="{{ route('view-pemasukan') }}">
                        <div class="search input-group d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <div class="d-flex flex-column col-md-5 col-sm-6 gap-1">
                                <label for="start_date" class="mr-2">Tanggal Awal</label>
                                <input type="date" name="start_date" class="form-control" placeholder="StartDate"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="d-flex flex-column col-md-5 col-sm-6 gap-1">
                                <label for="end_date" class="mr-2">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" placeholder="EndDate"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                    <!-- Tabel Pemasukan -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <form class="form-inline mb-3" method="GET"
                                            action="{{ route('view-pemasukan') }}">
                                            <div class="input-group align-items-center mt-2 gap-2">
                                                <div class="d-flex flex-column col-3 ">
                                                    <input type="text" name="search" class="form-control"
                                                        placeholder="Cari Pemasukan" value="{{ request('search') }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Keterangan Pemasukan</th>
                                    <th scope="col">Sumber Pemasukkan</th>
                                    <th scope="col">Jumlah Pemasukkan</th>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara')
                                    <th scope="col">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $pemasukan)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $pemasukan->tanggal }}</td>
                                        <td>{{ $pemasukan->keterangan }}</td>
                                        <td>{{ $pemasukan->sumber }}</td>
                                        <td>RP. {{ number_format($pemasukan->jumlah, 2, ',', '.') }}</td>
                                        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara')
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <a href="{{ url('pemasukan-edit/' . $pemasukan->id) }}">
                                                        <button class="btn btn-primary btn-sm">Edit</button>
                                                    </a>
                                                    <form action="{{ url('pemasukan-delete/' . $pemasukan->id) }}"
                                                        method="POST" onsubmit="return confirmDelete(event)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach

                                <!-- Baris untuk Total Pemasukan -->
                                <tr>
                                    <td colspan="4" class=""><strong>Total Pemasukan</strong></td>
                                    <td colspan="2"><strong>RP.
                                            {{ number_format($totalPemasukan, 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function confirmDelete(event) {
            event.preventDefault();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success mr-2",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });

                    event.target.submit();
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error"
                    });
                }
            });
        }
    </script>
@endsection
