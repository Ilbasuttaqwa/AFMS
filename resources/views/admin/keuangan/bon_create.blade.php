@extends('layouts.admin.template')

@section('title', 'Tambah Bon')

@section('css')
<style>
    .content-wrapper {
        min-height: 100vh !important;
        background: #f5f5f9 !important;
    }
    
    .card {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="min-height: calc(100vh - 120px);">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-plus me-2"></i>Tambah Bon</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('keuangan.bon.store') }}" method="POST" id="bonForm">
                        @csrf

                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-money-bill fs-3 text-primary"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-1"></i>Pilih Pegawai
                            </label>
                            <select name="pegawai_id" class="form-control @error('pegawai_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                        {{ $pegawai->nama_pegawai }} - {{ $pegawai->jabatan->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pegawai_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-money-bill-wave me-1"></i>Jumlah Bon
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                                       value="{{ old('jumlah') }}" required min="1" step="1000" placeholder="Masukkan jumlah bon">
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar me-1"></i>Tanggal
                            </label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-comment me-1"></i>Keterangan
                            </label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="3" required placeholder="Masukkan keterangan bon">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info border-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Info:</strong> Bon akan otomatis disetujui setelah disimpan.
                        </div>
                    </form>
                </div>
                <div class="card-footer border-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('keuangan.bon.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" form="bonForm" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Bon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
