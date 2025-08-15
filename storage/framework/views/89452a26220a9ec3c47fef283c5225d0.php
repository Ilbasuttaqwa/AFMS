<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
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
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Keuangan /</span> Potongan Gaji</h4>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Filter Karyawan</h5>
                <form action="<?php echo e(route('keuangan.potongan-gaji')); ?>" method="GET" class="row g-3">
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
                        <a href="<?php echo e(route('keuangan.potongan-gaji')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold py-3">Tabel Potongan Gaji Karyawan</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="example">
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
                                        <button type="button" class="btn rounded-pill btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#potonganModal<?php echo e($user->id); ?>"
                                                style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                            <i class="bi bi-dash-circle" data-bs-toggle="tooltip"
                                               data-bs-offset="0,4" data-bs-placement="top"
                                               data-bs-html="true" title="Potong Gaji"></i>
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

<!-- Modal Tambah Potongan -->
<?php if(isset($users) && count($users) > 0): ?>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="potonganModal<?php echo e($user->id); ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-dash-circle me-2"></i>Tambah Potongan Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('keuangan.store-potongan-gaji')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_user" value="<?php echo e($user->id); ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" value="<?php echo e($user->nama_pegawai); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Potongan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="jumlah_potongan" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bulan/Tahun</label>
                            <input type="month" name="bulan_tahun" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan alasan potongan gaji..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-save me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/keuangan/potongan_gaji.blade.php ENDPATH**/ ?>