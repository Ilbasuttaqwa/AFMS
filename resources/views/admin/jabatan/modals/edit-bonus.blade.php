<!-- Modal Edit Bonus Tidak Ambil Libur -->
<div class="modal fade" id="editBonusModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-gift me-2"></i>Edit Bonus Tidak Ambil Libur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('jabatan.update-bonus', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-gift fs-3 text-success"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar me-1"></i>Bonus per Hari Tidak Ambil Libur
                        </label>
                        <input type="number" class="form-control" name="bonus_tidak_libur" 
                               value="{{ $jabatan->bonus_tidak_libur ?? 25000 }}" min="0" required>
                        <div class="form-text">Jumlah bonus yang akan diberikan untuk setiap hari libur yang tidak diambil</div>
                    </div>
                    
                    <div class="alert alert-success border-0">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Contoh Perhitungan:</strong>
                        <div class="mt-2">
                            <p class="mb-1">Jatah libur: 2 hari/bulan</p>
                            <p class="mb-1">Libur yang diambil: 0 hari</p>
                            <p class="mb-1">Sisa libur: 2 hari</p>
                            <p class="mb-0"><strong>Total bonus: 2 × Rp {{ number_format($jabatan->bonus_tidak_libur ?? 25000, 0, ',', '.') }} = Rp {{ number_format(2 * ($jabatan->bonus_tidak_libur ?? 25000), 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Bonus hanya diberikan jika karyawan tidak mengambil seluruh jatah libur dalam bulan tersebut.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Simpan Bonus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>