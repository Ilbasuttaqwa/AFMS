<!-- Modal Edit Potongan Keterlambatan -->
<div class="modal fade" id="editPotonganModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Edit Pengaturan Potongan Keterlambatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('jabatan.update-potongan', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Sistem Akumulasi Bulanan:</strong> Potongan dihitung berdasarkan total menit keterlambatan dalam satu bulan
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-sunrise me-1"></i>Keterlambatan Masuk Pagi
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label">0-30 menit (Tidak ada potongan)</label>
                                <input type="number" class="form-control" value="0" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">31-45 menit</label>
                                <input type="number" class="form-control" name="potongan_31_45" 
                                       value="{{ $jabatan->potongan_31_45 ?? 10000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">46-60 menit</label>
                                <input type="number" class="form-control" name="potongan_46_60" 
                                       value="{{ $jabatan->potongan_46_60 ?? 15000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">61-100 menit</label>
                                <input type="number" class="form-control" name="potongan_61_100" 
                                       value="{{ $jabatan->potongan_61_100 ?? 25000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">101-200 menit</label>
                                <input type="number" class="form-control" name="potongan_101_200" 
                                       value="{{ $jabatan->potongan_101_200 ?? 50000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">200+ menit</label>
                                <input type="number" class="form-control" name="potongan_200_plus" 
                                       value="{{ $jabatan->potongan_200_plus ?? 100000 }}" min="0">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-sun me-1"></i>Keterlambatan Masuk Setelah Istirahat
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label">0-30 menit (Tidak ada potongan)</label>
                                <input type="number" class="form-control" value="0" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">31-45 menit</label>
                                <input type="number" class="form-control" name="potongan_siang_31_45" 
                                       value="{{ $jabatan->potongan_31_45 ?? 10000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">46-60 menit</label>
                                <input type="number" class="form-control" name="potongan_siang_46_60" 
                                       value="{{ $jabatan->potongan_46_60 ?? 15000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">61-100 menit</label>
                                <input type="number" class="form-control" name="potongan_siang_61_100" 
                                       value="{{ $jabatan->potongan_61_100 ?? 25000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">101-200 menit</label>
                                <input type="number" class="form-control" name="potongan_siang_101_200" 
                                       value="{{ $jabatan->potongan_101_200 ?? 50000 }}" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">200+ menit</label>
                                <input type="number" class="form-control" name="potongan_siang_200_plus" 
                                       value="{{ $jabatan->potongan_200_plus ?? 100000 }}" min="0">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>Simpan Pengaturan Potongan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>