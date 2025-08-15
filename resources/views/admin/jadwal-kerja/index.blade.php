@extends('layouts.admin')

@section('title', 'Jadwal Kerja')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Jadwal Kerja</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('jadwal-kerja.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" 
                                           id="jam_masuk" name="jam_masuk" value="{{ $jadwalKerja->jam_masuk }}" required>
                                    @error('jam_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jam_pulang" class="form-label">Jam Pulang</label>
                                    <input type="time" class="form-control @error('jam_pulang') is-invalid @enderror" 
                                           id="jam_pulang" name="jam_pulang" value="{{ $jadwalKerja->jam_pulang }}" required>
                                    @error('jam_pulang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="toleransi_keterlambatan" class="form-label">Toleransi Keterlambatan (menit)</label>
                                    <input type="number" class="form-control @error('toleransi_keterlambatan') is-invalid @enderror" 
                                           id="toleransi_keterlambatan" name="toleransi_keterlambatan" 
                                           value="{{ $jadwalKerja->toleransi_keterlambatan }}" min="0" required>
                                    @error('toleransi_keterlambatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Berapa menit toleransi keterlambatan sebelum dikenakan denda</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="potongan_per_menit" class="form-label">Potongan per Menit (Rp)</label>
                                    <input type="number" class="form-control @error('potongan_per_menit') is-invalid @enderror" 
                                           id="potongan_per_menit" name="potongan_per_menit" 
                                           value="{{ $jadwalKerja->potongan_per_menit }}" min="0" required>
                                    @error('potongan_per_menit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Jumlah potongan gaji per menit keterlambatan</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection