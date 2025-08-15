@extends('layouts.admin.template')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: middle;
        }
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        .avatar-lg {
            width: 48px;
            height: 48px;
        }
        .stats-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .stats-card.manager {
            border-left-color: #dc3545;
        }
        .stats-card.admin {
            border-left-color: #198754;
        }
        .stats-card.total {
            border-left-color: #0d6efd;
        }

    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"></span> Manajemen Akun Karyawan
        </h4>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stats-card total">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $users->count() }}</h3>
                                <p class="text-muted mb-0">Total Akun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card manager">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-danger rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-check text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $users->where('role', 'manager')->count() }}</h3>
                                <p class="text-muted mb-0">Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card admin">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-lg bg-success rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-check text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0 fw-bold">{{ $users->where('role', 'admin')->count() }}</h3>
                                <p class="text-muted mb-0">Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-funnel me-2"></i>Filter & Pencarian
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-shield me-1"></i>Role
                                </label>
                                <select class="form-select" id="roleFilter">
                                    <option value="">Semua Role</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Cabang
                                </label>
                                <select class="form-select" id="cabangFilter">
                                    <option value="">Semua Cabang</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->nama_cabang }}">{{ $cabang->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-check-circle me-1"></i>Status Verifikasi
                                </label>
                                <select class="form-select" id="verifikasiFilter">
                                    <option value="">Semua Status</option>
                                    <option value="verified">Terverifikasi</option>
                                    <option value="unverified">Belum Verifikasi</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                        <i class="bi bi-plus-circle me-1"></i>Tambah Akun
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Account Modal -->
        <div class="modal fade" id="addAccountModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>Tambah Akun Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('manajemen-akun.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-person me-1"></i>Nama Lengkap
                                        </label>
                                        <input type="text" class="form-control @error('nama_pegawai') is-invalid @enderror"
                                               name="nama_pegawai" value="{{ old('nama_pegawai') }}"
                                               placeholder="Masukkan nama lengkap" required>
                                        @error('nama_pegawai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-1"></i>Email
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}"
                                               placeholder="contoh@email.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-key me-1"></i>Password
                                        </label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               name="password" placeholder="Minimal 8 karakter" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-key-fill me-1"></i>Konfirmasi Password
                                        </label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                               placeholder="Ulangi password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt me-1"></i>Lokasi/Cabang
                                        </label>
                                        <select class="form-select @error('id_cabang') is-invalid @enderror" name="id_cabang" required>
                                            <option value="">Pilih Cabang</option>
                                            @foreach($cabangs as $cabang)
                                                <option value="{{ $cabang->id }}" {{ old('id_cabang') == $cabang->id ? 'selected' : '' }}>
                                                    {{ $cabang->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_cabang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-shield me-1"></i>Role
                                        </label>
                                        <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                            <option value="">Pilih Role</option>
                                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i>Buat Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Data Akun -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>Data Akun Karyawan
                </h5>
                <span class="badge bg-light text-primary">{{ $users->count() }} Akun</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="akunTable" class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">
                                    <i class="bi bi-person me-1"></i>Nama Lengkap
                                </th>
                                <th style="width: 20%;">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </th>
                                <th class="text-center" style="width: 10%;">
                                    <i class="bi bi-key me-1"></i>Password
                                </th>
                                <th class="text-center" style="width: 15%;">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi/Cabang
                                </th>
                                <th class="text-center" style="width: 10%;">
                                    <i class="bi bi-shield me-1"></i>Role
                                </th>
                                <th class="text-center" style="width: 12%;">
                                    <i class="bi bi-calendar me-1"></i>Dibuat
                                </th>
                                <th class="text-center" style="width: 8%;">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr class="align-middle">
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->nama_pegawai }}</div>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $user->email }}</div>
                                        <small class="text-muted">
                                            @if($user->email_verified_at)
                                                <i class="bi bi-check-circle text-success"></i> Terverifikasi
                                            @else
                                                <i class="bi bi-x-circle text-warning"></i> Belum Verifikasi
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#passwordModal{{ $user->id }}"
                                                title="Lihat Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if($user->cabang)
                                            <span class="badge bg-info-subtle text-info border border-info">
                                                <i class="bi bi-building me-1"></i>{{ $user->cabang->nama_cabang }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Belum Ditentukan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->role === 'manager')
                                            <span class="badge bg-danger-subtle text-danger border border-danger">
                                                <i class="bi bi-shield-check me-1"></i>Manager
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-person-check me-1"></i>Admin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-medium">{{ $user->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}"
                                                    title="Edit Akun">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}"
                                                        title="Hapus Akun">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        title="Tidak dapat menghapus akun sendiri" disabled>
                                                    <i class="bi bi-shield-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Password Modal -->
                                <div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">
                                                    <i class="bi bi-key me-2"></i>Password: {{ $user->nama_pegawai }}
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Password Terenkripsi</strong>
                                                </div>
                                                <p class="text-muted mb-2">Password disimpan dalam bentuk hash untuk keamanan.</p>
                                                <div class="bg-light p-3 rounded">
                                                    <small class="font-monospace text-break">{{ Str::limit($user->password, 50) }}...</small>
                                                </div>
                                                <hr>
                                                <p class="text-sm text-muted mb-0">
                                                    <i class="bi bi-shield-lock me-1"></i>
                                                    Gunakan fitur "Reset Password" untuk mengubah password.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Akun: {{ $user->nama_pegawai }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('manajemen-akun.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Nama</label>
                                                                <input type="text" class="form-control" name="nama_pegawai"
                                                                       value="{{ $user->nama_pegawai }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                       value="{{ $user->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                                                <input type="password" class="form-control" name="password">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Konfirmasi Password</label>
                                                                <input type="password" class="form-control" name="password_confirmation">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Lokasi/Cabang</label>
                                                                <select class="form-select" name="id_cabang" required>
                                                                    @foreach($cabangs as $cabang)
                                                                        <option value="{{ $cabang->id }}"
                                                                                {{ $user->id_cabang == $cabang->id ? 'selected' : '' }}>
                                                                            {{ $cabang->nama_cabang }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label">Role</label>
                                                                <select class="form-select" name="role" required>
                                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                                    <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                @if($user->id !== auth()->id())
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus akun <strong>{{ $user->nama_pegawai }}</strong> dengan role <span class="badge bg-{{ $user->role == 'manager' ? 'danger' : 'success' }}">{{ ucfirst($user->role) }}</span>?</p>
                                                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('manajemen-akun.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash me-1"></i>Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#akunTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [3, 7] }, // Disable sorting on password and action columns
                    { searchable: false, targets: [3, 7] }, // Disable search on password and action columns
                    { className: "text-center", targets: [0, 3, 4, 5, 6, 7] } // Center align specific columns
                ],
                order: [[6, 'desc']], // Sort by created date (newest first)
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                drawCallback: function() {
                    // Re-initialize tooltips after table redraw
                    $('[title]').tooltip();
                }
            });

            // Custom filter functions
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var roleFilter = $('#roleFilter').val();
                    var cabangFilter = $('#cabangFilter').val();
                    var verifikasiFilter = $('#verifikasiFilter').val();

                    var role = data[5]; // Role column
                    var cabang = data[4]; // Cabang column
                    var verifikasi = data[2]; // Email column (contains verification status)

                    // Role filter
                    if (roleFilter && !role.toLowerCase().includes(roleFilter.toLowerCase())) {
                        return false;
                    }

                    // Cabang filter
                    if (cabangFilter && !cabang.includes(cabangFilter)) {
                        return false;
                    }

                    // Verifikasi filter
                    if (verifikasiFilter) {
                        if (verifikasiFilter === 'verified' && !verifikasi.includes('Terverifikasi')) {
                            return false;
                        }
                        if (verifikasiFilter === 'unverified' && !verifikasi.includes('Belum Verifikasi')) {
                            return false;
                        }
                    }

                    return true;
                }
            );

            // Filter event handlers
            $('#roleFilter, #cabangFilter, #verifikasiFilter').on('change', function() {
                table.draw();
            });

            // Reset filter
            $('#resetFilter').on('click', function() {
                $('#roleFilter, #cabangFilter, #verifikasiFilter').val('');
                table.search('').draw();
            });

            // Initialize tooltips
            $('[title]').tooltip();

            // Auto-open modal if there are validation errors
            @if($errors->any())
                $('#addAccountModal').modal('show');
            @endif
        });
     </script>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endsection
