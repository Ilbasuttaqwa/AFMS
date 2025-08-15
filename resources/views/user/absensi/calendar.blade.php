@extends('layouts.user.template')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .attendance-calendar {
            font-size: 0.85rem;
        }
        .attendance-calendar th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #dee2e6;
        }
        .attendance-calendar td {
            text-align: center;
            padding: 4px 2px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
            min-height: 60px;
        }
        .employee-name {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: left !important;
            padding-left: 8px !important;
            min-width: 150px;
        }
        .weekend {
            background-color: #ffe6e6;
        }
        .present {
            background-color: #d4edda;
            color: #155724;
        }
        .late {
            background-color: #f8d7da;
            color: #721c24;
        }
        .absent {
            background-color: #f1f3f4;
            color: #6c757d;
        }
        .legend-item {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            margin-right: 5px;
            border: 1px solid #dee2e6;
        }
        .schedule-info {
            font-size: 0.9rem;
        }
        .time-display {
            font-size: 0.75rem;
            line-height: 1.2;
        }
        .date-header {
            font-weight: bold;
            font-size: 0.8rem;
        }
        .day-name {
            font-size: 0.7rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold py-3 mb-4">
                    <i class="bi bi-calendar3 me-2"></i>Kalender Absensi
                </h4>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-funnel me-2"></i>Filter Data Absensi
                        </h5>
                        <form method="GET" action="{{ route('welcome.calendar') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-month me-1"></i>Bulan
                                    </label>
                                    <select class="form-select" name="month" id="monthFilter">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ (request('month', date('n')) == $i) ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-year me-1"></i>Tahun
                                    </label>
                                    <select class="form-select" name="year" id="yearFilter">
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ (request('year', date('Y')) == $i) ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" id="loadDataBtn">
                                        <i class="bi bi-search me-1"></i>Tampilkan Data
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend and Work Schedule -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Keterangan Status
                        </h6>
                        <div class="d-flex flex-wrap">
                            <div class="legend-item">
                                <div class="legend-color weekend"></div>
                                <span>Weekend/Libur</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #fff3cd;"></div>
                                <span>Izin/Sakit</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color absent"></div>
                                <span>Tidak Hadir</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color late"></div>
                                <span>Terlambat</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color present"></div>
                                <span>Tepat Waktu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock me-2"></i>Jadwal Kerja
                        </h6>
                        <div class="schedule-info">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-primary">Pagi:</span>
                                <span class="badge bg-light text-dark">07:30 - 12:00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-primary">Sore:</span>
                                <span class="badge bg-light text-dark">13:00 - 17:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-calendar3 me-2"></i>Kalender Absensi
                    <span class="text-muted" id="calendar-title"></span>
                </h5>
                <div class="table-responsive">
                    <table class="table attendance-calendar" id="attendanceTable">
                        <thead>
                            <tr>
                                <th style="min-width: 150px;">Nama Karyawan</th>
                                <!-- Days will be populated by JavaScript -->
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            <tr>
                                <td colspan="32" class="text-center">
                                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar3 fs-1 d-block mb-2"></i>
                                            Pilih bulan dan tahun, lalu klik "Tampilkan Data" untuk melihat kalender absensi
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            // Load data when button is clicked
            $('#loadDataBtn').on('click', function() {
                loadAttendanceData();
            });
            
            // Auto-load current month data
            loadAttendanceData();
        });

        function loadAttendanceData() {
            const month = $('#monthFilter').val() || {{ date('n') }};
            const year = $('#yearFilter').val() || {{ date('Y') }};
            
            console.log('Loading attendance data:', { month, year });
            
            // Update calendar title
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $('#calendar-title').text(`- ${monthNames[month - 1]} ${year}`);
            
            // Show loading message
            $('#attendanceTableBody').html('<tr><td colspan="32" class="text-center"><i class="fas fa-spinner fa-spin me-2"></i>Memuat data...</td></tr>');
            
            // Get attendance data via AJAX
            $.ajax({
                url: '{{ route("welcome.calendar.data") }}',
                method: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function(response) {
                    console.log('AJAX Success - Response received:', response);
                    renderAttendanceTable(response, month, year);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#attendanceTableBody').html(`<tr><td colspan="32" class="text-center text-danger">Error loading data: ${xhr.status} - ${xhr.statusText}</td></tr>`);
                }
            });
        }

        function renderAttendanceTable(employees, month, year) {
            console.log('Rendering attendance table:', { employees, month, year });
            
            // Calculate days in month
            const daysInMonth = new Date(year, month, 0).getDate();
            console.log('Days in month:', daysInMonth);
            
            // Generate header with days
            let headerHtml = '<tr><th class="employee-name">Nama Karyawan</th>';
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month - 1, day);
                const dayName = date.toLocaleDateString('id-ID', { weekday: 'short' });
                
                // Check if this day has holiday data from any employee
                let isHoliday = false;
                let isWeekend = false;
                if (employees.length > 0 && employees[0].attendance && employees[0].attendance[day]) {
                    isHoliday = employees[0].attendance[day].is_holiday;
                    isWeekend = employees[0].attendance[day].is_weekend;
                }
                
                const isHolidayOrWeekend = isWeekend || isHoliday;
                headerHtml += `<th class="${isHolidayOrWeekend ? 'weekend' : ''}" style="min-width: 80px;">
                    <div class="date-header">${day}</div>
                    <small class="day-name">${dayName}</small>
                </th>`;
            }
            headerHtml += '</tr>';
            
            $('#attendanceTable thead').html(headerHtml);
            
            // Generate body with employee data
            let bodyHtml = '';
            
            if (employees.length === 0) {
                bodyHtml = `<tr><td colspan="${daysInMonth + 1}" class="text-center text-muted">Tidak ada data karyawan ditemukan</td></tr>`;
            } else {
                employees.forEach(function(employee) {
                    bodyHtml += '<tr>';
                    bodyHtml += `<td class="employee-name">
                        <div class="fw-semibold">${employee.nama}</div>
                        <small class="text-muted">${employee.golongan} - ${employee.cabang}</small>
                    </td>`;
                    
                    for (let day = 1; day <= daysInMonth; day++) {
                        const attendance = employee.attendance[day];
                        
                        let cellClass = '';
                        let cellContent = '';
                        
                        // Check if it's weekend or holiday from server data
                        if (attendance && (attendance.is_weekend || attendance.is_holiday)) {
                            cellClass = 'weekend';
                            cellContent = '<small>Libur</small>';
                        } else if (attendance && attendance.jam_masuk) {
                            if (attendance.status === 'izin' || attendance.status === 'sakit') {
                                cellClass = 'bg-warning bg-opacity-25';
                                cellContent = '<small>Izin</small>';
                            } else if (isLateTime(attendance.jam_masuk, '07:30:00')) {
                                cellClass = 'late';
                                cellContent = `<div class="time-display">
                                    <div class="fw-bold">${day}</div>
                                    <div>${formatTime(attendance.jam_masuk)}</div>`;
                                if (attendance.jam_masuk_sore) {
                                    cellContent += `<div>${formatTime(attendance.jam_masuk_sore)}</div>`;
                                }
                                cellContent += `<div class="small text-danger">Terlambat</div></div>`;
                            } else {
                                cellClass = 'present';
                                cellContent = `<div class="time-display">
                                    <div class="fw-bold">${day}</div>
                                    <div>${formatTime(attendance.jam_masuk)}</div>`;
                                if (attendance.jam_masuk_sore) {
                                    cellContent += `<div>${formatTime(attendance.jam_masuk_sore)}</div>`;
                                }
                                cellContent += `<div class="small text-success">Tepat Waktu</div></div>`;
                            }
                        } else {
                            cellClass = 'absent';
                            cellContent = `<div class="time-display">
                                <div class="fw-bold">${day}</div>
                                <small>-</small>
                            </div>`;
                        }
                        
                        bodyHtml += `<td class="${cellClass}">${cellContent}</td>`;
                    }
                    
                    bodyHtml += '</tr>';
                });
            }
            
            $('#attendanceTableBody').html(bodyHtml);
        }

        function formatTime(timeString) {
            if (!timeString) return '-';
            return timeString.substring(0, 5); // Get HH:MM format
        }

        function isLateTime(actualTime, standardTime) {
            const actual = new Date('2000-01-01 ' + actualTime);
            const standard = new Date('2000-01-01 ' + standardTime);
            return actual > standard;
        }
    </script>
@endpush