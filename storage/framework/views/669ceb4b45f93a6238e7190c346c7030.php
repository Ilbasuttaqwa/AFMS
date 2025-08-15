<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .stats-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-card .card-body {
            padding: 1.5rem;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-icon.primary {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .stats-icon.success {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .stats-icon.warning {
            background-color: #fff3e0;
            color: #f57c00;
        }
        
        .stats-icon.info {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        .employee-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }
        
        .employee-email {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .badge-custom {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .badge-lokasi {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-golongan {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }
        
        .badge-salary {
            background-color: #e8f5e8;
            color: #2e7d32;
            font-weight: 600;
        }
        
        .bg-pink {
            background-color: #e91e63 !important;
            color: white !important;
        }
        
        .avatar {
            position: relative;
            display: inline-block;
        }
        
        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }
        
        .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .bg-label-primary {
            background-color: rgba(105, 108, 255, 0.16) !important;
            color: #696cff !important;
        }
        
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Filter Form Styling */
        .filter-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #e9ecef;
        }
        
        .filter-form .form-control,
        .filter-form .form-select {
            border: 1px solid #ced4da;
            font-size: 0.875rem;
        }
        
        .filter-form .input-group-text {
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.gap-3 {
                flex-direction: column;
                gap: 1rem !important;
            }
            
            .filter-form {
                flex-direction: column;
                gap: 0.5rem !important;
            }
            
            .filter-form .input-group,
            .filter-form .form-select {
                width: 100% !important;
            }
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Data Karyawan
        </h4>

        
        <?php if(session('success')): ?>
            <div class="bs-toast toast toast-placement-ex m-2 bg-success top-0 end-0 fade show toast-custom" role="alert"
                aria-live="assertive" aria-atomic="true" id="toastSuccess">
                <div class="toast-header">
                    <i class="bi bi-check-circle me-2"></i>
                    <div class="me-auto fw-semibold">Success</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"><?php echo e(session('success')); ?></div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0 fade show toast-custom" role="alert"
                aria-live="assertive" aria-atomic="true" id="toastError">
                <div class="toast-header">
                    <i class="bx bx-error me-2"></i>
                    <div class="me-auto fw-semibold">Error</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?>



        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-funnel me-2"></i>Filter Data Karyawan
                        </h5>
                        <form method="GET" action="<?php echo e(route('pegawai.index')); ?>">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-search me-1"></i>Cari Nama Karyawan
                                    </label>
                                    <input type="text" class="form-control" name="search-name" 
                                           placeholder="Masukkan nama karyawan..." 
                                           value="<?php echo e(request('search-name')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-building me-1"></i>Filter Lokasi
                                    </label>
                                    <select class="form-select" name="search-cabang">
                                        <option value="">Semua Lokasi</option>
                                        <?php $__currentLoopData = \App\Models\Cabang::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cab->id); ?>" <?php echo e(request('search-cabang') == $cab->id ? 'selected' : ''); ?>>
                                                <?php echo e($cab->nama_cabang); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-briefcase me-1"></i>Filter Golongan
                                    </label>
                                    <select class="form-select" name="search-golongan">
                                        <option value="">Semua Golongan</option>
                                        <?php $__currentLoopData = \App\Models\Jabatan::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($jab->id); ?>" <?php echo e(request('search-golongan') == $jab->id ? 'selected' : ''); ?>>
                                                <?php echo e($jab->nama_jabatan); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="btn-group w-100" role="group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Filter
                                        </button>
                                        <a href="<?php echo e(route('pegawai.index')); ?>" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-table me-2"></i>Tabel Data Karyawan
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKaryawanModal">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Karyawan
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataKaryawan">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">
                                    <i class="bi bi-person me-1"></i>Nama Karyawan
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    <i class="bi bi-fingerprint me-1"></i>ID Biometric
                                </th>
                                <th class="text-center" style="width: 10%;">
                                    <i class="bi bi-gender-ambiguous me-1"></i>Jenis Kelamin
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    <i class="bi bi-briefcase me-1"></i>Golongan
                                </th>
                                <th style="width: 18%;">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </th>
                                <th style="width: 8%;">
                                    <i class="bi bi-house me-1"></i>Alamat
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    <i class="bi bi-currency-dollar me-1"></i>Gaji
                                </th>
                                <th class="text-center" style="width: 8%;">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php $__currentLoopData = $pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center">
                                    <span class="fw-semibold"><?php echo e($index + 1); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-wrapper me-3">
                                            <div class="avatar avatar-sm">
                                                <div class="avatar-initial bg-label-primary rounded-circle">
                                                    <?php echo e(strtoupper(substr($data->nama_pegawai, 0, 2))); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="employee-name"><?php echo e($data->nama_pegawai); ?></div>
                                            <small class="text-muted">ID: <?php echo e($data->id); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if($data->device_user_id): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-fingerprint me-1"></i><?php echo e($data->device_user_id); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Belum Terdaftar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($data->jenis_kelamin == 'Laki-Laki'): ?>
                                        <span class="badge bg-info">
                                            <i class="bi bi-gender-male me-1"></i>Laki-Laki
                                        </span>
                                    <?php elseif($data->jenis_kelamin == 'Perempuan'): ?>
                                        <span class="badge bg-pink">
                                            <i class="bi bi-gender-female me-1"></i>Perempuan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-question-circle me-1"></i>Tidak Diketahui
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-custom badge-lokasi">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?php echo e($data->cabang ? $data->cabang->nama_cabang : 'Tidak ada lokasi'); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-custom badge-golongan">
                                        <i class="bi bi-briefcase me-1"></i>
                                        <?php echo e($data->jabatan ? $data->jabatan->nama_jabatan : 'Tidak ada golongan'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope me-2 text-muted"></i>
                                        <span class="text-truncate" style="max-width: 150px;" title="<?php echo e($data->email); ?>">
                                            <?php echo e($data->email); ?>

                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-house me-2 text-muted"></i>
                                        <span class="text-truncate" style="max-width: 100px;" title="<?php echo e($data->alamat ?? 'Alamat belum diisi'); ?>">
                                            <?php echo e($data->alamat ?? 'Belum diisi'); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $gajiJabatan = $data->jabatan ? $data->jabatan->gaji_pokok : 0;
                                        $gajiPegawai = $data->gaji ?? 0;
                                        $isGajiEdited = $gajiPegawai != $gajiJabatan;
                                    ?>
                                    
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge badge-custom badge-salary">
                                            <i class="bi bi-currency-dollar me-1"></i>
                                            Rp <?php echo e(number_format($gajiPegawai, 0, ',', '.')); ?>

                                        </span>
                                        <?php if($isGajiEdited && $gajiJabatan > 0): ?>
                                            <small class="text-warning mt-1">
                                                <i class="bi bi-pencil-square me-1"></i>
                                                <span style="font-size: 0.7rem;">Diedit Manual</span>
                                            </small>
                                            <small class="text-muted" style="font-size: 0.65rem;">
                                                Standar: Rp <?php echo e(number_format($gajiJabatan, 0, ',', '.')); ?>

                                            </small>
                                        <?php elseif($gajiJabatan > 0): ?>
                                            <small class="text-success mt-1">
                                                <i class="bi bi-check-circle me-1"></i>
                                                <span style="font-size: 0.7rem;">Sesuai Jabatan</span>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo e(route('pegawai.show', $data->id)); ?>">
                                                <i class="bx bx-show me-1"></i> Lihat Detail
                                            </a>
                                            <a class="dropdown-item" href="<?php echo e(route('pegawai.edit', $data->id)); ?>">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="<?php echo e(route('pegawai.destroy', $data->id)); ?>"
                                                data-confirm-delete="true">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    </div>

<!-- Modal Tambah Karyawan -->
<div class="modal fade" id="tambahKaryawanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Tambah Karyawan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('pegawai.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-plus fs-3 text-primary"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-person me-1"></i>Nama Lengkap
                            </label>
                            <input type="text" name="nama_pegawai" class="form-control" required placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-gender-ambiguous me-1"></i>Jenis Kelamin
                            </label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-building me-1"></i>Lokasi
                            </label>
                            <select name="id_cabang" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Lokasi --</option>
                                <?php $__currentLoopData = \App\Models\Cabang::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cabang->id); ?>"><?php echo e($cabang->nama_cabang); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-briefcase me-1"></i>Golongan
                            </label>
                            <select name="id_jabatan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Golongan --</option>
                                <?php $__currentLoopData = \App\Models\Jabatan::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($jabatan->id); ?>"><?php echo e($jabatan->nama_jabatan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <input type="email" name="email" class="form-control" required placeholder="Masukkan alamat email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-geo-alt me-1"></i>Alamat
                        </label>
                        <textarea name="alamat" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap"></textarea>
                    </div>

                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Info:</strong> Pastikan semua data yang dimasukkan sudah benar sebelum menyimpan.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Simpan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#dataKaryawan').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Auto-submit form when filters change
            $('select[name="search-cabang"], select[name="search-golongan"]').on('change', function() {
                $(this).closest('form').submit();
            });

            // Auto-hide toasts after 5 seconds
            setTimeout(function() {
                $('.toast').fadeOut();
            }, 5000);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/pegawai/index.blade.php ENDPATH**/ ?>