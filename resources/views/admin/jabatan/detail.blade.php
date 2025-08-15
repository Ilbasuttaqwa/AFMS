@extends('layouts.admin.template')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .config-item {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0 0.25rem 0.25rem 0;
        }
        .penalty-range {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.25rem;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">  </span> Detail {{ $jabatan->nama_jabatan }}</h4>
        <a href="{{ route('jabatan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- UNTUK TOAST NOTIFIKASI --}}
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="validationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                <div class="me-auto fw-semibold">Success</div>
                <small>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Data sudah ada!
            </div>
        </div>
    </div>

    <!-- Toast Untuk Success -->
    @if (session('success'))
        <div class="bs-toast toast toast-placement-ex m-2 bg-success top-0 end-0 fade show toast-custom" role="alert"
            aria-live="assertive" aria-atomic="true" id="toastSuccess">
            <div class="toast-header">
                <i class="bi bi-check-circle me-2"></i>
                <div class="me-auto fw-semibold">Success</div>
                <small>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Toast Untuk Error --}}
    @if (session('error'))
        <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0 fade show toast-custom" role="alert"
            aria-live="assertive" aria-atomic="true" id="toastError">
            <div class="toast-header">
                <i class="bx bx-error me-2"></i>
                <div class="me-auto fw-semibold">Error</div>
                <small>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Toast Untuk Danger --}}
    @if (session('danger'))
        <div class="bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0 fade show toast-custom" role="alert"
            aria-live="assertive" aria-atomic="true" id="toastError">
            <div class="toast-header">
                <i class="bx bx-error me-2"></i>
                <div class="me-auto fw-semibold">Danger</div>
                <small>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('danger') }}
            </div>
        </div>
    @endif

    {{-- Toast Untuk Warning --}}
    @if (session('warning'))
        <div class="bs-toast toast toast-placement-ex m-2 bg-warning top-0 end-0 fade show toast-custom" role="alert"
            aria-live="assertive" aria-atomic="true" id="toastError">
            <div class="toast-header">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <div class="me-auto fw-semibold">Warning</div>
                <small>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    <div class="row">
        <!-- 1. Pengaturan Jam Kerja -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-clock me-2"></i>Pengaturan Jam Kerja</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="config-item">
                                <h6 class="fw-bold text-primary"><i class="bi bi-sunrise me-1"></i>Jam Masuk Pagi</h6>
                                <p class="mb-0 fs-4">{{ $jabatan->jam_masuk ?? '08:00' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="config-item">
                                <h6 class="fw-bold text-primary"><i class="bi bi-sun me-1"></i>Jam Masuk Siang</h6>
                                <p class="mb-0 fs-4">{{ $jabatan->jam_masuk_siang ?? '13:00' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="config-item">
                                <h6 class="fw-bold text-warning"><i class="bi bi-hourglass-split me-1"></i>Toleransi Keterlambatan</h6>
                                <p class="mb-0 fs-4">{{ $jabatan->toleransi_keterlambatan ?? 15 }} menit</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="config-item">
                                <h6 class="fw-bold text-success"><i class="bi bi-currency-dollar me-1"></i>Gaji Pokok</h6>
                                <p class="mb-0 fs-4">Rp {{ number_format($jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editJamKerjaModal">
                            <i class="bi bi-pencil me-1"></i>Edit Pengaturan Jam Kerja
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Fitur Potongan Keterlambatan -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Potongan Keterlambatan (Akumulasi Bulanan)</h5>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Sistem Akumulasi:</strong> Potongan dihitung berdasarkan total menit keterlambatan dalam satu bulan
                    </div>

                    <h6 class="fw-bold mb-3">Keterlambatan Masuk Pagi:</h6>
                    <div class="mb-3">
                        <span class="penalty-range">0-30 menit: Tidak ada potongan</span>
                        <span class="penalty-range">31-45 menit: Rp {{ number_format($jabatan->potongan_31_45 ?? 10000, 0, ',', '.') }}</span>
                        <span class="penalty-range">46-60 menit: Rp {{ number_format($jabatan->potongan_46_60 ?? 15000, 0, ',', '.') }}</span>
                        <span class="penalty-range">61-100 menit: Rp {{ number_format($jabatan->potongan_61_100 ?? 25000, 0, ',', '.') }}</span>
                        <span class="penalty-range">101-200 menit: Rp {{ number_format($jabatan->potongan_101_200 ?? 50000, 0, ',', '.') }}</span>
                        <span class="penalty-range">200+ menit: Rp {{ number_format($jabatan->potongan_200_plus ?? 100000, 0, ',', '.') }}</span>
                    </div>

                    <h6 class="fw-bold mb-3">Keterlambatan Masuk Setelah Istirahat:</h6>
                    <div class="mb-3">
                        <span class="penalty-range">0-30 menit: Tidak ada potongan</span>
                        <span class="penalty-range">31-45 menit: Rp {{ number_format($jabatan->potongan_31_45 ?? 10000, 0, ',', '.') }}</span>
                        <span class="penalty-range">46-60 menit: Rp {{ number_format($jabatan->potongan_46_60 ?? 15000, 0, ',', '.') }}</span>
                        <span class="penalty-range">61-100 menit: Rp {{ number_format($jabatan->potongan_61_100 ?? 25000, 0, ',', '.') }}</span>
                        <span class="penalty-range">101-200 menit: Rp {{ number_format($jabatan->potongan_101_200 ?? 50000, 0, ',', '.') }}</span>
                        <span class="penalty-range">200+ menit: Rp {{ number_format($jabatan->potongan_200_plus ?? 100000, 0, ',', '.') }}</span>
                    </div>

                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPotonganModal">
                        <i class="bi bi-pencil me-1"></i>Edit Pengaturan Potongan
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. Fitur Libur Karyawan -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2"></i>Pengaturan Libur</h5>
                    </div>
                    <div class="config-item">
                        <h6 class="fw-bold text-info"><i class="bi bi-calendar-month me-1"></i>Jatah Libur per Bulan</h6>
                        <p class="mb-0 fs-4">{{ $jabatan->jatah_libur_per_bulan ?? 2 }} hari</p>
                    </div>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editLiburModal">
                        <i class="bi bi-pencil me-1"></i>Edit Jatah Libur
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. Fitur Potongan Libur -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-dash-circle me-2"></i>Potongan Libur Berlebih</h5>
                    </div>
                    <div class="config-item">
                        <h6 class="fw-bold text-danger"><i class="bi bi-currency-dollar me-1"></i>Denda per Hari</h6>
                        <p class="mb-0 fs-4">Rp {{ number_format($jabatan->denda_per_hari_libur ?? 50000, 0, ',', '.') }}</p>
                    </div>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editDendaLiburModal">
                        <i class="bi bi-pencil me-1"></i>Edit Denda Libur
                    </button>
                </div>
            </div>
        </div>

        <!-- 5. Bonus -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-gift me-2"></i>Bonus Tidak Ambil Libur</h5>
                    </div>
                    <div class="config-item">
                        <h6 class="fw-bold text-success"><i class="bi bi-currency-dollar me-1"></i>Bonus per Hari</h6>
                        <p class="mb-0 fs-4">Rp {{ number_format($jabatan->bonus_tidak_libur ?? 25000, 0, ',', '.') }}</p>
                    </div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editBonusModal">
                        <i class="bi bi-pencil me-1"></i>Edit Bonus
                    </button>
                </div>
            </div>
        </div>

        <!-- 6. Pengaturan Minimal Waktu Absen -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Pengaturan Minimal Waktu Absen</h5>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Jika karyawan absen sebelum waktu minimal yang ditentukan, akan dikenakan potongan gaji.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="config-item">
                                <h6 class="fw-bold text-primary"><i class="bi bi-sunrise me-1"></i>Minimal Absen Pagi</h6>
                                <p class="mb-0 fs-4">{{ $jabatan->minimal_absen_pagi ?? '07:00' }}</p>
                                <small class="text-muted">Absen sebelum jam ini akan dikenakan potongan</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="config-item">
                                <h6 class="fw-bold text-primary"><i class="bi bi-sun me-1"></i>Minimal Absen Siang</h6>
                                <p class="mb-0 fs-4">{{ $jabatan->minimal_absen_siang ?? '12:00' }}</p>
                                <small class="text-muted">Absen sebelum jam ini akan dikenakan potongan</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="config-item">
                                <h6 class="fw-bold text-danger"><i class="bi bi-currency-dollar me-1"></i>Potongan Absen Awal</h6>
                                <p class="mb-0 fs-4">Rp {{ number_format($jabatan->potongan_absen_awal ?? 10000, 0, ',', '.') }}</p>
                                <small class="text-muted">Potongan per kejadian absen terlalu awal</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMinimalAbsenModal">
                            <i class="bi bi-pencil me-1"></i>Edit Pengaturan Minimal Absen
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- 7. Laporan Gaji -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2"></i>Laporan Gaji Golongan {{ $jabatan->nama_jabatan }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="laporanGajiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Cabang</th>
                                    <th>Gaji Pokok</th>
                                    <th>Bonus</th>
                                    <th>Potongan</th>
                                    <th>Total Gaji</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pegawai as $karyawan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $karyawan->nama_pegawai }}</td>
                                    <td>{{ $karyawan->cabang->nama_cabang ?? '-' }}</td>
                                    <td>Rp {{ number_format($jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $totalBonus = $bonusGaji->where('id_user', $karyawan->id)->sum('jumlah_bonus');
                                        @endphp
                                        Rp {{ number_format($totalBonus, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $totalPotongan = $potonganGaji->where('id_user', $karyawan->id)->sum('jumlah_potongan');
                                        @endphp
                                        Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $totalGaji = ($jabatan->gaji_pokok ?? 0) + $totalBonus - $totalPotongan;
                                        @endphp
                                        <strong>Rp {{ number_format($totalGaji, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="detailGaji({{ $karyawan->id }})">
                                            <i class="bi bi-eye"></i> Detail Gaji
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. Data Karyawan di Golongan -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Data Karyawan di Golongan {{ $jabatan->nama_jabatan }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="karyawanTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Lokasi</th>
                                    <th>Email</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pegawai as $karyawan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial bg-primary rounded-circle">
                                                    {{ substr($karyawan->nama_pegawai, 0, 1) }}
                                                </div>
                                            </div>
                                            <strong>{{ $karyawan->nama_pegawai }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $karyawan->cabang->nama_cabang ?? '-' }}</td>
                                    <td>{{ $karyawan->email }}</td>
                                    <td>{{ $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('pegawai.show', $karyawan->id) }}">
                                                    <i class="bx bx-show me-1"></i> Lihat Detail
                                                </a>
                                                <a class="dropdown-item" href="{{ route('pegawai.edit', $karyawan->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="detailRiwayat({{ $karyawan->id }}, '{{ $karyawan->nama_pegawai }}')" data-bs-toggle="modal" data-bs-target="#riwayatModal">
                                                    <i class="bx bx-history me-1"></i> Detail Riwayat
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 9. Data Bon -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Data Bon Karyawan</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBonModal">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Bon
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="bonTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jumlah Bon</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bonData as $bon)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bon->pegawai->nama_pegawai }}</td>
                                    <td>Rp {{ number_format($bon->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $bon->keterangan }}</td>
                                    <td>{{ $bon->tanggal->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $bon->status == 'lunas' ? 'success' : 'warning' }}">
                                            {{ ucfirst($bon->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="editBon({{ $bon->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteBon({{ $bon->id }}, '{{ $bon->pegawai->nama_pegawai }}', {{ $bon->jumlah }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be included here -->
@include('admin.jabatan.modals.edit-jam-kerja')
@include('admin.jabatan.modals.edit-potongan')
@include('admin.jabatan.modals.edit-libur')
@include('admin.jabatan.modals.edit-denda-libur')
@include('admin.jabatan.modals.edit-bonus')
@include('admin.jabatan.modals.edit-minimal-absen')

@include('admin.jabatan.modals.bon-modal')
@include('admin.jabatan.modals.riwayat-karyawan')

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
$(document).ready(function() {

    
    $('#laporanGajiTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    $('#bonTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    $('#karyawanTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});

function detailGaji(userId) {
    // Get employee data
    const employeeRow = $(`button[onclick="detailGaji(${userId})"]`).closest('tr');
    const employeeName = employeeRow.find('td:nth-child(2)').text().trim();
    const basicSalary = employeeRow.find('td:nth-child(4)').text().trim();
    const bonus = employeeRow.find('td:nth-child(5)').text().trim();
    const deduction = employeeRow.find('td:nth-child(6)').text().trim();
    const totalSalary = employeeRow.find('td:nth-child(7)').text().trim();
    
    // Create detail modal content
    const modalContent = `
        <div class="modal fade" id="detailGajiModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i>Detail Gaji ${employeeName}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6"><strong>Gaji Pokok:</strong></div>
                            <div class="col-6">${basicSalary}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6"><strong>Total Bonus:</strong></div>
                            <div class="col-6 text-success">${bonus}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6"><strong>Total Potongan:</strong></div>
                            <div class="col-6 text-danger">${deduction}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6"><strong>Total Gaji:</strong></div>
                            <div class="col-6"><strong>${totalSalary}</strong></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#detailGajiModal').remove();
    
    // Add modal to body and show
    $('body').append(modalContent);
    $('#detailGajiModal').modal('show');
}

function editBon(bonId) {
    // Find the bon data from the table row
    const bonRow = $(`button[onclick="editBon(${bonId})"]`).closest('tr');
    const employeeName = bonRow.find('td:nth-child(2)').text().trim();
    const jumlahText = bonRow.find('td:nth-child(3)').text().trim();
    const jumlah = jumlahText.replace(/[^0-9]/g, ''); // Extract numbers only
    const keterangan = bonRow.find('td:nth-child(4)').text().trim();
    const tanggalText = bonRow.find('td:nth-child(5)').text().trim();
    const status = bonRow.find('td:nth-child(6) .badge').text().trim().toLowerCase();
    
    // Convert date format from d/m/Y to Y-m-d
    const dateParts = tanggalText.split('/');
    const tanggal = `${dateParts[2]}-${dateParts[1].padStart(2, '0')}-${dateParts[0].padStart(2, '0')}`;
    
    // Find employee ID by name
    let pegawaiId = '';
    $('#edit_pegawai_id option').each(function() {
        if ($(this).text().includes(employeeName)) {
            pegawaiId = $(this).val();
            return false;
        }
    });
    
    // Set form values
    $('#editBonForm').attr('action', `{{ url('admin/jabatan/bon') }}/${bonId}`);
    $('#edit_pegawai_id').val(pegawaiId);
    $('#edit_jumlah').val(jumlah);
    $('#edit_keterangan').val(keterangan);
    $('#edit_tanggal').val(tanggal);
    $('#edit_status').val(status === 'lunas' ? 'paid' : status);
    
    // Show modal
    $('#editBonModal').modal('show');
}

function detailRiwayat(userId, userName) {
    $('#riwayatKaryawanModal').modal('show');
    $('#modalUserName').text(userName);
    $('#modalUserId').val(userId);
    
    // Load default data (keterlambatan)
    loadKeterlambatanData(userId);
}

function deleteBon(id, namaKaryawan, jumlah) {
    if (confirm(`Hapus data bon ${namaKaryawan} sebesar Rp ${new Intl.NumberFormat('id-ID').format(jumlah)}?`)) {
        $.ajax({
            url: `{{ url('admin/jabatan/bon') }}/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Data bon berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus data bon!');
            }
        });
    }
}
</script>
@endpush
