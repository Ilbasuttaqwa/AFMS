<!-- Modal Riwayat Karyawan -->
<div class="modal fade" id="riwayatModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-clock-history me-2"></i>Riwayat Karyawan - <span id="namaKaryawan"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Filter Periode -->
                    <div class="col-12 mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Dari Tanggal:</label>
                                        <input type="date" class="form-control" id="tanggalMulai" value="<?php echo e(date('Y-m-01')); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Sampai Tanggal:</label>
                                        <input type="date" class="form-control" id="tanggalSelesai" value="<?php echo e(date('Y-m-t')); ?>">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary" onclick="loadRiwayatData()">
                                            <i class="bi bi-search me-1"></i>Filter Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs untuk berbagai riwayat -->
                <ul class="nav nav-tabs" id="riwayatTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="keterlambatan-tab" data-bs-toggle="tab" data-bs-target="#keterlambatan" type="button" role="tab">
                            <i class="bi bi-clock me-1"></i>Riwayat Keterlambatan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="libur-berlebih-tab" data-bs-toggle="tab" data-bs-target="#libur-berlebih" type="button" role="tab">
                            <i class="bi bi-calendar-x me-1"></i>Libur Berlebih
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tidak-libur-tab" data-bs-toggle="tab" data-bs-target="#tidak-libur" type="button" role="tab">
                            <i class="bi bi-calendar-check me-1"></i>Tidak Ambil Libur
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="riwayatTabContent">
                    <!-- Tab Riwayat Keterlambatan -->
                    <div class="tab-pane fade show active" id="keterlambatan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabelKeterlambatan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Seharusnya</th>
                                        <th>Durasi Terlambat</th>
                                        <th>Potongan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="dataKeterlambatan">
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Riwayat Libur Berlebih -->
                    <div class="tab-pane fade" id="libur-berlebih" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabelLiburBerlebih">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Jatah Libur</th>
                                        <th>Libur Diambil</th>
                                        <th>Libur Berlebih</th>
                                        <th>Denda per Hari</th>
                                        <th>Total Denda</th>
                                        <th>Detail Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="dataLiburBerlebih">
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Riwayat Tidak Ambil Libur -->
                    <div class="tab-pane fade" id="tidak-libur" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabelTidakLibur">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Jatah Libur</th>
                                        <th>Libur Diambil</th>
                                        <th>Sisa Libur</th>
                                        <th>Bonus per Hari</th>
                                        <th>Total Bonus</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="dataTidakLibur">
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-success" onclick="exportRiwayat()">
                    <i class="bi bi-download me-1"></i>Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentKaryawanId = null;

function detailRiwayat(karyawanId, namaKaryawan) {
    currentKaryawanId = karyawanId;
    document.getElementById('namaKaryawan').textContent = namaKaryawan;
    
    // Load initial data
    loadRiwayatData();
}

function loadRiwayatData() {
    if (!currentKaryawanId) return;
    
    const tanggalMulai = document.getElementById('tanggalMulai').value;
    const tanggalSelesai = document.getElementById('tanggalSelesai').value;
    
    // Load data based on active tab
    const activeTab = document.querySelector('.nav-link.active').id;
    
    if (activeTab === 'keterlambatan-tab') {
        loadKeterlambatanData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    } else if (activeTab === 'libur-berlebih-tab') {
        loadLiburBerlebihData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    } else if (activeTab === 'tidak-libur-tab') {
        loadTidakLiburData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    }
}

