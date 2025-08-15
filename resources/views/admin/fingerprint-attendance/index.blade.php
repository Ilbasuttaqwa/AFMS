@extends('layouts.admin.template')

@section('title', 'Data Fingerprint Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Fingerprint Attendance</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" onclick="reprocessData()">
                            <i class="fas fa-sync"></i> Proses Ulang Data
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('fingerprint-attendance.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="date">Tanggal:</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="processed">Status:</label>
                                <select name="processed" id="processed" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="1" {{ request('processed') == '1' ? 'selected' : '' }}>Sudah Diproses</option>
                                    <option value="0" {{ request('processed') == '0' ? 'selected' : '' }}>Belum Diproses</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-info mr-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('fingerprint-attendance.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Device User ID</th>
                                    <th>Nama Pegawai</th>
                                    <th>Cabang</th>
                                    <th>Waktu Absensi</th>
                                    <th>Tipe Absensi</th>
                                    <th>Device IP</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $attendances->firstItem() + $index }}</td>
                                    <td>{{ $attendance->device_user_id }}</td>
                                    <td>
                                        @if($attendance->user)
                                            {{ $attendance->user->nama_pegawai }}
                                        @else
                                            <span class="text-muted">User tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->cabang)
                                            {{ $attendance->cabang->nama_cabang }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->attendance_time->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->attendance_type == 1 ? 'success' : ($attendance->attendance_type == 2 ? 'danger' : 'warning') }}">
                                            {{ $attendance->attendance_type_name }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->device_ip }}</td>
                                    <td>
                                        @if($attendance->is_processed)
                                            <span class="badge badge-success">Sudah Diproses</span>
                                        @else
                                            <span class="badge badge-warning">Belum Diproses</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" onclick="showDetails({{ $attendance->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $attendance->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data fingerprint attendance</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Fingerprint Attendance</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function reprocessData() {
    if (confirm('Apakah Anda yakin ingin memproses ulang data fingerprint yang belum diproses?')) {
        $.ajax({
            url: '{{ route("fingerprint-attendance.reprocess") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses data'
                });
            }
        });
    }
}

function showDetails(id) {
    // Show loading
    $('#detailContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#detailModal').modal('show');
    
    // Load detail data (you can implement this endpoint if needed)
    // For now, just show a placeholder
    setTimeout(() => {
        $('#detailContent').html('<p>Detail data akan ditampilkan di sini</p>');
    }, 1000);
}

function deleteRecord(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            url: '{{ url("admin/fingerprint-attendance") }}/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus data'
                });
            }
        });
    }
}
</script>
@endpush