<!-- Modal Edit Denda Libur Berlebih -->
<div class="modal fade" id="editDendaLiburModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-dash-circle me-2"></i>Edit Denda Libur Berlebih</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('jabatan.update-denda-libur', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar me-1"></i>Denda per Hari Libur Berlebih
                        </label>
                        <input type="number" class="form-control" name="denda_per_hari_libur" 
                               value="{{ $jabatan->denda_per_hari_libur ?? 50000 }}" min="0" required>
                        <div class="form-text">Jumlah denda yang akan dipotong untuk setiap hari libur yang melebihi jatah</div>
                    </div>
                    
                    <div class="alert alert-warning border-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Contoh Perhitungan:</strong>
                        <div class="mt-2">
                            <p class="mb-1">Jatah libur: 2 hari/bulan</p>
                            <p class="mb-1">Libur yang diambil: 4 hari</p>
                            <p class="mb-1">Libur berlebih: 2 hari</p>
                            <p class="mb-0"><strong>Total denda: 2 × Rp {{ number_format($jabatan->denda_per_hari_libur ?? 50000, 0, ',', '.') }} = Rp {{ number_format(2 * ($jabatan->denda_per_hari_libur ?? 50000), 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle me-1"></i>Simpan Denda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>