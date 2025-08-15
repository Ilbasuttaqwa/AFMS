<!-- Modal Tambah Bon -->
<div class="modal fade" id="tambahBonModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Data Bon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('jabatan.store-bon')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="jabatan_id" value="<?php echo e($jabatan->id); ?>">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin fs-3 text-warning"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person me-1"></i>Karyawan
                        </label>
                        <select class="form-select" name="pegawai_id" required>
                            <option value="">Pilih Karyawan</option>
                            <?php $__currentLoopData = $pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($karyawan->id); ?>"><?php echo e($karyawan->nama_pegawai); ?> - <?php echo e($karyawan->cabang->nama_cabang ?? '-'); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar me-1"></i>Jumlah Bon
                        </label>
                        <input type="number" class="form-control" name="jumlah" min="1000" step="1000" required>
                        <div class="form-text">Minimal Rp 1.000</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-chat-text me-1"></i>Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukkan keterangan bon..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar me-1"></i>Tanggal Bon
                        </label>
                        <input type="date" class="form-control" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>Simpan Bon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Bon -->
<div class="modal fade" id="editBonModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Data Bon</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBonForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-pencil-square fs-3 text-info"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person me-1"></i>Karyawan
                        </label>
                        <select class="form-select" name="pegawai_id" id="edit_pegawai_id" required>
                            <option value="">Pilih Karyawan</option>
                            <?php $__currentLoopData = $pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($karyawan->id); ?>"><?php echo e($karyawan->nama_pegawai); ?> - <?php echo e($karyawan->cabang->nama_cabang ?? '-'); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar me-1"></i>Jumlah Bon
                        </label>
                        <input type="number" class="form-control" name="jumlah" id="edit_jumlah" min="1000" step="1000" required>
                        <div class="form-text">Minimal Rp 1.000</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-chat-text me-1"></i>Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" id="edit_keterangan" rows="3" placeholder="Masukkan keterangan bon..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar me-1"></i>Tanggal Bon
                        </label>
                        <input type="date" class="form-control" name="tanggal" id="edit_tanggal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-check-circle me-1"></i>Status
                        </label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="paid">Sudah Dibayar</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-check-circle me-1"></i>Update Bon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editBon(id, pegawaiId, jumlah, keterangan, tanggal, status) {
    $('#editBonForm').attr('action', `/admin/jabatan/bon/${id}`);
    $('#edit_pegawai_id').val(pegawaiId);
    $('#edit_jumlah').val(jumlah);
    $('#edit_keterangan').val(keterangan);
    $('#edit_tanggal').val(tanggal);
    $('#edit_status').val(status);
    $('#editBonModal').modal('show');
}

function deleteBon(id, namaKaryawan, jumlah) {
    if (confirm(`Hapus data bon ${namaKaryawan} sebesar Rp ${new Intl.NumberFormat('id-ID').format(jumlah)}?`)) {
        $.ajax({
            url: `/admin/jabatan/bon/${id}`,
            method: 'DELETE',
            data: {
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Data bon berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus data bon!');
            }
        });
    }
}
</script><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/jabatan/modals/bon-modal.blade.php ENDPATH**/ ?>