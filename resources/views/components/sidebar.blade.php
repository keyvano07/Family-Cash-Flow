<div class="sidebar">
    <div class="logo-details">
        <i class='bx bxs-bank icon'></i>
        <span class="logo_name">DaKu</span>
        <div class="menu">
            <i class='bx bx-menu' id="btn"></i>
        </div>
    </div>
    {{-- MENU SIDEBAR --}}
    <ul class="nav-list">
        <div class="line">
            {{-- MENU DASHBOARD --}}
            <li>
                <a href="{{ url('dashboard') }}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
        </div>
        <div class="line">
            <span class="text-label">
                INPUT DATA
            </span>
            {{-- MENU INPUT PEMASUKAN --}}
            <li>
                <a href="{{ url('pemasukan') }}">
                    <i class='bx bx-plus-circle'></i>
                    <span class="links_name">Pemasukan</span>
                </a>
                <span class="tooltip">Pemasukan</span>
            </li>
            {{-- MENU INPUT PENGHELUARAN --}}
            <li>
                <a href="{{ url('pengeluaran') }}">
                    <i class='bx bx-minus-circle'></i>
                    <span class="links_name">Pengeluaran</span>
                </a>
                <span class="tooltip">Pengeluaran</span>
            </li>
        </div>
        <div class="line">
            <span class="text-label">
                DATA
            </span>
            {{-- MENU DATA PEMASUKAN --}}
            <li>
                <a href="{{ url('view-pemasukan') }}">
                    <i class='bx bx-left-top-arrow-circle'></i>
                    <span class="links_name">Pemasukan</span>
                </a>
                <span class="tooltip">Pemasukan</span>
            </li>
            {{-- MENU INPUT PENGELUARAN --}}
            <li>
                <a href="{{ url('view-pengeluaran') }}">
                    <i class='bx bx-left-down-arrow-circle'></i>
                    <span class="links_name">Pengeluaran</span>
                </a>
                <span class="tooltip">Pengeluaran</span>

                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara')
            <li>
                {{-- MENU LAPORAN --}}
                <a href="/laporan">
                    <i class='bx bxs-report bx-flip-horizontal'></i>
                    <span class="links_name">Laporan</span>
                </a>
                <span class="tooltip">Laporan</span>
            </li>
            @endif
            </li>

        </div>
        @if (Auth::user()->role == 'admin')
            <span class="text-label pengaturan">
                PENGATURAN
            </span>
            <li class="pengaturan">
                <a href="{{ url('informasikeluarga') }}">
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Pegaturan</span>
                </a>
                <span class="tooltip">Pengaturan</span>
            </li>
            <li class="users">
                <a href="{{ url('users') }}">
                    <i class='bx bxs-user'></i>
                    <span class="links_name">Users</span>
                </a>
                <span class="tooltip">Users</span>
            </li>
        @endif

        <li class="profile">
            <div class="profile-details">
                <img src="https://i.pravatar.cc/300" alt="">
                <div class="name_job">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="job">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form action="{{ url('/account/logout') }}" method="POST">
                @csrf
                <button type="submit"><i class='bx bx-log-out' id="log_out"></i></button>
            </form>
        </li>
    </ul>
</div>

</body>

</html>
