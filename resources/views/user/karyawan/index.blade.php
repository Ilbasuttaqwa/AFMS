@extends('layouts.user.template')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Data Karyawan - {{ $currentBranch->nama_cabang ?? 'Lokasi Tidak Diketahui' }}</h6>
                            <p class="text-sm mb-0">Daftar karyawan yang bekerja di lokasi yang sama dengan Anda</p>
                        </div>
                        <div class="badge badge-info">
                            Total: {{ $karyawan->total() }} Karyawan
                        </div>
                    </div>
                </div>
                
                <!-- Filter Section -->
                <div class="card-body">
                    <form method="GET" action="{{ route('user.karyawan.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-search me-1"></i>Cari Karyawan
                                </label>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Nama, Email, atau NIP..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-briefcase me-1"></i>Filter Jabatan
                                </label>
                                <select class="form-select" name="jabatan">
                                    <option value="">Semua Jabatan</option>
                                    @foreach($jabatan as $j)
                                        <option value="{{ $j->id }}" {{ request('jabatan') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('user.karyawan.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Employee Cards -->
                    <div class="row">
                        @forelse($karyawan as $employee)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar avatar-lg bg-gradient-primary rounded-circle me-3">
                                                <span class="text-white font-weight-bold">
                                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $employee->name }}</h6>
                                                <p class="text-sm text-muted mb-0">{{ $employee->jabatan->nama_jabatan ?? 'Tidak Ada Jabatan' }}</p>
                                                @if($employee->nip)
                                                    <small class="text-xs text-secondary">NIP: {{ $employee->nip }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h6 class="text-primary mb-0">{{ $employee->attendance_stats['total_days'] }}</h6>
                                                    <small class="text-xs text-muted">Total Hadir</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h6 class="text-success mb-0">{{ $employee->attendance_stats['on_time_days'] }}</h6>
                                                    <small class="text-xs text-muted">Tepat Waktu</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <h6 class="text-warning mb-0">{{ $employee->attendance_stats['late_days'] }}</h6>
                                                <small class="text-xs text-muted">Terlambat</small>
                                            </div>
                                        </div>
                                        
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-gradient-success" 
                                                 style="width: {{ $employee->attendance_stats['attendance_rate'] }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Tingkat Kehadiran: {{ $employee->attendance_stats['attendance_rate'] }}%</small>
                                            <a href="{{ route('user.karyawan.show', $employee->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada karyawan ditemukan</h5>
                                    <p class="text-sm text-muted">
                                        @if(request('search') || request('jabatan'))
                                            Coba ubah filter pencarian Anda
                                        @else
                                            Belum ada karyawan lain di lokasi ini
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($karyawan->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $karyawan->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit form when filter changes
    document.querySelector('select[name="jabatan"]').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Add loading state to filter button
    document.querySelector('form').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memuat...';
        btn.disabled = true;
    });
</script>
@endpush
@endsection