<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold py-3 mb-0">Tabel Seluruh Lokasi</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLokasiModal">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Lokasi
                        </button>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lokasi</th>
                                    <th>Alamat</th>
                                    <th>Kode Lokasi</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php $__currentLoopData = $cabang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->index + 1); ?></td>
                                        <td><?php echo e($data->nama_cabang); ?></td>
                                        <td><?php echo e($data->alamat ?? '-'); ?></td>
                                        <td><?php echo e($data->kode_cabang); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('cabang.destroy', $data->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <a href="<?php echo e(route('cabang.show', $data->id)); ?>" class="btn rounded-pill btn-info"
                                                    style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                                    <i class="bi bi-eye" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                        data-bs-placement="left" data-bs-html="true" title="Lihat Detail"></i>
                                                </a>
                                                <a href="<?php echo e(route('cabang.edit', $data->id)); ?>" class="btn rounded-pill btn-primary"
                                                    style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                                    <i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                        data-bs-placement="left" data-bs-html="true" title="Edit Lokasi"></i>
                                                </a>
                                                <button type="submit" class="btn rounded-pill btn-danger"
                                                    style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                        data-bs-placement="left" data-bs-html="true" title="Hapus Lokasi"></i>
                                                </button>
                                            </form>
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

    <!-- Modal Tambah Lokasi -->
    <div class="modal fade" id="tambahLokasiModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Lokasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('cabang.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-building fs-3 text-primary"></i>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_cabang" class="form-label fw-bold text-dark">
                                    <i class="bi bi-building me-1"></i>Nama Lokasi
                                </label>
                                <input type="text" class="form-control" name="nama_cabang" placeholder="Masukkan nama lokasi" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kode_cabang" class="form-label fw-bold text-dark">
                                    <i class="bi bi-tag me-1"></i>Kode Lokasi
                                </label>
                                <input type="text" class="form-control" name="kode_cabang" placeholder="Masukkan kode lokasi" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold text-dark">
                                <i class="bi bi-geo-alt me-1"></i>Alamat
                            </label>
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan alamat lokasi"></textarea>
                        </div>

                        <div class="alert alert-info border-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Lokasi akan digunakan untuk mengkategorikan karyawan berdasarkan tempat kerja mereka.
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        new DataTable('#example', {
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
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/cabang/index.blade.php ENDPATH**/ ?>