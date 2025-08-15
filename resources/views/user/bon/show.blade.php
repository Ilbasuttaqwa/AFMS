@extends('layouts.user.template')

@push('styles')
<style>
    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .detail-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 20px 20px 0 0;
        color: white;
        padding: 25px;
    }
    
    .detail-body {
        padding: 30px;
    }
    
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .info-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 15px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }
    
    .info-label i {
        margin-right: 8px;
        color: #28a745;
    }
    
    .employee-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-radius: 15px;
        padding: 20px;
    }
    
    .bon-info {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        border-radius: 15px;
        padding: 20px;
    }
    
    .description-info {
        background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
        border-radius: 15px;
        padding: 20px;
    }
    
    .additional-info {
        background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
        border-radius: 15px;
        padding: 20px;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
        color: #d63031;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #55efc4 0%, #00b894 100%);
        color: #00b894;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
        color: #e84393;
    }
    
    .btn-modern {
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #fd7e14 0%, #e55a4e 100%);
        color: white;
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .amount-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: #28a745;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .detail-item {
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .detail-item strong {
        color: #495057;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card detail-card mb-4">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-1">Detail Bon</h5>
                            <p class="text-white opacity-8 mb-0">Informasi lengkap pengajuan bon</p>
                        </div>
                        <div>
                            @if($bon->status == 'pending')
                                <a href="{{ route('user.bon.edit', $bon) }}" class="btn btn-light btn-modern me-2">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('user.bon.index') }}" class="btn btn-outline-light btn-modern">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="detail-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-user"></i>Informasi Karyawan
                            </div>
                            <div class="employee-info">
                                <div class="detail-item">
                                    <strong>Nama:</strong><br>
                                    <span class="h6 text-primary">{{ $bon->pegawai->nama_pegawai ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Jabatan:</strong><br>
                                    <span class="text-muted">{{ $bon->pegawai->jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Lokasi:</strong><br>
                                    <span class="text-muted">{{ $bon->pegawai->cabang->nama_cabang ?? 'Tidak ada cabang' }}</span>
                                </div>
                                <div class="detail-item">
                                    <strong>Email:</strong><br>
                                    <span class="text-muted">{{ $bon->pegawai->email ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">
                                <i class="fas fa-receipt"></i>Informasi Bon
                            </div>
                            <div class="bon-info">
                                <div class="detail-item">
                                    <strong>Tanggal:</strong><br>
                                    <span class="text-dark">{{ $bon->tanggal->format('d F Y') }}</span><br>
                                    <small class="text-muted">{{ $bon->tanggal->format('l') }}</small>
                                </div>
                                <div class="detail-item">
                                    <strong>Status:</strong><br>
                                    @if($bon->status == 'pending')
                                        <span class="status-badge status-pending">Pending</span>
                                    @elseif($bon->status == 'approved')
                                        <span class="status-badge status-approved">Disetujui</span>
                                    @else
                                        <span class="status-badge status-rejected">Ditolak</span>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <strong>Jumlah:</strong><br>
                                    <div class="amount-display">Rp {{ number_format($bon->jumlah, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Keterangan</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $bon->keterangan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Informasi Tambahan</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Dibuat oleh:</strong> {{ $bon->creator->nama_pegawai ?? 'N/A' }}</p>
                                                <p class="mb-1"><strong>Tanggal dibuat:</strong> {{ $bon->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Terakhir diupdate:</strong> {{ $bon->updated_at->format('d/m/Y H:i') }}</p>
                                                <p class="mb-1"><strong>ID Bon:</strong> #{{ $bon->id }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <form action="{{ route('user.bon.destroy', $bon) }}" method="POST" class="d-inline me-2" onsubmit="return confirm('Yakin ingin menghapus bon ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>Hapus Bon
                            </button>
                        </form>
                        <a href="{{ route('user.bon.edit', $bon) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Bon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection