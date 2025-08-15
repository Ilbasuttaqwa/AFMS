@extends('layouts.admin.template')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
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
        });
    </script>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Keuangan /</span> Potongan Gaji</h4>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Filter Karyawan</h5>
                <form action="{{ route('keuangan.potongan-gaji') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="jabatan" class="form-label">Filter Golongan</label>
                            <select class="form-select" id="jabatan" name="jabatan">
                                <option value="">-- Semua Golongan --</option>
                            @foreach ($jabatan as $jab)
                                <option value="{{ $jab->id }}" {{ request('jabatan') == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cabang" class="form-label">Filter Lokasi</label>
                            <select class="form-select" id="cabang" name="cabang">
                                <option value="">-- Semua Lokasi --</option>
                            @foreach ($cabang as $cab)
                                <option value="{{ $cab->id }}" {{ request('cabang') == $cab->id ? 'selected' : '' }}>{{ $cab->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i> Cari</button>
                        <a href="{{ route('keuangan.potongan-gaji') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="fw-bold py-3">Tabel Potongan Gaji Karyawan</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Golongan</th>
                                <th>Lokasi</th>
                                <th>Gaji Pokok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->nama_pegawai }}</td>
                                    <td>{{ $user->jabatan->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $user->cabang->nama_cabang ?? '-' }}</td>
                                    <td>Rp {{ number_format($user->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" class="btn rounded-pill btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#potonganModal{{ $user->id }}"
                                                style="padding-left: 20px; padding-right: 20px; padding-top: 7px; padding-bottom: 7px">
                                            <i class="bi bi-dash-circle" data-bs-toggle="tooltip"
                                               data-bs-offset="0,4" data-bs-placement="top"
                                               data-bs-html="true" title="Potong Gaji"></i>
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

<!-- Modal Tambah Potongan -->
@if(isset($users) && count($users) > 0)
    @foreach ($users as $user)
    <div class="modal fade" id="potonganModal{{ $user->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-dash-circle me-2"></i>Tambah Potongan Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('keuangan.store-potongan-gaji') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" value="{{ $user->nama_pegawai }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Potongan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="jumlah_potongan" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bulan/Tahun</label>
                            <input type="month" name="bulan_tahun" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan alasan potongan gaji..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-save me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection
