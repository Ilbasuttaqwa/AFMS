@extends('layouts.user.template')

@push('styles')
<style>
    /* Clean Blue Theme - Form Only */
    .form-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-header {
        background: #2563eb;
        border-radius: 12px 12px 0 0;
        color: white;
        padding: 25px;
    }
    
    .form-body {
        padding: 30px;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }
    
    .form-control-modern {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }
    
    .form-control-modern:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        background-color: white;
    }
    
    .btn-modern {
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-submit {
        background: #2563eb;
        color: white;
    }
    
    .btn-reset {
        background: #6b7280;
        color: white;
    }
    
    .input-group-text {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 8px 0 0 8px;
        font-weight: 600;
        color: #374151;
    }
    
    .input-group .form-control-modern {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }
    
    .alert-modern {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
    }
    
    .employee-search-container {
        position: relative;
    }
    
    #employee_dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        border: 1px solid #e5e7eb;
        border-radius: 0 0 8px 8px;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }
    
    .employee-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    
    .employee-item:hover {
        background-color: #f9fafb;
    }
    
    .employee-item:last-child {
        border-bottom: none;
    }
    
    .employee-item.active {
        background-color: #eff6ff;
        color: #2563eb;
    }
    
    .employee-item.selected {
        background-color: #dcfce7;
        color: #166534;
        transform: scale(0.98);
    }
    
    .employee-item mark {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        padding: 1px 2px;
        border-radius: 2px;
    }
    
    .employee-search-container .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    /* Table Styles */
    .table-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        background: white;
        overflow: hidden;
    }
    
    .table-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 20px 25px;
    }
    
    .table-responsive {
        border-radius: 0 0 12px 12px;
    }
    
    .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        padding: 15px;
        font-size: 0.875rem;
    }
    
    .table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-approved {
        background: #dcfce7;
        color: #166534;
    }
    
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        margin: 0 2px;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-view {
        background: #eff6ff;
        color: #2563eb;
    }
    
    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Form Input Bon -->
            <div class="card form-card mb-4">
                <div class="form-header">
                    <div>
                        <h5 class="text-white mb-1">Input Data Bon</h5>
                        <p class="text-white opacity-8 mb-0">Buat pengajuan bon untuk karyawan</p>
                    </div>
                </div>
                <div class="form-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-modern">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Terdapat kesalahan:</strong>
                            </div>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.bon.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group employee-search-container">
                                    <label for="employee_search" class="form-label">Cari Karyawan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-modern" id="employee_search" 
                                               placeholder="Ketik nama karyawan untuk mencari..." autocomplete="off">
                                    </div>
                                    <div id="employee_dropdown">
                                        <!-- Employee list will be populated here -->
                                    </div>
                                    <input type="hidden" name="pegawai_id" id="selected_employee_id" required>
                                    <small class="text-muted">Pilih karyawan dari hasil pencarian</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date" class="form-control form-control-modern" id="tanggal" name="tanggal" 
                                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah" class="form-label">Jumlah Bon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-money-bill"></i>
                                        </span>
                                        <input type="number" class="form-control form-control-modern" id="jumlah" name="jumlah" 
                                               placeholder="Masukkan jumlah bon" value="{{ old('jumlah') }}" min="1" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-modern" id="keterangan" name="keterangan" 
                                              rows="4" placeholder="Masukkan keterangan bon..." required>{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-reset btn-modern">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-submit btn-modern">
                                <i class="fas fa-save me-2"></i>Simpan Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Bon -->
            <div class="card table-card">
                <div class="table-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">Data Bon Karyawan</h5>
                            <p class="text-muted mb-0">Daftar bon karyawan di lokasi Anda</p>
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-list me-1"></i>
                            Total: {{ $bons->count() }} data
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @if($bons->count() > 0)
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Nama Karyawan</th>
                                    <th width="15%">Jabatan</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Dibuat Oleh</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bons as $index => $bon)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $bon->pegawai->nama_pegawai ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $bon->pegawai->nip ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $bon->pegawai->jabatan->nama_jabatan ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $bon->tanggal->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $bon->tanggal->format('l') }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">
                                                Rp {{ number_format($bon->jumlah, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($bon->status == 'approved')
                                                <span class="badge badge-status badge-approved">
                                                    <i class="fas fa-check me-1"></i>Disetujui
                                                </span>
                                            @elseif($bon->status == 'pending')
                                                <span class="badge badge-status badge-pending">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @else
                                                <span class="badge badge-status badge-rejected">
                                                    <i class="fas fa-times me-1"></i>Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $bon->creator->nama_pegawai ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $bon->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('user.bon.show', $bon) }}" 
                                                   class="btn btn-action btn-view" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($bon->status == 'pending')
                                                    <a href="{{ route('user.bon.edit', $bon) }}" 
                                                       class="btn btn-action btn-edit" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('user.bon.destroy', $bon) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus bon ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-action btn-delete" 
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted">Belum ada data bon</h6>
                            <p class="text-muted mb-0">Silakan tambahkan bon baru menggunakan form di atas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let employees = [];
    let selectedIndex = -1;
    let searchTimeout;

    // Load employees data on page load
    loadEmployees();

    function loadEmployees() {
        $.ajax({
            url: '{{ route("user.bon.employees") }}',
            method: 'GET',
            success: function(data) {
                employees = data;
            },
            error: function(xhr, status, error) {
                console.error('Error loading employees:', error);
                showAlert('Gagal memuat data karyawan: ' + error, 'danger');
            }
        });
    }

    // Employee search functionality with debounce
    $('#employee_search').on('input', function() {
        const query = $(this).val().trim();
        const dropdown = $('#employee_dropdown');
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Reset selected employee when typing
        $('#selected_employee_id').val('');
        
        if (query.length < 1) {
            dropdown.hide();
            selectedIndex = -1;
            return;
        }

        // Add debounce to prevent too many searches
        searchTimeout = setTimeout(function() {
            searchEmployees(query, dropdown);
        }, 300);
    });

    function searchEmployees(query, dropdown) {
        const searchQuery = query.toLowerCase();
        
        // Filter employees based on name or NIP
        const filtered = employees.filter(emp => {
            const name = emp.nama_pegawai ? emp.nama_pegawai.toLowerCase() : '';
            const nip = emp.nip ? emp.nip.toLowerCase() : '';
            return name.includes(searchQuery) || nip.includes(searchQuery);
        });

        if (filtered.length > 0) {
            let html = '';
            filtered.slice(0, 10).forEach((emp, index) => { // Limit to 10 results
                const highlightedName = highlightMatch(emp.nama_pegawai, query);
                const highlightedNip = highlightMatch(emp.nip, query);
                
                html += `
                    <div class="employee-item" data-id="${emp.id}" data-name="${emp.nama_pegawai}" data-index="${index}">
                        <div class="fw-bold">${highlightedName}</div>
                        <small class="text-muted">
                            NIP: ${highlightedNip} | 
                            ${emp.jabatan?.nama_jabatan || 'N/A'} | 
                            ${emp.cabang?.nama_cabang || 'N/A'}
                        </small>
                    </div>
                `;
            });
            
            if (filtered.length > 10) {
                html += `<div class="employee-item text-center text-muted">
                    <small>Dan ${filtered.length - 10} karyawan lainnya...</small>
                </div>`;
            }
            
            dropdown.html(html).show();
            selectedIndex = -1;
        } else {
            dropdown.html(`
                <div class="employee-item text-center text-muted">
                    <i class="fas fa-search me-2"></i>
                    Tidak ada karyawan ditemukan untuk "${query}"
                </div>
            `).show();
        }
    }

    function highlightMatch(text, query) {
        if (!text || !query) return text || '';
        
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-warning">$1</mark>');
    }

    // Handle employee selection
    $(document).on('click', '.employee-item', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        if (id && name) {
            $('#employee_search').val(name);
            $('#selected_employee_id').val(id);
            $('#employee_dropdown').hide();
            selectedIndex = -1;
            
            // Show success feedback
            $(this).addClass('selected');
            setTimeout(() => {
                $(this).removeClass('selected');
            }, 200);
            
            // Focus next input
            $('#tanggal').focus();
        }
    });

    // Enhanced keyboard navigation
    $('#employee_search').on('keydown', function(e) {
        const items = $('.employee-item[data-id]'); // Only items with data-id
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (items.length > 0) {
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (items.length > 0) {
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items.length > selectedIndex) {
                items.eq(selectedIndex).click();
            }
        } else if (e.key === 'Escape') {
            $('#employee_dropdown').hide();
            selectedIndex = -1;
            $(this).blur();
        } else if (e.key === 'Tab') {
            $('#employee_dropdown').hide();
            selectedIndex = -1;
        }
    });

    function updateSelection(items) {
        $('.employee-item').removeClass('active');
        if (selectedIndex >= 0 && items.length > selectedIndex) {
            const activeItem = items.eq(selectedIndex);
            activeItem.addClass('active');
            
            // Scroll into view if needed
            const dropdown = $('#employee_dropdown');
            const itemTop = activeItem.position().top;
            const itemHeight = activeItem.outerHeight();
            const dropdownHeight = dropdown.height();
            const scrollTop = dropdown.scrollTop();
            
            if (itemTop < 0) {
                dropdown.scrollTop(scrollTop + itemTop);
            } else if (itemTop + itemHeight > dropdownHeight) {
                dropdown.scrollTop(scrollTop + itemTop + itemHeight - dropdownHeight);
            }
        }
    }

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.employee-search-container').length) {
            $('#employee_dropdown').hide();
            selectedIndex = -1;
        }
    });

    // Show dropdown when focusing on search input
    $('#employee_search').on('focus', function() {
        if ($(this).val().length >= 1 && employees.length > 0) {
            searchEmployees($(this).val(), $('#employee_dropdown'));
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        const selectedEmployeeId = $('#selected_employee_id').val();
        const searchValue = $('#employee_search').val().trim();
        
        if (!selectedEmployeeId || !searchValue) {
            e.preventDefault();
            showAlert('Silakan pilih karyawan dari hasil pencarian', 'warning');
            $('#employee_search').focus();
            return false;
        }
    });

    function showAlert(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of form body
        $('.form-body').prepend(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Format currency input
    $('#jumlah').on('input', function() {
        let value = $(this).val().replace(/[^\d]/g, '');
        if (value) {
            $(this).val(parseInt(value));
        }
    });

    // Form validation
    $('form').on('submit', function(e) {
        if (!$('#selected_employee_id').val()) {
            e.preventDefault();
            alert('Silakan pilih karyawan terlebih dahulu');
            $('#employee_search').focus();
            return false;
        }
    });
});
</script>
@endpush
@endsection