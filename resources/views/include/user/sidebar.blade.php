<style>
    aside#sidenav-main {
        height: 100vh;
        /* Menyesuaikan tinggi dengan layar */
        overflow-y: auto;
        /* Menambahkan scroll jika kontennya panjang */
        position: fixed;
        /* Memastikan navbar tetap berada di sisi kiri */
        top: 0;
        /* Mulai dari atas layar */
        left: 0;
        /* Mulai dari sisi kiri layar */
        width: 250px;
        /* Atur lebar sidebar sesuai kebutuhan */
        background-color: #fff;
        /* Warna background */
        z-index: 1030;
        /* Menyesuaikan posisi dengan elemen lainnya */
    }

    .nav-item.active .nav-link {
        background-color: #f5f5f5;
        color: #5e72e4;
        font-weight: bold;
    }

    /* Transition effects for smooth experience */
    .nav-item .nav-link {
        position: relative;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    .nav-link:hover .icon {
        color: #5e72e4;
    }
</style>

<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('user.dashboard') }}">
            <span class="ms-1 font-weight-bold text-primary" style="font-size: 1.2rem;">AFMS</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main" style="min-height: 100%;">
        <ul class="navbar-nav">
            <!-- Dashboard Menu -->
            <li class="nav-item {{ request()->is('user/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('user/dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Data</h6>
            </li>



            <!-- Kalender Absensi Menu -->
            <li class="nav-item {{ request()->is('absensi-kalender') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('welcome.calendar') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Kalender Absensi</span>
                </a>
            </li>

            <!-- Bon Menu -->
            <li class="nav-item {{ request()->is('user/bon*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.bon.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-money-coins text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Bon</span>
                </a>
            </li>

            <!-- Sakit/Izin Menu -->
            <li class="nav-item {{ request()->is('user/sakit-izin*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.sakit-izin.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-ambulance text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sakit/Izin</span>
                </a>
            </li>

            <!-- Data Karyawan Menu -->
            <li class="nav-item {{ request()->is('user/karyawan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.karyawan.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-circle-08 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Karyawan</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account</h6>
            </li>

            <!-- Logout Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
       @csrf
    </form>
</aside>
