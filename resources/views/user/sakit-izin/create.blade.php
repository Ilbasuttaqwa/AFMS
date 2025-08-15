@extends('layouts.user.template')

@section('content')
<style>
/* Clean Blue Theme - No Gradients */
.create-header {
    background: #2563eb;
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 25px;
    margin: -1px -1px 0 -1px;
}

.create-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    background: white;
}

.btn-back-modern {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    padding: 10px 20px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back-modern:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-1px);
    color: white;
}

.form-modern {
    padding: 30px;
    background: white;
}

.form-label-modern {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control-modern {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background-color: white;
}

.form-control-modern:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    background-color: white;
}

.select-modern {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background-color: white;
}

.select-modern:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    background-color: white;
}

.info-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    margin-top: 10px;
}

.info-card h6 {
    color: #374151;
    font-weight: 600;
    margin-bottom: 5px;
}

.info-card p {
    color: #6b7280;
    margin-bottom: 8px;
    font-size: 14px;
}

.btn-reset-modern {
    background: #6b7280;
    border: none;
    border-radius: 8px;
    padding: 12px 25px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-reset-modern:hover {
    background: #4b5563;
    transform: translateY(-1px);
    color: white;
}

.btn-submit-modern {
    background: #2563eb;
    border: none;
    border-radius: 8px;
    padding: 12px 25px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-submit-modern:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    color: white;
}

.alert-modern {
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 25px;
    background: #fef2f2;
}

.required-star {
    color: #dc2626;
    font-weight: bold;
}

.form-section {
    margin-bottom: 25px;
}

.bg-danger-light {
    background: #fef2f2 !important;
    border-color: #fecaca !important;
}

.bg-warning-light {
    background: #fffbeb !important;
    border-color: #fed7aa !important;
}
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card create-card mb-4">
                <div class="create-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-plus-circle me-2"></i>Tambah Data Sakit/Izin</h4>
                        <p class="mb-0 opacity-8">Buat laporan absensi sakit atau izin karyawan</p>
                    </div>
                    <a href="{{ route('user.sakit-izin.index') }}" class="btn btn-back-modern">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
                <div class="form-modern">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-modern">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.sakit-izin.store') }}" method="POST">
                        @csrf
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_user" class="form-label-modern">Nama Karyawan <span class="required-star">*</span></label>
                                        <select class="form-control form-control-modern select-modern @error('id_user') is-invalid @enderror" id="id_user" name="id_user" required>
                                            <option value="">Pilih Karyawan</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('id_user') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->nama_pegawai }} - {{ $employee->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="form-label-modern">Tanggal <span class="required-star">*</span></label>
                                        <input type="date" class="form-control form-control-modern @error('tanggal') is-invalid @enderror" 
                                               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keterangan" class="form-label-modern">Keterangan <span class="required-star">*</span></label>
                                        <select class="form-control form-control-modern select-modern @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" required>
                                            <option value="">Pilih Keterangan</option>
                                            <option value="Sakit" {{ old('keterangan') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="Izin" {{ old('keterangan') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        </select>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-modern">Lokasi/Cabang</label>
                                        <input type="text" class="form-control form-control-modern" 
                                               value="{{ Auth::user()->cabang->nama_cabang ?? 'Belum ditentukan' }}" 
                                               readonly>
                                        <small class="text-muted">Otomatis sesuai akun login</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="catatan" class="form-label-modern">Catatan Tambahan</label>
                                        <textarea class="form-control form-control-modern @error('catatan') is-invalid @enderror" 
                                                  id="catatan" name="catatan" rows="4" 
                                                  placeholder="Masukkan catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label-modern">Informasi Pelapor</label>
                                        <div class="info-card">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-user me-2"></i>{{ Auth::user()->nama_pegawai }}</h6>
                                                    <p><i class="fas fa-briefcase me-2"></i>{{ Auth::user()->jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</h6>
                                                    <p><i class="fas fa-building me-2"></i>{{ Auth::user()->cabang->nama_cabang ?? 'Belum ditentukan' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-reset-modern">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-submit-modern">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for employee selection
    $('#id_user').select2({
        placeholder: 'Cari nama karyawan...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });

    // Change info card color based on keterangan selection
    $('#keterangan').on('change', function() {
        const value = $(this).val();
        const infoCard = $('.info-card');
        
        if (value === 'Sakit') {
            infoCard.removeClass('info-card').addClass('bg-danger-light');
        } else if (value === 'Izin') {
            infoCard.removeClass('info-card').addClass('bg-warning-light');
        } else {
            infoCard.removeClass('bg-danger-light bg-warning-light').addClass('info-card');
        }
    });

    // Add smooth animations
    $('.form-control-modern, .select-modern').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
});
</script>
@endpush
@endsection