<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Seluruh Golongan</h4>



        
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="validationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                    <div class="me-auto fw-semibold">Success</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Data sudah ada!
                </div>
            </div>
        </div>


        <!-- Toast Untuk Success -->
        <?php if(session('success')): ?>
            <div class="bs-toast toast toast-placement-ex m-2 bg-success top-0 end-0 fade show toast-custom" role="alert"
                aria-live="assertive" aria-atomic="true" id="toastSuccess">
                <div class="toast-header">
                    <i class="bi bi-check-circle me-2"></i>
                    <div class="me-auto fw-semibold">Success</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php echo e(session('success')); ?>

                </div>
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
                <div class="toast-body">
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?>


        
        <?php if(session('danger')): ?>
            <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0 fade show toast-custom" role="alert"
                aria-live="assertive" aria-atomic="true" id="toastError">
                <div class="toast-header">
                    <i class="bx bx-error me-2"></i>
                    <div class="me-auto fw-semibold">Danger</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php echo e(session('danger')); ?>

                </div>
            </div>
        <?php endif; ?>

        
        <?php if(session('warning')): ?>
            <div class="bs-toast toast toast-placement-ex m-2 bg-warning top-0 end-0 fade show toast-custom" role="alert"
                aria-live="assertive" aria-atomic="true" id="toastError">
                <div class="toast-header">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <div class="me-auto fw-semibold">Warning</div>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php echo e(session('warning')); ?>

                </div>
            </div>
        <?php endif; ?>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold py-3 mb-0">Tabel Golongan</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahGolonganModal">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Golongan
                            </button>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Golongan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->index + 1); ?></td>
                                            <td><?php echo e($data->nama_jabatan); ?></td>
                                            <td>
                                                <form action="<?php echo e(route('jabatan.destroy', $data->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <a href="<?php echo e(route('jabatan.detail', $data->id)); ?>" class="btn rounded-pill btn-info"
                                                        style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                                        <i class="bi bi-eye" data-bs-toggle="tooltip"
                                                            data-bs-offset="0,4" data-bs-placement="top"
                                                            data-bs-html="true" title="Lihat Detail"></i>
                                                    </a>

                                                    <a href="javascript:void(0)" class="btn rounded-pill btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal<?php echo e($data->id); ?>"
                                                        style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                                        <i class="bi bi-pencil-square" data-bs-toggle="tooltip"
                                                            data-bs-offset="0,4" data-bs-placement="left"
                                                            data-bs-html="true" title="Edit Golongan"></i>

                                                    </a>

                                                    <a href="<?php echo e(route('jabatan.destroy', $data->id)); ?>"
                                                        class="btn rounded-pill btn-danger" data-confirm-delete="true"
                                                        style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                                        <i class="bi bi-trash-fill" data-bs-toggle="tooltip"
                                                            data-bs-offset="0,4" data-bs-placement="right"
                                                            data-bs-html="true" title="Delete Golongan"></i>

                                                    </a>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Jabatan -->
                                        <div class="modal fade" id="editModal<?php echo e($data->id); ?>" tabindex="-1"
                                            aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog modal-md modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Golongan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('jabatan.update', $data->id)); ?>"
                                                        method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="nama_jabatan" class="form-label">Nama
                                                                    Golongan</label>
                                                                <input type="text" class="form-control"
                                                                    name="nama_jabatan" value="<?php echo e($data->nama_jabatan); ?>"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-check-circle me-1"></i> Update Golongan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Golongan -->
    <div class="modal fade" id="tambahGolonganModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Golongan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('jabatan.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-briefcase fs-3 text-primary"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_jabatan" class="form-label fw-bold text-dark">
                                <i class="bi bi-tag me-1"></i>Nama Golongan
                            </label>
                            <input type="text" class="form-control" name="nama_jabatan" placeholder="Masukkan nama golongan" required>
                        </div>

                        <div class="alert alert-info border-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Golongan akan digunakan untuk mengkategorikan karyawan berdasarkan jabatan mereka.
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Golongan
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
        // Script untuk tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
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

<?php echo $__env->make('layouts.admin.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/jabatan/index.blade.php ENDPATH**/ ?>