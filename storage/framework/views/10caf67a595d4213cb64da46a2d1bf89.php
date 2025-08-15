<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Laporan Absensi</h4>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="<?php echo e(route('laporan.absensi')); ?>">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select" name="year" id="year">
                                <?php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 5;
                                ?>
                                <?php for($year = $currentYear; $year >= $startYear; $year--): ?>
                                    <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month" class="form-label">Month</label>
                            <select class="form-select" name="month" id="month">
                                <?php for($month = 1; $month <= 12; $month++): ?>
                                    <option value="<?php echo e($month); ?>" <?php echo e(request('month') == $month ? 'selected' : ''); ?>>
                                        <?php echo e(date('F', mktime(0, 0, 0, $month, 1))); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cabang" class="form-label">Lokasi</label>
                            <select class="form-select" name="cabang" id="cabang">
                                <option value="">Semua Lokasi</option>
                                <?php $__currentLoopData = \App\Models\Cabang::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cabang->id); ?>" <?php echo e(request('cabang') == $cabang->id ? 'selected' : ''); ?>>
                                        <?php echo e($cabang->nama_cabang); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary form-control" type="submit">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <?php if(!$absensi->isEmpty()): ?>
                        <div class="col-4">
                            <button id="lihatPdfButtonAbsensi" class="btn btn-secondary form-control" data-bs-toggle="modal" data-bs-target="#pdfModal">
                                Lihat PDF
                            </button>
                        </div>
                        <div class="col-4">
                            <a href="<?php echo e(route('laporan.absensi', ['download_pdf' => true, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')])); ?>" class="btn btn-info form-control">
                                Buat PDF
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="<?php echo e(route('laporan.absensi', ['download_excel' => true, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')])); ?>" class="btn btn-success form-control">
                                Buat EXCEL
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>ID Fingerprint</th>
                                <th>Lokasi</th>
                                <th>Kategori/Golongan</th>
                                <th>Morning Check-in</th>
                                <th>Evening Check-in</th>
                                <th>Sumber Data</th>
                                <th>Late Penalty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($item->user->nama_pegawai ?? "Tidak Ada Data"); ?></td>
                                    <td>
                                        <?php if($item->user->device_user_id): ?>
                                            <span class="badge bg-info"><?php echo e($item->user->device_user_id); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->user->cabang->nama_cabang ?? "Tidak Ada Data"); ?></td>
                                    <td><?php echo e($item->user->jabatan->nama_jabatan ?? "Tidak Ada Data"); ?></td>
                                    <td><?php echo e($item->jam_masuk ?? "Tidak Ada Data"); ?></td>
                                    <td><?php echo e($item->jam_masuk_sore ?? "Tidak Ada Data"); ?></td>
                                    <td>
                                        <?php if(str_contains($item->keterangan ?? '', 'fingerprint')): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-fingerprint"></i> Fingerprint
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-pencil"></i> Manual
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $lateMinutes = 0;
                                            if ($item->jam_masuk) {
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i', '07:30');
                                                $checkInTime = \Carbon\Carbon::parse($item->jam_masuk);

                                                if ($checkInTime->gt($startTime)) {
                                                    $lateMinutes = $checkInTime->diffInMinutes($startTime);
                                                }
                                            }
                                        ?>

                                        <?php if($lateMinutes > 0): ?>
                                            <span class="badge bg-danger"><?php echo e($lateMinutes); ?> menit</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center">Data absensi tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/laporan/absensi.blade.php ENDPATH**/ ?>