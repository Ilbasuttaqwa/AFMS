<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
       <a href="{{ route('home') }}" class="app-brand-link">
    <span class="app-brand-text demo menu-text fw-bolder ms-2"
          style="color: #3d40fb; font-size: 1.2rem; text-transform: uppercase;">
        AFMS manager
    </span>
</a>


        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- 1. Dashboard -->
        <li class="menu-item {{ url()->current() == route('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- 2. Data Absensi -->
        <li class="menu-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <a href="{{ route('absensi.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-time-five"></i>
                <div data-i18n="Data Absensi">Data Absensi</div>
            </a>
        </li>

        <!-- 3. Karyawan -->
        <li class="menu-item {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
            <a href="{{ route('pegawai.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Karyawan">Karyawan</div>
            </a>
        </li>

        <!-- 4. Golongan -->
        <li class="menu-item {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
            <a href="{{ route('jabatan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-id-card"></i>
                <div data-i18n="Golongan">Golongan</div>
            </a>
        </li>

        <!-- 5. Lokasi -->
        <li class="menu-item {{ request()->routeIs('cabang.*') ? 'active' : '' }}">
            <a href="{{ route('cabang.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div data-i18n="Lokasi">Lokasi</div>
            </a>
        </li>

        <!-- 6. Keuangan (Dropdown) -->
        <li class="menu-item {{ request()->routeIs('keuangan.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div data-i18n="Keuangan">Keuangan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('keuangan.bonus-gaji') ? 'active' : '' }}">
                    <a href="{{ route('keuangan.bonus-gaji') }}" class="menu-link">
                        <div data-i18n="Bonus Gaji">Bonus Gaji Manual</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('keuangan.potongan-gaji') ? 'active' : '' }}">
                    <a href="{{ route('keuangan.potongan-gaji') }}" class="menu-link">
                        <div data-i18n="Potongan Gaji">Potongan Gaji Manual</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('keuangan.bon.*') ? 'active' : '' }}">
                    <a href="{{ route('keuangan.bon.index') }}" class="menu-link">
                        <div data-i18n="Bon">Bon manual</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('keuangan.laporan-potongan-gaji') ? 'active' : '' }}">
                    <a href="{{ route('keuangan.laporan-potongan-gaji') }}" class="menu-link">
                        <div data-i18n="Laporan Gaji">Semua Laporan Gaji</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 7. Konfigurasi (Dropdown) -->
        <li class="menu-item {{ request()->routeIs('golongan.pindah-golongan') || request()->routeIs('keuangan.gaji-pokok') || request()->routeIs('fingerprint.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Konfigurasi">Konfigurasi</div>
            </a>
            <ul class="menu-sub">



                <li class="menu-item {{ request()->routeIs('fingerprint.index') ? 'active' : '' }}">
                    <a href="{{ route('fingerprint.index') }}" class="menu-link">
                        <div data-i18n="Management Device">Management Device</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- 8. Management Akun -->
        <li class="menu-item {{ request()->routeIs('manajemen-akun.*') ? 'active' : '' }}">
            <a href="{{ route('manajemen-akun.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="Management Akun">Management Akun</div>
            </a>
        </li>

        <!-- 9. Laporan -->
        <li class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.absensi') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file-blank"></i>
                <div data-i18n="Laporan">Laporan</div>
            </a>
        </li>

    </ul>
</aside>
