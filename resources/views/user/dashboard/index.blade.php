@extends('layouts.user.template')

@push('styles')
<style>
    /* Simple Dashboard Styling */
    .container-fluid {
        background: #f8f9fa;
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    
    /* Simple Statistics Cards */
    .stats-card {
        background: white !important;
        border: none !important;
        border-radius: 10px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        margin-bottom: 1.5rem !important;
    }
    
    .stats-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15) !important;
    }
    
    .stats-card .card-body {
        padding: 1.5rem;
    }
    
    /* Icon containers */
    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-shape.bg-gradient-primary {
        background: #007bff;
    }
    
    .icon-shape.bg-gradient-success {
        background: #28a745;
    }
    
    .icon-shape.bg-gradient-info {
        background: #17a2b8;
    }
    
    .icon-shape.bg-gradient-warning {
        background: #ffc107;
    }
    
    .icon-shape i {
        font-size: 1.25rem;
        color: white;
    }
    
    /* Number styling */
    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0;
    }
    
    .stats-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .stats-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .stats-description .text-success {
        color: #28a745 !important;
    }
    
    .stats-description .text-danger {
        color: #dc3545 !important;
    }
    
    .stats-description .text-warning {
        color: #ffc107 !important;
    }
    
    /* Welcome Card */
    .welcome-card {
        background: white !important;
        border: none !important;
        border-radius: 10px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    }
    
    .welcome-card .card-body {
        padding: 2rem;
    }
    
    /* Specific styling for main welcome text */
    .welcome-card h4 {
        font-weight: 700 !important;
        font-size: 1.5rem !important;
        color: #495057 !important;
        margin-bottom: 0.5rem !important;
    }
    
    .welcome-card h5 {
        font-weight: 600 !important;
        color: #6c757d !important;
        margin-bottom: 1rem !important;
    }
    
    /* Calendar Card */
    .calendar-card {
        background: white !important;
        border: none !important;
        border-radius: 10px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    }
    
    .calendar-card .card-header {
        background: transparent;
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem 1.5rem 1rem;
    }
    
    .calendar-card .card-body {
        padding: 1.5rem;
    }
    
    /* Info Items */
    .info-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }
    
    .info-item .icon-wrapper {
        width: 40px;
        height: 40px;
        background: #007bff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .info-item .icon-wrapper i {
        color: white;
        font-size: 1rem;
    }
    
    .info-text-label {
        color: #6c757d !important;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .info-text-value {
        color: #495057 !important;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0;
    }
    
    /* Avatar styling */
    .avatar-xl {
        width: 64px;
        height: 64px;
        background: #007bff;
        border: 2px solid #0056b3;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-xl i {
        font-size: 1.8rem;
        color: white;
    }
    
    /* Button styling */
    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        background: transparent;
    }
    
    .btn-outline-primary:hover {
        background: #007bff;
        border-color: #007bff;
        color: white;
    }
    
    /* Card title */
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    

    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Employee Statistics Cards -->
        <div class="row">
            <!-- Karyawan Terlambat -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="icon-shape bg-gradient-warning">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="stats-number mb-0">{{ $karyawanTerlambat ?? 0 }}</h3>
                                <p class="stats-label mb-0">Karyawan Terlambat</p>
                                <small class="stats-description">
                                    <span class="text-warning">
                                        <i class="fas fa-clock me-1"></i>
                                    </span>
                                    Hari ini
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Karyawan Sakit -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="icon-shape bg-gradient-info">
                                    <i class="fas fa-user-injured text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="stats-number mb-0">{{ $karyawanSakit ?? 0 }}</h3>
                                <p class="stats-label mb-0">Karyawan Sakit</p>
                                <small class="stats-description">
                                    <span class="text-danger">
                                        <i class="fas fa-user-injured me-1"></i>
                                    </span>
                                    Hari ini
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="icon-shape bg-gradient-success">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="stats-number mb-0" style="font-size: 1.2rem;">{{ Auth::user()->cabang->nama_cabang ?? 'Belum ditentukan' }}</h3>
                                <p class="stats-label mb-0">Lokasi Anda</p>
                                <small class="stats-description">
                                    <span class="text-success">
                                        <i class="fas fa-building me-1"></i>
                                    </span>
                                    Tempat Kerja
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome and Calendar Section -->
        <div class="row mt-4">
            <!-- Welcome Card -->
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card welcome-card h-100">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-xl mx-auto mb-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="mb-2">Selamat Datang!</h4>
                            <h5 class="opacity-8">{{ Auth::user()->nama_pegawai }}</h5>
                        </div>
                        
                        <div class="info-list">
                            <div class="info-item d-flex align-items-center mb-3">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small class="info-text-label">Lokasi</small>
                                    <p class="info-text-value mb-0">{{ Auth::user()->cabang->nama_cabang ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                            
                            <div class="info-item d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <small class="info-text-label">Tanggal Masuk</small>
                                    <p class="info-text-value mb-0">{{ Auth::user()->tanggal_masuk ?? 'Belum diatur' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="col-lg-8">
                <div class="card calendar-card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title mb-1">Kalender Absensi</h5>
                                <p class="text-muted mb-0">Lihat jadwal dan riwayat kehadiran Anda</p>
                            </div>
                            <div class="btn-group" role="group">
                                <button id="prevMonth" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-chevron-left me-1"></i>Prev
                                </button>
                                <button id="nextMonth" class="btn btn-outline-primary btn-sm">
                                    Next<i class="fas fa-chevron-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar" class="table-responsive">
                            <!-- Kalender akan dirender di sini oleh JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Kalender sederhana
    function generateCalendar() {
        const calendar = document.getElementById('calendar');
        const now = new Date();
        const currentMonth = now.getMonth();
        const currentYear = now.getFullYear();
        
        // Header bulan
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        let calendarHTML = `
            <div class="calendar-header text-center mb-3">
                <h5 class="mb-0">${monthNames[currentMonth]} ${currentYear}</h5>
            </div>
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th class="text-center text-muted">Min</th>
                        <th class="text-center text-muted">Sen</th>
                        <th class="text-center text-muted">Sel</th>
                        <th class="text-center text-muted">Rab</th>
                        <th class="text-center text-muted">Kam</th>
                        <th class="text-center text-muted">Jum</th>
                        <th class="text-center text-muted">Sab</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        // Mendapatkan hari pertama bulan dan jumlah hari
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        let date = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    calendarHTML += '<td></td>';
                } else if (date > daysInMonth) {
                    break;
                } else {
                    const isToday = date === now.getDate() && currentMonth === now.getMonth() && currentYear === now.getFullYear();
                    const cellClass = isToday ? 'bg-primary text-white' : '';
                    calendarHTML += `<td class="text-center p-2"><span class="d-inline-block rounded-circle p-2 ${cellClass}" style="width: 35px; height: 35px; line-height: 19px;">${date}</span></td>`;
                    date++;
                }
            }
            calendarHTML += '</tr>';
            if (date > daysInMonth) break;
        }
        
        calendarHTML += '</tbody></table>';
        calendar.innerHTML = calendarHTML;
    }
    
    // Generate kalender saat halaman dimuat
    document.addEventListener('DOMContentLoaded', generateCalendar);
</script>
@endpush
