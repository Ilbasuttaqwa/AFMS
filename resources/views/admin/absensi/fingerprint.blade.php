@extends('layouts.admin.template')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <style>
        /* Custom styles for attendance table */
        .table-sm td {
            padding: 0.25rem;
            vertical-align: middle;
        }
        
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .card-body, .card-body * {
                visibility: visible;
            }
            
            .card-body {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .btn, .dataTables_filter, .dataTables_length, .dataTables_paginate, .dataTables_info {
                display: none !important;
            }
            
            .table-sm td, .table-sm th {
                padding: 0.15rem;
                font-size: 0.7rem;
            }
            
            .table-bordered td, .table-bordered th {
                border: 1px solid #000 !important;
            }
            
            .d-flex.flex-column {
                display: flex !important;
                flex-direction: column !important;
            }
            
            .d-flex.flex-column div {
                line-height: 1;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu Absensi /</span> Fingerprint</h4>

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

        <div class="card">
            <h5 class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Data Absensi Karyawan</span>
                    <div>
                        <i class="bi bi-fingerprint fs-3 text-primary me-2"></i>
                        <span class="text-muted">Sistem absensi fingerprint otomatis</span>
                    </div>
                </div>
            </h5>

            <div class="card-body">
                <form method="GET" action="{{ route('absensi.fingerprint') }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="search-name" class="form-label">Cari Nama Karyawan</label>
                            <input type="text" class="form-control" id="search-name" name="search-name" placeholder="Masukkan nama karyawan..." value="{{ request('search-name') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="search-cabang" class="form-label">Filter Lokasi</label>
                            <select class="form-select" id="search-cabang" name="search-cabang">
                                <option value="">Semua Lokasi</option>
                                @foreach (\App\Models\Cabang::all() as $cab)
                                    <option value="{{ $cab->id }}" {{ request('search-cabang') == $cab->id ? 'selected' : '' }}>{{ $cab->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search-month" class="form-label">Bulan</label>
                            <select class="form-select" id="search-month" name="search-month">
                                <option value="">Semua Bulan</option>
                                <option value="01" {{ request('search-month', date('m')) == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ request('search-month', date('m')) == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ request('search-month', date('m')) == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ request('search-month', date('m')) == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('search-month', date('m')) == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ request('search-month', date('m')) == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ request('search-month', date('m')) == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ request('search-month', date('m')) == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ request('search-month', date('m')) == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('search-month', date('m')) == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ request('search-month', date('m')) == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('search-month', date('m')) == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search-year" class="form-label">Tahun</label>
                            <select class="form-select" id="search-year" name="search-year">
                                <option value="">Semua Tahun</option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 5;
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ request('search-year', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('absensi.fingerprint') }}" class="btn btn-secondary">Reset</a>
                            <button type="button" class="btn btn-success btn-print"><i class="bi bi-printer"></i> Cetak</button>
                            <button type="button" class="btn btn-info btn-excel"><i class="bi bi-file-excel"></i> Export Excel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table for pegawai Data -->
            <div class="table-responsive">
                @php
                    $selectedMonth = request('search-month', date('m'));
                    $selectedYear = request('search-year', date('Y'));
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
                    $monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
                @endphp

                <table class="table table-bordered table-sm" id="example">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle text-center">Nama Karyawan</th>
                            <th colspan="{{ $daysInMonth }}" class="text-center">{{ $monthName }} {{ $selectedYear }}</th>
                        </tr>
                        <tr>
                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dayDate = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $dayOfWeek = date('N', strtotime($dayDate));
                                    $isWeekend = ($dayOfWeek >= 6); // 6 = Saturday, 7 = Sunday
                                    $cellClass = $isWeekend ? 'bg-light' : '';
                                @endphp
                                <th class="text-center {{ $cellClass }}" style="min-width: 60px;">{{ $day }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawai as $data)
                            <tr>
                                <td>{{ $data->nama_pegawai }}</td>
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                                        $dayOfWeek = date('N', strtotime($date));
                                        $isWeekend = ($dayOfWeek >= 6); // 6 = Saturday, 7 = Sunday
                                        $cellClass = $isWeekend ? 'bg-light' : '';
                                        
                                        $absensi = \App\Models\Absensi::where('id_user', $data->id)
                                            ->where('tanggal_absen', $date)
                                            ->first();
                                    @endphp
                                    <td class="text-center p-1 {{ $cellClass }}" style="font-size: 0.85rem;">
                                        <div class="d-flex flex-column">
                                            <div>
                                                {{ $absensi ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}
                                            </div>
                                            <div>
                                                {{ $absensi && $absensi->jam_masuk_sore ? \Carbon\Carbon::parse($absensi->jam_masuk_sore)->format('H:i') : '-' }}
                                            </div>
                                        </div>
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with minimal configuration for attendance view
            var table = new DataTable('#example', {
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true
            });
            
            // Search by name functionality - instant filtering
            $('#search-name').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Auto-submit form when filters change
            $('#search-cabang, #search-month, #search-year').on('change', function() {
                $(this).closest('form').submit();
            });
            
            // Add print functionality
            $('.btn-print').on('click', function() {
                window.print();
            });
            
            // Add export to Excel functionality
            $('.btn-excel').on('click', function() {
                var params = new URLSearchParams(window.location.search);
                window.location.href = '{{ route("absensi.export") }}?' + params.toString();
            });
            
            // Highlight today's column
            highlightToday();
            
            function highlightToday() {
                var today = new Date();
                var currentMonth = (today.getMonth() + 1).toString().padStart(2, '0');
                var currentYear = today.getFullYear().toString();
                var currentDay = today.getDate();
                
                var selectedMonth = $('#search-month').val() || currentMonth;
                var selectedYear = $('#search-year').val() || currentYear;
                
                if (selectedMonth === currentMonth && selectedYear === currentYear) {
                    // Find the column for today and highlight it
                    $('th:contains("' + currentDay + '")').addClass('bg-warning-subtle');
                    $('td:nth-child(' + (currentDay + 1) + ')').addClass('bg-warning-subtle');
                }
            }
        });
    </script>
@endpush