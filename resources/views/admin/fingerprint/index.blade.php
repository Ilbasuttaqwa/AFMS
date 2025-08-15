@extends('layouts.admin.template')

@section('title', 'Manajemen Fingerprint X100-C')

@section('content')
<style>
    .fingerprint-card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s;
    }
    
    .fingerprint-card:hover {
        box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
        transform: translateY(-2px);
    }
    
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    
    .status-active {
        background-color: #1cc88a;
        animation: pulse 2s infinite;
    }
    
    .status-inactive {
        background-color: #e74a3b;
    }
    
    .status-unknown {
        background-color: #f6c23e;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .sync-button {
        transition: all 0.3s;
    }
    
    .sync-button:hover {
        transform: scale(1.05);
    }
    
    .config-form {
        background: #f8f9fc;
        border-radius: 0.35rem;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .attendance-table {
        font-size: 0.875rem;
    }
    
    .attendance-table th {
        background-color: #f8f9fc;
        border-top: none;
        font-weight: 600;
        color: #5a5c69;
    }
    
    .badge-role {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fingerprint text-primary mr-2"></i>
            Manajemen Fingerprint X100-C
        </h1>
        @if(auth()->user()->role === 'manager')
        <button class="btn btn-primary sync-button" onclick="syncAllDevices()">
            <i class="fas fa-sync-alt mr-2"></i>
            Sync Semua Cabang
        </button>
        @endif
    </div>

    <!-- Role Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>{{ auth()->user()->role === 'manager' ? 'Manager' : 'Admin' }}:</strong>
        @if(auth()->user()->role === 'manager')
            Anda dapat melihat dan mengelola semua data absensi dari seluruh cabang.
        @else
            Anda hanya dapat melihat data absensi dari cabang: <strong>{{ auth()->user()->cabang->nama_cabang ?? 'Tidak ada cabang' }}</strong>
        @endif
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>

    <!-- Sync Status Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cabang Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalActiveBranches">
                                {{ $cabangs->where('fingerprint_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sync Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todaySync">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Status Koneksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="connectionStatus">
                                <span class="status-indicator status-unknown"></span>
                                Checking...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wifi fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Last Sync
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="lastSyncTime">
                                Loading...
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Configuration Cards -->
    <div class="row">
        @foreach($cabangs as $cabang)
        <div class="col-lg-6 mb-4">
            <div class="card fingerprint-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building mr-2"></i>
                        {{ $cabang->nama_cabang }}
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" onclick="testConnection({{ $cabang->id }})">
                                <i class="fas fa-plug fa-sm fa-fw mr-2 text-gray-400"></i>
                                Test Koneksi
                            </a>
                            <a class="dropdown-item" href="#" onclick="syncBranch({{ $cabang->id }})">
                                <i class="fas fa-sync fa-sm fa-fw mr-2 text-gray-400"></i>
                                Sync Manual
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Status Info -->
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted">Status:</small><br>
                            @if($cabang->fingerprint_active)
                                <span class="status-indicator status-active"></span>
                                <span class="text-success font-weight-bold">Aktif</span>
                            @else
                                <span class="status-indicator status-inactive"></span>
                                <span class="text-danger font-weight-bold">Tidak Aktif</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted">IP Address:</small><br>
                            <code>{{ $cabang->fingerprint_ip ?? 'Belum dikonfigurasi' }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted">Port:</small><br>
                            <code>{{ $cabang->fingerprint_port ?? '4370' }}</code>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted">Last Sync:</small><br>
                            <span class="text-info">
                                {{ $cabang->last_sync ? \Carbon\Carbon::parse($cabang->last_sync)->diffForHumans() : 'Belum pernah' }}
                            </span>
                        </div>
                    </div>

                    <!-- Configuration Form -->
                    <div class="config-form">
                        <form id="configForm{{ $cabang->id }}" onsubmit="updateConfig(event, {{ $cabang->id }})">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="fingerprint_ip{{ $cabang->id }}">IP Address</label>
                                    <input type="text" class="form-control" 
                                           id="fingerprint_ip{{ $cabang->id }}" 
                                           name="fingerprint_ip" 
                                           value="{{ $cabang->fingerprint_ip }}"
                                           placeholder="192.168.1.100" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fingerprint_port{{ $cabang->id }}">Port</label>
                                    <input type="number" class="form-control" 
                                           id="fingerprint_port{{ $cabang->id }}" 
                                           name="fingerprint_port" 
                                           value="{{ $cabang->fingerprint_port ?? 4370 }}"
                                           min="1" max="65535" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="fingerprint_active{{ $cabang->id }}" 
                                           name="fingerprint_active"
                                           {{ $cabang->fingerprint_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="fingerprint_active{{ $cabang->id }}">
                                        Aktifkan Fingerprint
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan Konfigurasi
                                </button>
                                <button type="button" class="btn btn-info btn-sm mr-2" 
                                        onclick="testConnection({{ $cabang->id }})">
                                    <i class="fas fa-plug mr-1"></i>
                                    Test Koneksi
                                </button>
                                <button type="button" class="btn btn-success btn-sm sync-button" 
                                        onclick="syncBranch({{ $cabang->id }})">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Sync Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Attendance Data -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clock mr-2"></i>
                Data Absensi Terbaru
            </h6>
            <div class="dropdown no-arrow">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshAttendanceData()">
                    <i class="fas fa-refresh mr-1"></i>
                    Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered attendance-table" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Cabang</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody">
                        <tr>
                            <td colspan="7" class="text-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#attendanceTable').DataTable({
        "pageLength": 25,
        "order": [[ 2, "desc" ]],
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
        }
    });
    
    // Load initial data
    loadSyncStatus();
    loadAttendanceData();
    
    // Auto refresh every 5 minutes
    setInterval(function() {
        loadSyncStatus();
        loadAttendanceData();
    }, 300000);
});

function updateConfig(event, cabangId) {
    event.preventDefault();
    
    const form = document.getElementById(`configForm${cabangId}`);
    const formData = new FormData(form);
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(`/admin/fingerprint/config/${cabangId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.success,
                timer: 2000,
                showConfirmButton: false
            });
            loadSyncStatus();
        } else {
            throw new Error(data.error || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function testConnection(cabangId) {
    const ipInput = document.getElementById(`fingerprint_ip${cabangId}`);
    const portInput = document.getElementById(`fingerprint_port${cabangId}`);
    
    if (!ipInput.value || !portInput.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Harap isi IP Address dan Port terlebih dahulu'
        });
        return;
    }
    
    Swal.fire({
        title: 'Testing Connection...',
        text: 'Mohon tunggu, sedang menguji koneksi ke mesin fingerprint',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('/admin/fingerprint/test-connection', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            ip: ipInput.value,
            port: parseInt(portInput.value)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Koneksi Berhasil!',
                text: data.success
            });
        } else {
            throw new Error(data.error || 'Koneksi gagal');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Koneksi Gagal!',
            text: error.message
        });
    });
}

function syncBranch(cabangId) {
    Swal.fire({
        title: 'Konfirmasi Sync',
        text: 'Apakah Anda yakin ingin melakukan sinkronisasi data absensi?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Sync!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            performSync(cabangId);
        }
    });
}

function performSync(cabangId) {
    Swal.fire({
        title: 'Syncing Data...',
        text: 'Mohon tunggu, sedang melakukan sinkronisasi data absensi',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/fingerprint/sync/${cabangId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sync Berhasil!',
                text: data.success
            });
            loadSyncStatus();
            loadAttendanceData();
        } else {
            throw new Error(data.error || 'Sync gagal');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Sync Gagal!',
            text: error.message
        });
    });
}

function syncAllDevices() {
    Swal.fire({
        title: 'Konfirmasi Sync Semua',
        text: 'Apakah Anda yakin ingin melakukan sinkronisasi semua cabang?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Sync Semua!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            performSyncAll();
        }
    });
}

function performSyncAll() {
    Swal.fire({
        title: 'Syncing All Devices...',
        text: 'Mohon tunggu, sedang melakukan sinkronisasi semua cabang',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('/admin/fingerprint/sync-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sync Semua Berhasil!',
                text: data.success
            });
            loadSyncStatus();
            loadAttendanceData();
        } else {
            throw new Error(data.error || 'Sync gagal');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Sync Gagal!',
            text: error.message
        });
    });
}

function loadSyncStatus() {
    fetch('/admin/fingerprint/sync-status')
    .then(response => response.json())
    .then(data => {
        // Update status indicators
        const activeCount = data.filter(item => item.fingerprint_active).length;
        document.getElementById('totalActiveBranches').textContent = activeCount;
        
        // Update last sync time
        if (data.length > 0) {
            const latestSync = data.reduce((latest, current) => {
                if (!current.last_sync_raw) return latest;
                if (!latest || new Date(current.last_sync_raw) > new Date(latest)) {
                    return current.last_sync;
                }
                return latest;
            }, null);
            
            document.getElementById('lastSyncTime').textContent = latestSync || 'Belum pernah';
        }
        
        // Update connection status
        const connectionStatus = document.getElementById('connectionStatus');
        if (activeCount > 0) {
            connectionStatus.innerHTML = '<span class="status-indicator status-active"></span>Online';
        } else {
            connectionStatus.innerHTML = '<span class="status-indicator status-inactive"></span>Offline';
        }
    })
    .catch(error => {
        console.error('Error loading sync status:', error);
    });
}

function loadAttendanceData() {
    fetch('/admin/fingerprint/attendance-data')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('attendanceTableBody');
        tbody.innerHTML = '';
        
        if (data.data && data.data.length > 0) {
            data.data.forEach(attendance => {
                const row = `
                    <tr>
                        <td>${attendance.user ? attendance.user.nama_pegawai : 'N/A'}</td>
                        <td>${attendance.user && attendance.user.cabang ? attendance.user.cabang.nama_cabang : 'N/A'}</td>
                        <td>${attendance.tanggal_absen}</td>
                        <td>${attendance.jam_masuk || '-'}</td>
                        <td>${attendance.jam_keluar || '-'}</td>
                        <td>
                            <span class="badge badge-${getStatusColor(attendance.status)}">
                                ${attendance.status}
                            </span>
                        </td>
                        <td>${attendance.keterangan || '-'}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data absensi</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error loading attendance data:', error);
        document.getElementById('attendanceTableBody').innerHTML = 
            '<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>';
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'hadir': return 'success';
        case 'terlambat': return 'warning';
        case 'sakit': return 'info';
        case 'izin': return 'secondary';
        case 'alpha': return 'danger';
        default: return 'light';
    }
}

function refreshAttendanceData() {
    loadAttendanceData();
    loadSyncStatus();
    
    // Show refresh feedback
    const refreshBtn = event.target;
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
    }, 2000);
}
</script>
@endsection