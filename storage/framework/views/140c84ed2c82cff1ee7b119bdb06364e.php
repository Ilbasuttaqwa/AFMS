<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<?php $__env->stopSection(); ?>



<?php $__env->startSection('js'); ?>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#bonTable').DataTable({
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ada data yang cocok",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sSearch": "Cari:",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    }
                },
                "pageLength": 10,
                "order": [[0, 'asc']],
                "columnDefs": [
                    { "orderable": false, "targets": -1 }
                ],
                "destroy": true
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto hide alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);

            // Form submission handler
            $('form[action*="bon.store"]').on('submit', function(e) {
                var form = $(this);
                var pegawaiId = form.find('input[name="pegawai_id"]').val();
                var jumlah = form.find('input[name="jumlah"]').val();
                var tanggal = form.find('input[name="tanggal"]').val();

                // Validasi
                if (!pegawaiId || !jumlah || !tanggal) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi');
                    return false;
                }

                if (jumlah <= 0) {
                    e.preventDefault();
                    alert('Jumlah bon harus lebih dari 0');
                    return false;
                }

                // Disable submit button untuk mencegah double submit
                form.find('button[type="submit"]').prop('disabled', true).text('Menyimpan...');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Bon Karyawan</h4>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Filter Karyawan</h5>
                <form action="<?php echo e(route('keuangan.bon.index')); ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Cari nama karyawan..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="jabatan" class="form-label">Filter Golongan</label>
                            <select class="form-select" id="jabatan" name="jabatan">
                                <option value="">-- Semua Golongan --</option>
                            <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($jab->id); ?>" <?php echo e(request('jabatan') == $jab->id ? 'selected' : ''); ?>><?php echo e($jab->nama_jabatan); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cabang" class="form-label">Filter Lokasi</label>
                            <select class="form-select" id="cabang" name="cabang">
                                <option value="">-- Semua Lokasi --</option>
                            <?php $__currentLoopData = $cabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cab->id); ?>" <?php echo e(request('cabang') == $cab->id ? 'selected' : ''); ?>><?php echo e($cab->nama_cabang); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i> Cari</button>
                        <a href="<?php echo e(route('keuangan.bon.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold py-3">Tabel Bon Karyawan</h5>

                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="bonTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Golongan</th>
                                <th>Lokasi</th>
                                <th>Gaji Pokok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($user->nama_pegawai); ?></td>
                                    <td><?php echo e($user->jabatan->nama_jabatan ?? '-'); ?></td>
                                    <td><?php echo e($user->cabang->nama_cabang ?? '-'); ?></td>
                                    <td>Rp <?php echo e(number_format($user->jabatan->gaji_pokok ?? 0, 0, ',', '.')); ?></td>
                                    <td>
                                        <button type="button" class="btn rounded-pill btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bonModal<?php echo e($user->id); ?>"
                                                style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                            <i class="bi bi-cash" data-bs-toggle="tooltip"
                                               data-bs-offset="0,4" data-bs-placement="top"
                                               data-bs-html="true" title="Tambah Bon"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Bon untuk setiap user -->
<?php if(count($users) > 0): ?>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="bonModal<?php echo e($user->id); ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-cash me-2"></i>Tambah Bon Karyawan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?php echo e(route('keuangan.bon.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="pegawai_id" value="<?php echo e($user->id); ?>">
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-cash fs-3 text-success"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-person me-1"></i>Nama Karyawan
                                </label>
                                <input type="text" class="form-control bg-light border-0" value="<?php echo e($user->nama_pegawai); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-briefcase me-1"></i>Golongan
                                </label>
                                <input type="text" class="form-control bg-light border-0" value="<?php echo e($user->jabatan->nama_jabatan ?? '-'); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-currency-dollar me-1"></i>Jumlah Bon
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                                    <input type="number" name="jumlah" class="form-control" required min="0" step="1000" placeholder="Masukkan jumlah bon">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-calendar me-1"></i>Tanggal Bon
                                </label>
                                <input type="date" name="tanggal" class="form-control" required value="<?php echo e(date('Y-m-d')); ?>">
                                <small class="text-muted">Pilih tanggal pemberian bon</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-chat-text me-1"></i>Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan bon (opsional)"></textarea>
                            </div>

                            <div class="alert alert-info border-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Info:</strong> Bon akan dicatat sebagai pinjaman karyawan yang dapat dipotong dari gaji.
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Simpan Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/keuangan/bon_index.blade.php ENDPATH**/ ?>