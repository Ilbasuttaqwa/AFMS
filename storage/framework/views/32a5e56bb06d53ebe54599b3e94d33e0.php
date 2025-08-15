<!-- Modal Edit Pengaturan Libur -->
<div class="modal fade" id="editLiburModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Edit Pengaturan Libur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('jabatan.update-libur', $jabatan->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-month fs-3 text-info"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-month me-1"></i>Jatah Libur per Bulan (hari)
                        </label>
                        <input type="number" class="form-control" name="jatah_libur_per_bulan" 
                               value="<?php echo e($jabatan->jatah_libur_per_bulan ?? 2); ?>" min="0" max="31" required>
                        <div class="form-text">Jumlah hari libur yang diperbolehkan dalam satu bulan</div>
                    </div>
                    
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Jika karyawan mengambil libur sesuai jatah = gaji normal</li>
                            <li>Jika karyawan mengambil libur melebihi jatah = akan ada denda</li>
                            <li>Jika karyawan tidak mengambil jatah libur = akan mendapat bonus</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-check-circle me-1"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/jabatan/modals/edit-libur.blade.php ENDPATH**/ ?>