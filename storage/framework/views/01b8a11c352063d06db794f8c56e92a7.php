<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .sync-status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        .sync-info {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 0.75rem;
            border-left: 4px solid #0d6efd;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#laporanTable').DataTable({
                "language": {
                    "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "emptyTable": "Tidak ada data tersedia",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
                },
                "pageLength": 25,
                "order": [[ 1, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ]
            });

            // Load sync status on page load
            loadSyncStatus();

            // Manual sync button
            $('#manualSyncBtn').click(function() {
                manualSync();
            });
        });

        function exportToPDF() {
            const bulan = document.getElementById('bulan').value;
            const tahun = document.getElementById('tahun').value;
            const search = document.getElementById('search').value;

            let url = '<?php echo e(route("keuangan.laporan-potongan-gaji")); ?>?export=pdf&bulan=' + bulan + '&tahun=' + tahun;
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            window.open(url, '_blank');
        }



        function getMonthName(month) {
            const months = {
                '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
                '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
                '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
            };
            return months[month] || month;
        }

        function loadSyncStatus() {
            $.ajax({
            url: '<?php echo e(route("absensi.sync.status")); ?>',
            method: 'GET',
                success: function(response) {
                    let statusHtml = '';
                    let totalSynced = 0;

                    if (response.branches && response.branches.length > 0) {
                        response.branches.forEach(function(branch) {
                            const isSynced = branch.last_sync && branch.last_sync !== 'Belum pernah sync';
                            const badgeClass = isSynced ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
                            const iconClass = isSynced ? 'bi-check-circle' : 'bi-exclamation-triangle';

                            if (isSynced) totalSynced++;

                            statusHtml += `
                                <span class="badge ${badgeClass} me-2 mb-1">
                                    <i class="bi ${iconClass} me-1"></i>
                                    ${branch.name}: ${branch.last_sync}
                                </span>
                            `;
                        });

                        statusHtml += `
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    ${totalSynced} dari ${response.branches.length} cabang telah sync
                                </small>
                            </div>
                        `;
                    } else {
                        statusHtml = `
                            <span class="badge bg-warning-subtle text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>Tidak ada data cabang
                            </span>
                        `;
                    }

                    $('#syncStatus').html(statusHtml);
                },
                error: function() {
                    $('#syncStatus').html(`
                        <span class="badge bg-danger-subtle text-danger">
                            <i class="bi bi-x-circle me-1"></i>Gagal memuat status sync
                        </span>
                    `);
                }
            });
        }

        function manualSync() {
            const btn = $('#manualSyncBtn');
            const originalText = btn.html();

            // Disable button and show loading
            btn.prop('disabled', true).html('<i class="bi bi-arrow-clockwise spin me-1"></i>Syncing...');

            $.ajax({
            url: '<?php echo e(route("absensi.sync")); ?>',
            method: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    // Show success message
                    if (response.success) {
                        // Reload sync status
                        loadSyncStatus();

                        // Show success notification
                        const alert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                Sync berhasil! ${response.total_synced || 0} data absensi telah disinkronkan.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        $('.container-xxl').prepend(alert);

                        // Auto refresh page after 2 seconds to show updated data
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat sync';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    const alert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle me-2"></i>
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('.container-xxl').prepend(alert);
                },
                complete: function() {
                    // Re-enable button
                    btn.prop('disabled', false).html(originalText);
                }
            });
        }


    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Laporan Gaji</h4>

        <!-- Sync Status Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-wifi text-success me-2"></i>Status Sync Fingerprint
                        </h6>
                        <div id="syncStatus">
                            <span class="badge bg-info-subtle text-info">
                                <i class="bi bi-clock me-1"></i>Memuat status sync...
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-outline-primary" id="manualSyncBtn">
                            <i class="bi bi-arrow-clockwise me-1"></i>Sync Manual
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Filter Laporan</h5>
                <form action="<?php echo e(route('keuangan.laporan-potongan-gaji')); ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Cari nama karyawan..." value="<?php echo e($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan">
                            <option value="01" <?php echo e($bulan == '01' ? 'selected' : ''); ?>>Januari</option>
                            <option value="02" <?php echo e($bulan == '02' ? 'selected' : ''); ?>>Februari</option>
                            <option value="03" <?php echo e($bulan == '03' ? 'selected' : ''); ?>>Maret</option>
                            <option value="04" <?php echo e($bulan == '04' ? 'selected' : ''); ?>>April</option>
                            <option value="05" <?php echo e($bulan == '05' ? 'selected' : ''); ?>>Mei</option>
                            <option value="06" <?php echo e($bulan == '06' ? 'selected' : ''); ?>>Juni</option>
                            <option value="07" <?php echo e($bulan == '07' ? 'selected' : ''); ?>>Juli</option>
                            <option value="08" <?php echo e($bulan == '08' ? 'selected' : ''); ?>>Agustus</option>
                            <option value="09" <?php echo e($bulan == '09' ? 'selected' : ''); ?>>September</option>
                            <option value="10" <?php echo e($bulan == '10' ? 'selected' : ''); ?>>Oktober</option>
                            <option value="11" <?php echo e($bulan == '11' ? 'selected' : ''); ?>>November</option>
                            <option value="12" <?php echo e($bulan == '12' ? 'selected' : ''); ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select class="form-select" id="tahun" name="tahun">
                            <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($tahun == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i> Filter</button>
                        <a href="<?php echo e(route('keuangan.laporan-potongan-gaji')); ?>" class="btn btn-outline-secondary me-2"><i class="bi bi-x-circle me-1"></i> Reset</a>
                        <button type="button" class="btn btn-success" onclick="exportToPDF()"><i class="bi bi-file-pdf me-1"></i> Export PDF</button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold py-3">Laporan Gaji Karyawan</h5>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="laporanTable">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">No</th>
                                <th class="fw-bold">Nama Karyawan</th>
                                <th class="fw-bold">Golongan</th>
                                <th class="fw-bold">Lokasi</th>
                                <th class="fw-bold">Gaji Pokok</th>
                                <th class="fw-bold">Potongan Keterlambatan</th>
                                <th class="fw-bold">Potongan Manual</th>
                                <th class="fw-bold">Bon</th>
                                <th class="fw-bold">Total Potongan</th>
                                <th class="fw-bold">Bonus Gaji</th>
                                <th class="fw-bold">Gaji Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php if(count($laporanData) > 0): ?>
                                <?php $__currentLoopData = $laporanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium"><?php echo e($data['user']->nama_pegawai); ?></span>
                                            <small class="text-muted"><?php echo e($data['user']->email); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo e($data['user']->jabatan->nama_jabatan ?? 'Tidak Ada Data'); ?></td>
                                    <td><?php echo e($data['user']->cabang->nama_cabang ?? 'Tidak Ada Data'); ?></td>
                                    <td>Rp <?php echo e(number_format($data['user']->jabatan->gaji_pokok ?? 0, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php if($data['potongan_keterlambatan'] > 0): ?>
                                            <span class="badge bg-warning-subtle text-warning fw-medium px-3 py-2">
                                                Rp <?php echo e(number_format($data['potongan_keterlambatan'], 0, ',', '.')); ?>

                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo e($data['total_menit_terlambat'] ?? 0); ?> menit, <?php echo e($data['jumlah_hari_terlambat'] ?? 0); ?> hari
                                            </small>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success fw-medium px-3 py-2">
                                                Rp 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($data['potongan_manual'] > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger fw-medium px-3 py-2">
                                                Rp <?php echo e(number_format($data['potongan_manual'], 0, ',', '.')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success fw-medium px-3 py-2">
                                                Rp 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($data['total_bon'] > 0): ?>
                                            <span class="badge bg-warning-subtle text-warning fw-medium px-3 py-2">
                                                Rp <?php echo e(number_format($data['total_bon'], 0, ',', '.')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success fw-medium px-3 py-2">
                                                Rp 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger fw-medium px-3 py-2">
                                            Rp <?php echo e(number_format($data['total_potongan'], 0, ',', '.')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success fw-medium px-3 py-2">
                                            Rp <?php echo e(number_format($data['bonus_gaji'], 0, ',', '.')); ?>

                                        </span>
                                    </td>
                                    <td>Rp <?php echo e(number_format($data['gaji_bersih'], 0, ',', '.')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-inbox fs-3 text-muted"></i>
                                            </div>
                                            <h6 class="text-muted mb-1">Tidak ada data</h6>
                                            <p class="text-muted small mb-0">Tidak ada data gaji untuk periode yang dipilih</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if(count($laporanData) > 0): ?>
                        <tfoot class="table-light border-top">
                            <tr>
                                <th colspan="5" class="text-end fw-bold">TOTAL:</th>
                                <th>
                                    <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-2">
                                        Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'potongan_keterlambatan')), 0, ',', '.')); ?>

                                    </span>
                                </th>
                                <th>
                                    <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2">
                                        Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'potongan_manual')), 0, ',', '.')); ?>

                                    </span>
                                </th>
                                <th>
                                    <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-2">
                                        Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'total_bon')), 0, ',', '.')); ?>

                                    </span>
                                </th>
                                <th>
                                    <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2">
                                        Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'total_potongan')), 0, ',', '.')); ?>

                                    </span>
                                </th>
                                <th>
                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2">
                                        Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'bonus_gaji')), 0, ',', '.')); ?>

                                    </span>
                                </th>
                                <th class="fw-bold">Rp <?php echo e(number_format(array_sum(array_column($laporanData, 'gaji_bersih')), 0, ',', '.')); ?></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>




    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/keuangan/laporan_potongan_gaji.blade.php ENDPATH**/ ?>