@extends('layouts.user.template')

@section('content')
<style>
.sick-leave-header {
    background: #2563eb;
    color: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(37, 99, 235, 0.1);
}

.sick-leave-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.btn-add-modern {
    background: #2563eb;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
    color: white;
}

.table-modern {
    border-radius: 10px;
    overflow: hidden;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 15px;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background-color: #f8f9ff;
    transform: scale(1.01);
}

.table-modern tbody td {
    padding: 15px;
    vertical-align: middle;
    border: none;
}

.status-badge-sick {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.status-badge-leave {
    background: linear-gradient(45deg, #ffc107, #ff9800);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.action-btn {
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    margin: 0 2px;
    transition: all 0.3s ease;
    font-size: 12px;
}

.action-btn-view {
    background: linear-gradient(45deg, #17a2b8, #20c997);
    color: white;
}

.action-btn-edit {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: white;
}

.action-btn-delete {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #6c757d;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 0;
}

.alert-modern {
    border: none;
    border-radius: 10px;
    padding: 15px 20px;
    margin: 20px;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
}

.employee-info h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 2px;
}

.employee-info p {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 0;
}

.date-info h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 2px;
}

.date-info p {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 0;
}

.note-text {
    color: #495057;
    font-weight: 500;
}

.creator-info h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 2px;
}

.creator-info p {
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 0;
}
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card sick-leave-card mb-4">
                <div class="sick-leave-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-user-injured me-2"></i>Data Sakit & Izin</h4>
                        <p class="mb-0 opacity-8">Kelola data absensi sakit dan izin karyawan</p>
                    </div>
                    <a href="{{ route('user.sakit-izin.create') }}" class="btn btn-add-modern">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table table-modern align-items-center mb-0" id="sakitIzinTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Pegawai</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dibuat Oleh</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sakitIzins as $index => $sakitIzin)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="employee-info">
                                            <h6>{{ $sakitIzin->user->nama_pegawai ?? 'N/A' }}</h6>
                                            <p>{{ $sakitIzin->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <h6>{{ $sakitIzin->tanggal->format('d M Y') }}</h6>
                                            <p>{{ $sakitIzin->tanggal->format('l') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        @if($sakitIzin->keterangan == 'Sakit')
                                            <span class="status-badge-sick">Sakit</span>
                                        @else
                                            <span class="status-badge-leave">Izin</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="note-text">{{ Str::limit($sakitIzin->catatan ?? 'Tidak ada catatan', 30) }}</span>
                                    </td>
                                    <td>
                                        <div class="creator-info">
                                            <h6>{{ $sakitIzin->creator->nama_pegawai ?? 'N/A' }}</h6>
                                            <p>{{ $sakitIzin->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <a href="{{ route('user.sakit-izin.show', $sakitIzin) }}" class="btn action-btn action-btn-view">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->is_admin || $sakitIzin->created_by == Auth::user()->id)
                                                <a href="{{ route('user.sakit-izin.edit', $sakitIzin) }}" class="btn action-btn action-btn-edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('user.sakit-izin.destroy', $sakitIzin) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn action-btn action-btn-delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-user-injured"></i>
                                            <h5>Belum Ada Data Sakit/Izin</h5>
                                            <p>Tidak ada data absensi sakit atau izin yang tercatat</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#sakitIzinTable').DataTable({
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "emptyTable": "Tidak ada data tersedia",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "responsive": true,
        "pageLength": 10,
        "order": [[ 2, "desc" ]]
    });
});
</script>
@endpush
@endsection