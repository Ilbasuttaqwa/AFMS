<?php $__env->startSection('css'); ?>
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
        .sync-status-container {
            max-height: 120px;
            overflow-y: auto;
        }
        .sync-status-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 4px 8px;
            margin-bottom: 6px;
            border-radius: 4px;
            background-color: #f8f9fa;
            border-left: 3px solid #dee2e6;
            font-size: 0.8rem;
        }
        .sync-status-item.success {
            background-color: #d4edda;
            border-left-color: #28a745;
        }
        .sync-status-item.warning {
            background-color: #fff3cd;
            border-left-color: #ffc107;
        }
        .sync-branch {
            font-weight: 600;
            color: #495057;
        }
        .sync-time {
            color: #6c757d;
            font-size: 0.75rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">


        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-funnel me-2"></i>Filter Data Absensi
                        </h5>
                        <form method="GET" action="<?php echo e(route('absensi.index')); ?>">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-month me-1"></i>Bulan
                                    </label>
                                    <select class="form-select" name="month">
                                        <?php for($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e((request('month', date('n')) == $i) ? 'selected' : ''); ?>>
                                                <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-year me-1"></i>Tahun
                                    </label>
                                    <select class="form-select" name="year">
                                        <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e((request('year', date('Y')) == $i) ? 'selected' : ''); ?>>
                                                <?php echo e($i); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Lokasi
                                </label>
                                <select class="form-select" name="cabang">
                                    <option value="">Semua Lokasi</option>
                                    <?php $__currentLoopData = \App\Models\Cabang::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cabang->id); ?>" <?php echo e(request('cabang') == $cabang->id ? 'selected' : ''); ?>>
                                            <?php echo e($cabang->nama_cabang); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Tampilkan
                                        </button>
                                        <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                        <button type="button" class="btn btn-success" id="syncFingerprintBtn" title="Sinkronisasi Data Fingerprint">
                                            <i class="fas fa-sync-alt"></i> Sync
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend and Work Schedule -->
        <div class="row mb-4">
            <div class="col-md-6">
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
                            <div class="legend-item">
                                <i class="fas fa-fingerprint text-primary"></i>
                                <span>Fingerprint</span>
                            </div>
                            <div class="legend-item">
                                <i class="fas fa-edit text-secondary"></i>
                                <span>Manual</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-fingerprint me-2"></i>Status Sync
                        </h6>
                        <div id="syncStatus" class="sync-status-container">
                            <div class="text-muted small">
                                <i class="fas fa-spinner fa-spin me-1"></i>Memuat status...
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
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function() {
            loadAttendanceData();
            loadSyncStatus();
            
            // Reload data when filter changes
            $('select[name="month"], select[name="year"], select[name="cabang"]').on('change', function() {
                loadAttendanceData();
            });
            
            // Sync fingerprint button
            $('#syncFingerprintBtn').on('click', function() {
                syncFingerprint();
            });
            
            // Auto refresh sync status every 30 seconds
            setInterval(loadSyncStatus, 30000);
        });

        function loadAttendanceData() {
            const month = $('select[name="month"]').val() || <?php echo e(date('n')); ?>;
            const year = $('select[name="year"]').val() || <?php echo e(date('Y')); ?>;
            const lokasi = $('select[name="cabang"]').val() || '';
            
            console.log('Loading attendance data:', { month, year, lokasi });
            console.log('AJAX URL:', '<?php echo e(route("absensi.data")); ?>');
            
            // Update calendar title
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $('#calendar-title').text(`- ${monthNames[month - 1]} ${year}`);
            
            // Show loading message
            $('#attendanceTableBody').html('<tr><td colspan="32" class="text-center">Loading data...</td></tr>');
            
            // Get attendance data via AJAX
            $.ajax({
                url: '<?php echo e(route("absensi.data")); ?>',
                method: 'GET',
                data: {
                    month: month,
                    year: year,
                    cabang: lokasi
                },
                beforeSend: function(xhr) {
                    console.log('AJAX request starting...');
                    console.log('Request headers:', xhr.getAllResponseHeaders());
                },
                success: function(response) {
                    console.log('AJAX Success - Response received:', response);
                    console.log('Response type:', typeof response);
                    console.log('Number of employees:', response ? response.length : 'undefined');
                    if (response && response.length > 0) {
                        console.log('First employee:', response[0]);
                        console.log('First employee attendance for day 8:', response[0].attendance ? response[0].attendance[8] : 'no attendance data');
                    }
                    renderAttendanceTable(response, month, year);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:');
                    console.error('- Status:', status);
                    console.error('- Error:', error);
                    console.error('- XHR Status:', xhr.status);
                    console.error('- XHR Status Text:', xhr.statusText);
                    console.error('- Response Text:', xhr.responseText);
                    console.error('- Response Headers:', xhr.getAllResponseHeaders());
                    
                    let errorMessage = 'Error loading data';
                    if (xhr.status === 401) {
                        errorMessage = 'Authentication required';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Access denied';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Endpoint not found';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error';
                    }
                    
                    $('#attendanceTableBody').html(`<tr><td colspan="32" class="text-center text-danger">${errorMessage}: ${xhr.status} - ${xhr.statusText}</td></tr>`);
                },
                complete: function() {
                    console.log('AJAX request completed');
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
                headerHtml += `<th class="${isHolidayOrWeekend ? 'weekend' : ''}" style="min-width: 60px;">
                    <div>${day}</div>
                    <small>${dayName}</small>
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
                                cellContent = `<div class="small">${formatTime(attendance.jam_masuk)}</div>`;
                                if (attendance.jam_masuk_sore) {
                                    cellContent += `<div class="small">${formatTime(attendance.jam_masuk_sore)}</div>`;
                                }
                                // Add source indicator
                                if (attendance.source) {
                                    const sourceIcon = attendance.source === 'Fingerprint' ? 'fa-fingerprint text-primary' : 'fa-edit text-secondary';
                                    cellContent += `<div class="small"><i class="fas ${sourceIcon}" title="${attendance.source}"></i></div>`;
                                }
                            } else {
                                cellClass = 'present';
                                cellContent = `<div class="small">${formatTime(attendance.jam_masuk)}</div>`;
                                if (attendance.jam_masuk_sore) {
                                    cellContent += `<div class="small">${formatTime(attendance.jam_masuk_sore)}</div>`;
                                }
                                // Add source indicator
                                if (attendance.source) {
                                    const sourceIcon = attendance.source === 'Fingerprint' ? 'fa-fingerprint text-primary' : 'fa-edit text-secondary';
                                    cellContent += `<div class="small"><i class="fas ${sourceIcon}" title="${attendance.source}"></i></div>`;
                                }
                            }
                        } else {
                            cellClass = 'absent';
                            cellContent = '<small>-</small>';
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

        function syncFingerprint() {
            const cabangId = $('select[name="cabang"]').val();
            const $btn = $('#syncFingerprintBtn');
            const originalHtml = $btn.html();
            
            // Disable button and show loading
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Syncing...');
            
            $.ajax({
                url: '<?php echo e(route("absensi.sync")); ?>',
                method: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    cabang_id: cabangId
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showToast('success', response.message);
                        
                        // Reload attendance data and sync status
                        loadAttendanceData();
                        loadSyncStatus();
                    } else {
                        showToast('error', response.message || 'Gagal melakukan sinkronisasi');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast('error', response?.message || 'Terjadi kesalahan saat sinkronisasi');
                },
                complete: function() {
                    // Re-enable button
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        }

        function loadSyncStatus() {
            $.ajax({
                url: '<?php echo e(route("absensi.sync.status")); ?>',
                method: 'GET',
                success: function(response) {
                    let statusHtml = '';
                    
                    if (response.length === 0) {
                        statusHtml = '<div class="text-muted small">Tidak ada perangkat fingerprint aktif</div>';
                    } else {
                        response.forEach(function(cabang) {
                            const isActive = cabang.fingerprint_active;
                            const hasRecentSync = cabang.last_sync && cabang.last_sync !== 'Belum pernah sync';
                            const itemClass = hasRecentSync ? 'success' : 'warning';
                            
                            statusHtml += `
                                <div class="sync-status-item ${itemClass}">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="sync-branch">${cabang.nama_cabang}</span>
                                            <i class="fas ${isActive ? 'fa-wifi text-success' : 'fa-wifi-slash text-danger'}" title="${isActive ? 'Aktif' : 'Tidak Aktif'}"></i>
                                        </div>
                                        <div class="sync-time">${cabang.last_sync}</div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    $('#syncStatus').html(statusHtml);
                },
                error: function() {
                    $('#syncStatus').html('<div class="text-danger small">Error loading sync status</div>');
                }
            });
        }

        function showToast(type, message) {
            // Create toast element
            const toastId = 'toast-' + Date.now();
            const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            // Add toast container if not exists
            if (!$('#toast-container').length) {
                $('body').append('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>');
            }
            
            // Add toast to container
            $('#toast-container').append(toastHtml);
            
            // Show toast
            const toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();
            
            // Remove toast element after it's hidden
            $(`#${toastId}`).on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/absensi/index.blade.php ENDPATH**/ ?>