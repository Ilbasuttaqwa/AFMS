@extends('layouts.user.template')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .form-header {
        background: linear-gradient(135deg, #fd7e14 0%, #e55a4e 100%);
        border-radius: 20px 20px 0 0;
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
        color: #344767;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }
    
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }
    
    .form-control-modern:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
        background-color: white;
    }
    
    .btn-modern {
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #fd7e14 0%, #e55a4e 100%);
        color: white;
    }
    
    .btn-reset {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #e9ecef;
        border-radius: 12px 0 0 12px;
        font-weight: 600;
        color: #495057;
    }
    
    .input-group .form-control-modern {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    
    .alert-modern {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
    }
    
    .employee-info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 15px;
        padding: 20px;
        margin-top: 15px;
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
        border: 2px solid #e9ecef;
        border-top: none;
        border-radius: 0 0 12px 12px;
        background: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        max-height: 200px;
        overflow-y: auto;
    }

    .employee-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }

    .employee-item:hover {
        background-color: #f8f9fa;
    }

    .employee-item.active {
        background-color: #e9ecef;
    }
    
    .employee-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card form-card mb-4">
                <div class="form-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('user.bon.index') }}" class="btn btn-light btn-modern me-3">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <h5 class="text-white mb-1">Edit Bon</h5>
                            <p class="text-white opacity-8 mb-0">Perbarui data pengajuan bon</p>
                        </div>
                    </div>
                </div>
                <div class="form-body">
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

                    <form action="{{ route('user.bon.update', $bon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group employee-search-container">
                                    <label for="employee_search" class="form-label">Cari Karyawan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control-modern" id="employee_search" 
                                               placeholder="Ketik nama karyawan untuk mencari..." autocomplete="off"
                                               value="{{ $bon->pegawai->nama_pegawai }}">
                                    </div>
                                    <div id="employee_dropdown" class="dropdown-menu w-100" style="display: none;">
                                        <!-- Employee list will be populated here -->
                                    </div>
                                    <input type="hidden" name="pegawai_id" id="pegawai_id" value="{{ old('pegawai_id', $bon->pegawai_id) }}">
                                    @error('pegawai_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="selected_employee_info" style="{{ $bon->pegawai ? '' : 'display: none;' }}">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Karyawan Terpilih</label>
                                    <div class="employee-info-card">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2"><i class="fas fa-user me-2 text-primary"></i><strong>Nama:</strong> <span id="selected_name">{{ $bon->pegawai->nama_pegawai ?? '-' }}</span></p>
                                                <p class="mb-2"><i class="fas fa-briefcase me-2 text-primary"></i><strong>Jabatan:</strong> <span id="selected_jabatan">{{ $bon->pegawai->jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i><strong>Lokasi:</strong> <span id="selected_cabang">{{ $bon->pegawai->cabang->nama_cabang ?? 'Tidak ada cabang' }}</span></p>
                                                <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i><strong>Email:</strong> <span id="selected_email">{{ $bon->pegawai->email ?? '-' }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control-modern @error('tanggal') is-invalid @enderror" 
                                           id="tanggal" name="tanggal" value="{{ old('tanggal', $bon->tanggal->format('Y-m-d')) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control-modern @error('jumlah') is-invalid @enderror" 
                                               id="jumlah" name="jumlah" value="{{ old('jumlah', $bon->jumlah) }}" 
                                               placeholder="Masukkan jumlah bon" min="0" step="1000" required>
                                    </div>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                    <textarea class="form-control-modern @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="4" 
                                              placeholder="Masukkan keterangan bon" required>{{ old('keterangan', $bon->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-reset btn-modern">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-submit btn-modern">
                                <i class="fas fa-save me-2"></i>Update Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let employees = [];
    
    // Format number input
    $('#jumlah').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
    });
    
    // Employee search functionality
    $('#employee_search').on('input', function() {
        const searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(function() {
                searchEmployees(searchTerm);
            }, 300);
        } else {
            $('#employee_dropdown').hide();
        }
    });
    
    // Search employees via AJAX
    function searchEmployees(searchTerm) {
        $.ajax({
            url: '{{ route("user.bon.employees") }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(data) {
                employees = data;
                displayEmployees(data);
            },
            error: function() {
                console.error('Error searching employees');
            }
        });
    }
    
    // Display employees in dropdown
    function displayEmployees(employees) {
        const dropdown = $('#employee_dropdown');
        dropdown.empty();
        
        if (employees.length === 0) {
            dropdown.append('<div class="dropdown-item text-muted">Tidak ada karyawan ditemukan</div>');
        } else {
            employees.forEach(function(employee) {
                const item = $(`
                    <div class="dropdown-item employee-item" data-id="${employee.id}" style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${employee.nama_pegawai}</strong>
                                <br>
                                <small class="text-muted">${employee.jabatan ? employee.jabatan.nama_jabatan : 'Tidak ada jabatan'}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">${employee.cabang ? employee.cabang.nama_cabang : 'Tidak ada cabang'}</small>
                            </div>
                        </div>
                    </div>
                `);
                dropdown.append(item);
            });
        }
        
        dropdown.show();
    }
    
    // Handle employee selection
    $(document).on('click', '.employee-item', function() {
        const employeeId = $(this).data('id');
        const employee = employees.find(emp => emp.id === employeeId);
        
        if (employee) {
            selectEmployee(employee);
        }
    });
    
    // Select employee
    function selectEmployee(employee) {
        $('#pegawai_id').val(employee.id);
        $('#employee_search').val(employee.nama_pegawai);
        $('#employee_dropdown').hide();
        
        // Update selected employee info
        $('#selected_name').text(employee.nama_pegawai);
        $('#selected_jabatan').text(employee.jabatan ? employee.jabatan.nama_jabatan : 'Tidak ada jabatan');
        $('#selected_cabang').text(employee.cabang ? employee.cabang.nama_cabang : 'Tidak ada cabang');
        $('#selected_email').text(employee.email);
        
        $('#selected_employee_info').show();
    }
    
    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#employee_search, #employee_dropdown').length) {
            $('#employee_dropdown').hide();
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        if (!$('#pegawai_id').val()) {
            e.preventDefault();
            alert('Silakan pilih karyawan terlebih dahulu');
            $('#employee_search').focus();
        }
    });
});
</script>
@endpush
@endsection