function loadKeterlambatanData(karyawanId, tanggalMulai, tanggalSelesai) {
    $.ajax({
        url: '<?php echo e(route("jabatan.riwayat-keterlambatan")); ?>',
        method: 'GET',
        data: {
            user_id: karyawanId,
            start_date: tanggalMulai,
            end_date: tanggalSelesai
        },
        success: function(data) {
            let tbody = $('#dataKeterlambatan');
            tbody.empty();
            
            if (data.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center">Tidak ada data keterlambatan</td></tr>');
            } else {
                data.forEach(function(item) {
                    tbody.append(`
                        <tr>
                            <td>${item.no}</td>
                            <td>${item.tanggal}</td>
                            <td>${item.jam_masuk}</td>
                            <td>${item.jam_seharusnya}</td>
                            <td>${item.durasi_terlambat}</td>
                            <td>${item.potongan}</td>
                            <td>${item.keterangan}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading keterlambatan data:', xhr);
            $('#dataKeterlambatan').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

function loadLiburBerlebihData(karyawanId, tanggalMulai, tanggalSelesai) {
    $.ajax({
        url: '<?php echo e(route("jabatan.riwayat-libur-berlebih")); ?>',
        method: 'GET',
        data: {
            user_id: karyawanId,
            start_date: tanggalMulai,
            end_date: tanggalSelesai
        },
        success: function(data) {
            let tbody = $('#dataLiburBerlebih');
            tbody.empty();
            
            if (data.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data libur berlebih</td></tr>');
            } else {
                data.forEach(function(item) {
                    tbody.append(`
                        <tr>
                            <td>${item.no}</td>
                            <td>${item.bulan}</td>
                            <td>${item.jatah_libur}</td>
                            <td>${item.libur_diambil}</td>
                            <td>${item.libur_berlebih}</td>
                            <td>${item.denda_per_hari}</td>
                            <td>${item.total_denda}</td>
                            <td>${item.detail_tanggal}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading libur berlebih data:', xhr);
            $('#dataLiburBerlebih').html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

function loadTidakLiburData(karyawanId, tanggalMulai, tanggalSelesai) {
    $.ajax({
        url: '<?php echo e(route("jabatan.riwayat-tidak-libur")); ?>',
        method: 'GET',
        data: {
            user_id: karyawanId,
            start_date: tanggalMulai,
            end_date: tanggalSelesai
        },
        success: function(data) {
            let tbody = $('#dataTidakLibur');
            tbody.empty();
            
            if (data.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data bonus tidak libur</td></tr>');
            } else {
                data.forEach(function(item) {
                    tbody.append(`
                        <tr>
                            <td>${item.no}</td>
                            <td>${item.bulan}</td>
                            <td>${item.jatah_libur}</td>
                            <td>${item.libur_diambil}</td>
                            <td>${item.sisa_libur}</td>
                            <td>${item.bonus_per_hari}</td>
                            <td>${item.total_bonus}</td>
                            <td>${item.status}</td>
                        </tr>
                    `);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading tidak libur data:', xhr);
            $('#dataTidakLibur').html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

function exportRiwayat() {
    if (!currentKaryawanId) return;
    
    const activeTab = document.querySelector('.nav-link.active').id;
     const userName = document.getElementById('modalUserName').textContent;
    const tanggalMulai = document.getElementById('tanggalMulai').value;
    const tanggalSelesai = document.getElementById('tanggalSelesai').value;
    
    let exportType = '';
    let routeName = '';
    
    if (activeTab === 'keterlambatan-tab') {
        exportType = 'Keterlambatan';
        routeName = 'jabatan.riwayat-keterlambatan';
    } else if (activeTab === 'libur-berlebih-tab') {
        exportType = 'Libur Berlebih';
        routeName = 'jabatan.riwayat-libur-berlebih';
    } else if (activeTab === 'tidak-libur-tab') {
        exportType = 'Tidak Ambil Libur';
        routeName = 'jabatan.riwayat-tidak-libur';
    }
    
    // Create export URL with parameters
    const exportUrl = `<?php echo e(url('admin/jabatan')); ?>/${routeName.split('.')[1]}?user_id=${currentKaryawanId}&start_date=${tanggalMulai}&end_date=${tanggalSelesai}&export=excel`;
    
    // Open export URL in new window
    window.open(exportUrl, '_blank');
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Export Berhasil',
        text: `Data ${exportType} untuk ${userName} sedang diunduh`,
        timer: 2000,
        showConfirmButton: false
    });
}
// Tab switching handlers
$('#keterlambatan-tab').on('click', function() {
    if (currentKaryawanId) {
        const tanggalMulai = document.getElementById('tanggalMulai').value;
        const tanggalSelesai = document.getElementById('tanggalSelesai').value;
        loadKeterlambatanData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    }
});

$('#libur-berlebih-tab').on('click', function() {
    if (currentKaryawanId) {
        const tanggalMulai = document.getElementById('tanggalMulai').value;
        const tanggalSelesai = document.getElementById('tanggalSelesai').value;
        loadLiburBerlebihData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    }
});

$('#tidak-libur-tab').on('click', function() {
    if (currentKaryawanId) {
        const tanggalMulai = document.getElementById('tanggalMulai').value;
        const tanggalSelesai = document.getElementById('tanggalSelesai').value;
        loadTidakLiburData(currentKaryawanId, tanggalMulai, tanggalSelesai);
    }
});
</script><?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/admin/jabatan/modals/riwayat-karyawan.blade.php ENDPATH**/ ?>