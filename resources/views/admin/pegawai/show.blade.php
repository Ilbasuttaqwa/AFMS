@extends('layouts.admin.template')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Management Karyawan /</span> 
            <span class="text-muted fw-light">Pegawai /</span> 
            Detail
        </h4>

        <div class="row">
            <div class="col-md-4">
                <!-- Profile Card -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-user fs-2"></i>
                            </span>
                        </div>
                        <h5 class="mb-1">{{ $pegawai->nama_pegawai }}</h5>
                        <p class="text-muted mb-1">{{ $pegawai->jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}</p>
                        <p class="text-muted mb-3">{{ $pegawai->cabang->nama_cabang ?? 'Tidak ada lokasi' }}</p>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </a>
                            <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Status Karyawan</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-check-circle me-2 text-success"></i>
                            <span class="badge {{ $pegawai->status_pegawai ? 'bg-success' : 'bg-danger' }}">
                                {{ $pegawai->status_pegawai ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                        @if($pegawai->device_user_id)
                        <div class="d-flex align-items-center">
                            <i class="bx bx-fingerprint me-2 text-info"></i>
                            <small class="text-muted">ID Biometric: {{ $pegawai->device_user_id }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bx bx-user me-2"></i>Informasi Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Nama Lengkap</label>
                                <p class="mb-0">{{ $pegawai->nama_pegawai }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Jenis Kelamin</label>
                                <p class="mb-0">
                                    <span class="badge {{ $pegawai->jenis_kelamin == 'Laki-Laki' ? 'bg-primary' : 'bg-pink' }}">
                                        {{ $pegawai->jenis_kelamin }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tempat Lahir</label>
                                <p class="mb-0">{{ $pegawai->tempat_lahir ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tanggal Lahir</label>
                                <p class="mb-0">
                                    {{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Alamat</label>
                                <p class="mb-0">{{ $pegawai->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bx bx-envelope me-2"></i>Informasi Kontak
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $pegawai->email }}" class="text-decoration-none">
                                        {{ $pegawai->email }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Provinsi</label>
                                <p class="mb-0">{{ $pegawai->provinsi ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Kabupaten</label>
                                <p class="mb-0">{{ $pegawai->kabupaten ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Kecamatan</label>
                                <p class="mb-0">{{ $pegawai->kecamatan ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Kelurahan</label>
                                <p class="mb-0">{{ $pegawai->kelurahan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bx bx-briefcase me-2"></i>Informasi Pekerjaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Golongan</label>
                                <p class="mb-0">
                                    <span class="badge bg-label-info">
                                        {{ $pegawai->jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Lokasi</label>
                                <p class="mb-0">
                                    <span class="badge bg-label-warning">
                                        {{ $pegawai->cabang->nama_cabang ?? 'Tidak ada lokasi' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tanggal Masuk</label>
                                <p class="mb-0">
                                    {{ $pegawai->tanggal_masuk ? \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Gaji</label>
                                <p class="mb-0">
                                    {{ $pegawai->gaji ? 'Rp ' . number_format($pegawai->gaji, 0, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-pink {
            background-color: #e91e63 !important;
        }
        .avatar {
            position: relative;
            display: inline-block;
            width: 2.375rem;
            height: 2.375rem;
        }
        .avatar-xl {
            width: 4.875rem;
            height: 4.875rem;
        }
        .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #fff;
        }
        .bg-label-primary {
            background-color: rgba(105, 108, 255, 0.16) !important;
            color: #696cff !important;
        }
        .bg-label-info {
            background-color: rgba(3, 195, 236, 0.16) !important;
            color: #03c3ec !important;
        }
        .bg-label-warning {
            background-color: rgba(255, 171, 0, 0.16) !important;
            color: #ffab00 !important;
        }
    </style>
@endsection