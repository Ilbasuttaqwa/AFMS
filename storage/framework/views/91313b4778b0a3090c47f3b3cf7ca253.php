<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: middle;
        }
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        .avatar-lg {
            width: 48px;
            height: 48px;
        }
        .stats-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .stats-card.manager {
            border-left-color: #dc3545;
        }
        .stats-card.admin {
            border-left-color: #198754;
        }
        .stats-card.total {
            border-left-color: #0d6efd;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"></span> Manajemen Akun Karyawan
        </h4>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card total">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold"><?php echo e($users->count()); ?></h3>
                                <p class="text-muted mb-0">Total Akun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card manager">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-danger rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-check text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold"><?php echo e($users->where('role', 'manager')->count()); ?></h3>
                                <p class="text-muted mb-0">Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card admin">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-success rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-check text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold"><?php echo e($users->where('role', 'admin')->count()); ?></h3>
                                <p class="text-muted mb-0">Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-funnel me-2"></i>Filter & Pencarian
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-shield me-1"></i>Role
                                </label>
                                <select class="form-select" id="roleFilter">
                                    <option value="">Semua Role</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Cabang
                                </label>
                                <select class="form-select" id="cabangFilter">
                                    <option value="">Semua Cabang</option>
                                    <?php $__currentLoopData = $cabangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cabang->nama_cabang); ?>"><?php echo e($cabang->nama_cabang); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-check-circle me-1"></i>Status Verifikasi
                                </label>
                                <select class="form-select" id="verifikasiFilter">
                                    <option value="">Semua Status</option>
                                    <option value="verified">Terverifikasi</option>
                                    <option value="unverified">Belum Verifikasi</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Akun
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Account Modal -->
        <div class="modal fade" id="addAccountModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>Tambah Akun Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('manajemen-akun.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-person me-1"></i>Nama Lengkap
                                        </label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['nama_pegawai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="nama_pegawai" value="<?php echo e(old('nama_pegawai')); ?>"
                                               placeholder="Masukkan nama lengkap" required>
                                        <?php $__errorArgs = ['nama_pegawai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-1"></i>Email
                                        </label>
                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="email" value="<?php echo e(old('email')); ?>"
                                               placeholder="contoh@email.com" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-key me-1"></i>Password
                                        </label>
                                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               name="password" placeholder="Minimal 8 karakter" required>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-key-fill me-1"></i>Konfirmasi Password
                                        </label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                               placeholder="Ulangi password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt me-1"></i>Lokasi/Cabang
                                        </label>
                                        <select class="form-select <?php $__errorArgs = ['id_cabang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="id_cabang" required>
                                            <option value="">Pilih Cabang</option>
                                            <?php $__currentLoopData = $cabangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cabang->id); ?>" <?php echo e(old('id_cabang') == $cabang->id ? 'selected' : ''); ?>>
                                                    <?php echo e($cabang->nama_cabang); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['id_cabang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-shield me-1"></i>Role
                                        </label>
                                        <select class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="role" required>
                                            <option value="">Pilih Role</option>
                                            <option value="admin" <?php echo e(old('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                            <option value="manager" <?php echo e(old('role') === 'manager' ? 'selected' : ''); ?>>Manager</option>
                                        </select>
                                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i>Buat Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Data Akun -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>Data Akun Karyawan
                </h5>
                <span class="badge bg-light text-primary"><?php echo e($users->count()); ?> Akun</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="akunTable" class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">
                                    <i class="bi bi-person me-1"></i>Nama Lengkap
                                </th>
                                <th style="width: 20%;">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </th>
                                <th class="text-center" style="width: 10%;">
                                    <i class="bi bi-key me-1"></i>Password
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi/Cabang
                                </th>
                                <th class="text-center" style="width: 10%;">
                                    <i class="bi bi-shield me-1"></i>Role
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    <i class="bi bi-calendar me-1"></i>Dibuat
                                </th>
                                <th class="text-center" style="width: 8%;">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="align-middle">
                                    <td class="text-center fw-bold"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($user->nama_pegawai); ?></div>
                                                <small class="text-muted">ID: <?php echo e($user->id); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium"><?php echo e($user->email); ?></div>
                                        <small class="text-muted">
                                            <?php if($user->email_verified_at): ?>
                                                <i class="bi bi-check-circle text-success"></i> Terverifikasi
                                            <?php else: ?>
                                                <i class="bi bi-x-circle text-warning"></i> Belum Verifikasi
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#passwordModal<?php echo e($user->id); ?>"
                                                title="Lihat Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <?php if($user->cabang): ?>
                                            <span class="badge bg-info-subtle text-info border border-info">
                                                <i class="bi bi-building me-1"></i><?php echo e($user->cabang->nama_cabang); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Belum Ditentukan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($user->role === 'manager'): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger">
                                                <i class="bi bi-shield-check me-1"></i>Manager
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-person-check me-1"></i>Admin
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-medium"><?php echo e($user->created_at->format('d/m/Y')); ?></div>
                                        <small class="text-muted"><?php echo e($user->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($user->id); ?>"
                                                    title="Edit Akun">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if($user->id !== auth()->id()): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($user->id); ?>"
                                                        title="Hapus Akun">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        title="Tidak dapat menghapus akun sendiri" disabled>
                                                    <i class="bi bi-shield-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Password Modal -->
                                <div class="modal fade" id="passwordModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">
                                                    <i class="bi bi-key me-2"></i>Password: <?php echo e($user->nama_pegawai); ?>

                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Password Terenkripsi</strong>
                                                </div>
                                                <p class="text-muted mb-2">Password disimpan dalam bentuk hash untuk keamanan.</p>
                                                <div class="bg-light p-3 rounded">
                                                    <small class="font-monospace text-break"><?php echo e(Str::limit($user->password, 50)); ?>...</small>
                                                </div>
                                                <hr>
                                                <p class="text-sm text-muted mb-0">
                                                    <i class="bi bi-shield-lock me-1"></i>
                                                    Gunakan fitur "Reset Password" untuk mengubah password.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Akun: <?php echo e($user->nama_pegawai); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?php echo e(route('manajemen-akun.update', $user->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Nama</label>
                                                                <input type="text" class="form-control" name="nama_pegawai"
                                                                       value="<?php echo e($user->nama_pegawai); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                       value="<?php echo e($user->email); ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                                                <input type="password" class="form-control" name="password">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Konfirmasi Password</label>
                                                                <input type="password" class="form-control" name="password_confirmation">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Lokasi/Cabang</label>
                                                                <select class="form-select" name="id_cabang" required>
                                                                    <?php $__currentLoopData = $cabangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($cabang->id); ?>"
                                                                                <?php echo e($user->id_cabang == $cabang->id ? 'selected' : ''); ?>>
                                                                            <?php echo e($cabang->nama_cabang); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Role</label>
                                                                <select class="form-select" name="role" required>
                                                                    <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                                                    <option value="manager" <?php echo e($user->role === 'manager' ? 'selected' : ''); ?>>Manager</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <?php if($user->id !== auth()->id()): ?>
                                    <div class="modal fade" id="deleteModal<?php echo e($user->id); ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus akun <strong><?php echo e($user->nama_pegawai); ?></strong> dengan role <span class="badge bg-<?php echo e($user->role == 'manager' ? 'danger' : 'success'); ?>"><?php echo e(ucfirst($user->role)); ?></span>?</p>
                                                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="<?php echo e(route('manajemen-akun.destroy', $user->id)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash me-1"></i>Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#akunTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [3, 7] }, // Disable sorting on password and action columns
                    { searchable: false, targets: [3, 7] }, // Disable search on password and action columns
                    { className: "text-center", targets: [0, 3, 4, 5, 6, 7] } // Center align specific columns
                ],
                order: [[6, 'desc']], // Sort by created date (newest first)
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function() {
                    // Re-initialize tooltips after table redraw
                    $('[title]').tooltip();
                }
            });

            // Custom filter functions
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var roleFilter = $('#roleFilter').val();
                    var cabangFilter = $('#cabangFilter').val();
                    var verifikasiFilter = $('#verifikasiFilter').val();

                    var role = data[5]; // Role column
                    var cabang = data[4]; // Cabang column
                    var verifikasi = data[2]; // Email column (contains verification status)

                    // Role filter
                    if (roleFilter && !role.toLowerCase().includes(roleFilter.toLowerCase())) {
                        return false;
                    }

                    // Cabang filter
                    if (cabangFilter && !cabang.includes(cabangFilter)) {
                        return false;
                    }

                    // Verifikasi filter
                    if (verifikasiFilter) {
                        if (verifikasiFilter === 'verified' && !verifikasi.includes('Terverifikasi')) {
                            return false;
                        }
                        if (verifikasiFilter === 'unverified' && !verifikasi.includes('Belum Verifikasi')) {
                            return false;
                        }
                    }

                    return true;
                }
            );

            // Filter event handlers
            $('#roleFilter, #cabangFilter, #verifikasiFilter').on('change', function() {
                table.draw();
            });

            // Reset filter
            $('#resetFilter').on('click', function() {
                $('#roleFilter, #cabangFilter, #verifikasiFilter').val('');
                table.search('').draw();
            });

            // Initialize tooltips
            $('[title]').tooltip();

            // Auto-open modal if there are validation errors
            <?php if($errors->any()): ?>
                $('#addAccountModal').modal('show');
            <?php endif; ?>
        });
     </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/manajemen-akun/index.blade.php ENDPATH**/ ?>