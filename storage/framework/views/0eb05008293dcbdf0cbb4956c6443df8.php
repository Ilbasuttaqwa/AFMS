<!-- Modal Edit Jam Kerja -->
<div class="modal fade" id="editJamKerjaModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-clock me-2"></i>Edit Pengaturan Jam Kerja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('jabatan.update-jam-kerja', $jabatan->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-sunrise me-1"></i>Jam Masuk Pagi
                                </label>
                                <input type="time" class="form-control" name="jam_masuk" 
                                       value="<?php echo e($jabatan->jam_masuk ?? '08:00'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-sun me-1"></i>Jam Masuk Siang (Setelah Istirahat)
                                </label>
                                <input type="time" class="form-control" name="jam_masuk_siang" 
                                       value="<?php echo e($jabatan->jam_masuk_siang ?? '13:00'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-hourglass-split me-1"></i>Toleransi Keterlambatan (menit)
                                </label>
                                <input type="number" class="form-control" name="toleransi_keterlambatan" 
                                       value="<?php echo e($jabatan->toleransi_keterlambatan ?? 15); ?>" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-currency-dollar me-1"></i>Gaji Pokok
                                </label>
                                <input type="number" class="form-control" name="gaji_pokok" 
                                       value="<?php echo e($jabatan->gaji_pokok ?? 0); ?>" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Sistem fingerprint akan mencatat 4 kali:
                        <ul class="mb-0 mt-2">
                            <li>Fingerprint jam masuk pagi</li>
                            <li>Fingerprint sebelum istirahat</li>
                            <li>Fingerprint jam masuk siang setelah istirahat</li>
                            <li>Fingerprint pulang</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/jabatan/modals/edit-jam-kerja.blade.php ENDPATH**/ ?>