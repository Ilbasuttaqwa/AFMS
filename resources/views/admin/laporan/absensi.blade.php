@extends('layouts.admin.template')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Laporan Absensi</h4>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('laporan.absensi') }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select" name="year" id="year">
                                @php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 5;
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month" class="form-label">Month</label>
                            <select class="form-select" name="month" id="month">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cabang" class="form-label">Lokasi</label>
                            <select class="form-select" name="cabang" id="cabang">
                                <option value="">Semua Lokasi</option>
                                @foreach (\App\Models\Cabang::all() as $cabang)
                                    <option value="{{ $cabang->id }}" {{ request('cabang') == $cabang->id ? 'selected' : '' }}>
                                        {{ $cabang->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary form-control" type="submit">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    @if (!$absensi->isEmpty())
                        <div class="col-4">
                            <button id="lihatPdfButtonAbsensi" class="btn btn-secondary form-control" data-bs-toggle="modal" data-bs-target="#pdfModal">
                                Lihat PDF
                            </button>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('laporan.absensi', ['download_pdf' => true, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}" class="btn btn-info form-control">
                                Buat PDF
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('laporan.absensi', ['download_excel' => true, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_selesai' => request('tanggal_selesai')]) }}" class="btn btn-success form-control">
                                Buat EXCEL
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>ID Fingerprint</th>
                                <th>Lokasi</th>
                                <th>Kategori/Golongan</th>
                                <th>Morning Check-in</th>
                                <th>Evening Check-in</th>
                                <th>Sumber Data</th>
                                <th>Late Penalty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->user->nama_pegawai ?? "Tidak Ada Data"}}</td>
                                    <td>
                                        @if($item->user->device_user_id)
                                            <span class="badge bg-info">{{ $item->user->device_user_id }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->user->cabang->nama_cabang ?? "Tidak Ada Data"}}</td>
                                    <td>{{ $item->user->jabatan->nama_jabatan ?? "Tidak Ada Data"}}</td>
                                    <td>{{ $item->jam_masuk ?? "Tidak Ada Data"}}</td>
                                    <td>{{ $item->jam_masuk_sore ?? "Tidak Ada Data"}}</td>
                                    <td>
                                        @if(str_contains($item->keterangan ?? '', 'fingerprint'))
                                            <span class="badge bg-success">
                                                <i class="bi bi-fingerprint"></i> Fingerprint
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-pencil"></i> Manual
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $lateMinutes = 0;
                                            if ($item->jam_masuk) {
                                                $startTime = \Carbon\Carbon::createFromFormat('H:i', '07:30');
                                                $checkInTime = \Carbon\Carbon::parse($item->jam_masuk);

                                                if ($checkInTime->gt($startTime)) {
                                                    $lateMinutes = $checkInTime->diffInMinutes($startTime);
                                                }
                                            }
                                        @endphp

                                        @if($lateMinutes > 0)
                                            <span class="badge bg-danger">{{ $lateMinutes }} menit</span>
                                        @else
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Data absensi tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
