<!-- Modal Edit Pengaturan Minimal Absen -->
<div class="modal fade" id="editMinimalAbsenModal" tabindex="-1" aria-labelledby="editMinimalAbsenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMinimalAbsenModalLabel">
                    <i class="bi bi-clock-history me-2"></i>Edit Pengaturan Minimal Waktu Absen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jabatan.updateMinimalAbsen', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong> Pengaturan ini akan menerapkan potongan gaji jika karyawan absen sebelum waktu minimal yang ditentukan.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-sunrise me-1"></i>Pengaturan Absen Pagi
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-clock me-1"></i>Minimal Waktu Absen Pagi
                                </label>
                                <input type="time" class="form-control" name="minimal_absen_pagi" 
                                       value="{{ $jabatan->minimal_absen_pagi ?? '07:00' }}" required>
                                <small class="form-text text-muted">Karyawan yang absen sebelum jam ini akan dikenakan potongan</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-sun me-1"></i>Pengaturan Absen Siang
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-clock me-1"></i>Minimal Waktu Absen Siang
                                </label>
                                <input type="time" class="form-control" name="minimal_absen_siang" 
                                       value="{{ $jabatan->minimal_absen_siang ?? '12:00' }}" required>
                                <small class="form-text text-muted">Karyawan yang absen sebelum jam ini akan dikenakan potongan</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-danger mb-3">
                                <i class="bi bi-currency-dollar me-1"></i>Pengaturan Potongan
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-cash me-1"></i>Potongan Absen Terlalu Awal (Rp)
                                </label>
                                <input type="number" class="form-control" name="potongan_absen_awal" 
                                       value="{{ $jabatan->potongan_absen_awal ?? 10000 }}" min="0" required>
                                <small class="form-text text-muted">Jumlah potongan gaji per kejadian absen sebelum waktu minimal</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Contoh Penerapan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Jika minimal absen pagi adalah 07:00 dan karyawan absen pada 06:45, maka akan dikenakan potongan.</li>
                            <li>Jika minimal absen siang adalah 12:00 dan karyawan absen pada 11:30, maka akan dikenakan potongan.</li>
                            <li>Potongan akan otomatis dihitung dalam sistem penggajian bulanan.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>