@extends('layouts.admin.template')

@section('title', 'Laporan Potongan Gaji')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Laporan Potongan Gaji
                    </h4>
                    <small>Laporan akumulasi potongan gaji berdasarkan keterlambatan dan bonus per bulan</small>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('keuangan.laporan-potongan-gaji') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Cari nama karyawan..." value="{{ $search }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select class="form-select" id="bulan" name="bulan">
                                        <option value="01" {{ $bulan == '01' ? 'selected' : '' }}>Januari</option>
                                        <option value="02" {{ $bulan == '02' ? 'selected' : '' }}>Februari</option>
                                        <option value="03" {{ $bulan == '03' ? 'selected' : '' }}>Maret</option>
                                        <option value="04" {{ $bulan == '04' ? 'selected' : '' }}>April</option>
                                        <option value="05" {{ $bulan == '05' ? 'selected' : '' }}>Mei</option>
                                        <option value="06" {{ $bulan == '06' ? 'selected' : '' }}>Juni</option>
                                        <option value="07" {{ $bulan == '07' ? 'selected' : '' }}>Juli</option>
                                        <option value="08" {{ $bulan == '08' ? 'selected' : '' }}>Agustus</option>
                                        <option value="09" {{ $bulan == '09' ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                                        <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select class="form-select" id="tahun" name="tahun">
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('keuangan.laporan-potongan-gaji') }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle me-1"></i> Reset
                                    </a>
                                    <button type="button" class="btn btn-success" onclick="exportToPDF()">
                                        <i class="bi bi-file-pdf me-1"></i> Export PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Karyawan</h6>
                                    <h4>{{ count($laporanData) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Potongan</h6>
                                    <h4>Rp {{ number_format(array_sum(array_column($laporanData, 'total_potongan')), 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Bonus</h6>
                                    <h4>Rp {{ number_format(array_sum(array_column($laporanData, 'bonus_gaji')), 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h6 class="card-title">Periode</h6>
                                    <h4>{{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="laporanTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Nama Karyawan</th>
                                    <th width="15%">Golongan</th>
                                    <th width="15%">Lokasi (Cabang)</th>
                                    <th width="10%">Keterlambatan</th>
                                    <th width="12%">Potongan Gaji</th>
                                    <th width="12%">Bonus Gaji</th>
                                    <th width="11%">Total Bersih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($laporanData) > 0)
                                    @foreach ($laporanData as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ $data['user']->nama_pegawai }}</strong>
                                            <br><small class="text-muted">{{ $data['user']->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ $data['user']->jabatan->nama_jabatan ?? '-' }}
                                            </span>
                                            <br><small class="text-muted">Gaji Pokok: Rp {{ number_format($data['user']->jabatan->gaji_pokok ?? 0, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $data['user']->cabang->nama_cabang ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($data['keterlambatan'] > 0)
                                                <span class="badge bg-warning text-dark">{{ $data['keterlambatan'] }}x</span>
                                            @else
                                                <span class="badge bg-success">0x</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-danger">
                                                <strong>Rp {{ number_format($data['total_potongan'], 0, ',', '.') }}</strong>
                                            </div>
                                            @if($data['potongan_keterlambatan'] > 0)
                                                <small class="text-muted">Keterlambatan: Rp {{ number_format($data['potongan_keterlambatan'], 0, ',', '.') }}</small><br>
                                            @endif
                                            @if($data['potongan_manual'] > 0)
                                                <small class="text-muted">Manual: Rp {{ number_format($data['potongan_manual'], 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data['bonus_gaji'] > 0)
                                                <span class="text-success">
                                                    <strong>Rp {{ number_format($data['bonus_gaji'], 0, ',', '.') }}</strong>
                                                </span>
                                            @else
                                                <span class="text-muted">Rp 0</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold {{ $data['gaji_bersih'] >= ($data['user']->jabatan->gaji_pokok ?? 0) ? 'text-success' : 'text-warning' }}">
                                                Rp {{ number_format($data['gaji_bersih'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mt-2">Tidak ada data untuk periode yang dipilih</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            @if(count($laporanData) > 0)
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="5" class="text-end">TOTAL:</th>
                                    <th class="text-danger">Rp {{ number_format(array_sum(array_column($laporanData, 'total_potongan')), 0, ',', '.') }}</th>
                                    <th class="text-success">Rp {{ number_format(array_sum(array_column($laporanData, 'bonus_gaji')), 0, ',', '.') }}</th>
                                    <th class="text-primary">Rp {{ number_format(array_sum(array_column($laporanData, 'gaji_bersih')), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    window.print();
}
</script>
@endsection