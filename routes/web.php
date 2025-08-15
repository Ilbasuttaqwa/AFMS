<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\BonController;

use App\Http\Controllers\FingerprintAttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ManajemenAkunController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\RekrutmenController;
use App\Http\Controllers\SakitIzinController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// API Routes for Fingerprint Device (No Auth Required)
Route::post('/api/fingerprint/attendance', [FingerprintAttendanceController::class, 'receiveAttendance'])->name('api.fingerprint.attendance');

// Auth::routes();

Auth::routes([
    'register' => false,
]);


Route::group(['prefix' => 'admin', 'middleware' => ['auth', isAdmin::class]], function () {
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Route Role Access
    Route::get('role', [App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
    Route::get('role/create', [App\Http\Controllers\RoleController::class, 'create'])->name('role.create');
    Route::post('role', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
    Route::get('role/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');
    Route::put('role/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');
    Route::delete('role/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('role.destroy');

    // Route Jabatan
    // Route::resource('jabatan', JabatanController::class);s
    //Route Jabatan Tanpa Resource
    Route::get('jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('jabatan/create', [JabatanController::class, 'create'])->name('jabatan.create');
    Route::post('jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('jabatan/{id}', [JabatanController::class, 'show'])->name('jabatan.show');
    Route::get('jabatan/{id}/detail', [JabatanController::class, 'detail'])->name('jabatan.detail');
    Route::get('jabatan/{id}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
    
    // Route Jabatan Detail Features
    Route::put('jabatan/{id}/jam-kerja', [JabatanController::class, 'updateJamKerja'])->name('jabatan.update-jam-kerja');
    Route::put('jabatan/{id}/potongan', [JabatanController::class, 'updatePotongan'])->name('jabatan.update-potongan');
    Route::put('jabatan/{id}/libur', [JabatanController::class, 'updateLibur'])->name('jabatan.update-libur');
    Route::put('jabatan/{id}/denda-libur', [JabatanController::class, 'updateDendaLibur'])->name('jabatan.update-denda-libur');
    Route::put('jabatan/{id}/bonus', [JabatanController::class, 'updateBonus'])->name('jabatan.update-bonus');
    Route::put('jabatan/{id}/minimal-absen', [JabatanController::class, 'updateMinimalAbsen'])->name('jabatan.updateMinimalAbsen');

    
    // Route Bon Management
    Route::post('jabatan/bon', [JabatanController::class, 'storeBon'])->name('jabatan.store-bon');
    Route::put('jabatan/bon/{id}', [JabatanController::class, 'updateBon'])->name('jabatan.update-bon');
    Route::delete('jabatan/bon/{id}', [JabatanController::class, 'destroyBon'])->name('jabatan.destroy-bon');
    
    // Route Employee History Data
    Route::get('jabatan/riwayat-keterlambatan', [JabatanController::class, 'getRiwayatKeterlambatan'])->name('jabatan.riwayat-keterlambatan');
    Route::get('jabatan/riwayat-libur-berlebih', [JabatanController::class, 'getRiwayatLiburBerlebih'])->name('jabatan.riwayat-libur-berlebih');
    Route::get('jabatan/riwayat-tidak-libur', [JabatanController::class, 'getRiwayatTidakLibur'])->name('jabatan.riwayat-tidak-libur');
    



    
    // Route Keuangan

    
    // Route Bonus Gaji
    Route::get('keuangan/bonus-gaji', [App\Http\Controllers\KeuanganController::class, 'bonusGaji'])->name('keuangan.bonus-gaji');
    Route::post('keuangan/bonus-gaji', [App\Http\Controllers\KeuanganController::class, 'storeBonusGaji'])->name('keuangan.store-bonus-gaji');
    
    // Route Potongan Gaji
    Route::get('keuangan/potongan-gaji', [App\Http\Controllers\KeuanganController::class, 'potonganGaji'])->name('keuangan.potongan-gaji');
    Route::post('keuangan/potongan-gaji', [App\Http\Controllers\KeuanganController::class, 'storePotonganGaji'])->name('keuangan.store-potongan-gaji');
    
    // Route Laporan Potongan Gaji
    Route::get('keuangan/laporan-potongan-gaji', [App\Http\Controllers\KeuanganController::class, 'laporanPotonganGaji'])->name('keuangan.laporan-potongan-gaji');
    
    // Route Penggajian Terintegrasi
    Route::get('keuangan/penggajian', [App\Http\Controllers\KeuanganController::class, 'penggajian'])->name('keuangan.penggajian');
    Route::post('keuangan/penggajian', [App\Http\Controllers\KeuanganController::class, 'storePenggajian'])->name('keuangan.store-penggajian');
    
    // Route Detail Keterlambatan
    Route::get('keuangan/detail-keterlambatan/{userId}', [App\Http\Controllers\KeuanganController::class, 'detailKeterlambatan'])->name('keuangan.detail-keterlambatan');
    
    // Route Update Gaji
    Route::post('keuangan/update-gaji', [App\Http\Controllers\KeuanganController::class, 'updateGaji'])->name('keuangan.update-gaji');
    
    // Route Bon Karyawan
    Route::get('keuangan/bon', [App\Http\Controllers\KeuanganController::class, 'bonIndex'])->name('keuangan.bon.index');
    Route::get('keuangan/bon/create', [App\Http\Controllers\KeuanganController::class, 'bonCreate'])->name('keuangan.bon.create');
    Route::post('keuangan/bon', [App\Http\Controllers\KeuanganController::class, 'bonStore'])->name('keuangan.bon.store');
    Route::get('keuangan/bon/{id}', [App\Http\Controllers\KeuanganController::class, 'bonShow'])->name('keuangan.bon.show');
    Route::get('keuangan/bon/{id}/edit', [App\Http\Controllers\KeuanganController::class, 'bonEdit'])->name('keuangan.bon.edit');
    Route::put('keuangan/bon/{id}', [App\Http\Controllers\KeuanganController::class, 'bonUpdate'])->name('keuangan.bon.update');
    Route::delete('keuangan/bon/{id}', [App\Http\Controllers\KeuanganController::class, 'bonDestroy'])->name('keuangan.bon.destroy');

    // Route pegawai
    // Route::resource('pegawai', PegawaiConteroller::class);
    // Route pegawai Tanpa Resource
    Route::get('pegawai/akun', [PegawaiController::class, 'indexAdmin'])->name('pegawai.admin');
    Route::get('pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');
    Route::get('pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::get('pegawai-export', [PegawaiController::class, 'export'])->name('pegawai.export');

    //Route Penggajian
    // Route::resource('penggajian', PenggajianController::class);
    // Route Penggajian Tanpa Resource
    Route::get('penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
    Route::get('penggajian/create', [PenggajianController::class, 'create'])->name('penggajian.create');
    Route::post('penggajian', [PenggajianController::class, 'store'])->name('penggajian.store');
    Route::get('penggajian/{id}', [PenggajianController::class, 'show'])->name('penggajian.show');
    Route::get('penggajian/{id}/edit', [PenggajianController::class, 'edit'])->name('penggajian.edit');
    Route::put('penggajian/{id}', [PenggajianController::class, 'update'])->name('penggajian.update');
    Route::delete('penggajian/{id}', [PenggajianController::class, 'destroy'])->name('penggajian.destroy');

    // Route Bonus
    Route::get('penggajian/bonus', [PenggajianController::class, 'bonus'])->name('penggajian.bonus');
    Route::post('penggajian/bonus', [PenggajianController::class, 'storeBonus'])->name('penggajian.storeBonus');

    // Route Potongan
    Route::get('penggajian/potongan', [PenggajianController::class, 'potongan'])->name('penggajian.potongan');
    Route::post('penggajian/potongan', [PenggajianController::class, 'storePotongan'])->name('penggajian.storePotongan');

    // Route Gaji Golongan
    Route::put('penggajian/golongan/{id}', [PenggajianController::class, 'updateGolongan'])->name('penggajian.updateGolongan');

    // Route Jadwal Kerja
    Route::get('jadwal-kerja', [App\Http\Controllers\JadwalKerjaController::class, 'index'])->name('jadwal-kerja.index');
    Route::put('jadwal-kerja', [App\Http\Controllers\JadwalKerjaController::class, 'update'])->name('jadwal-kerja.update');
    Route::put('penggajian/jadwal', [PenggajianController::class, 'updateJadwal'])->name('penggajian.updateJadwal');

    //Route absensi
    // Route::resource('absensi', AbsensiController::class);
    // Route Absensi
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/fingerprint', [AbsensiController::class, 'fingerprint'])->name('absensi.fingerprint');
    Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::get('absensi/data', [AbsensiController::class, 'getAttendanceData'])->name('absensi.data');
    Route::get('absensi/branches', [AbsensiController::class, 'getBranches'])->name('absensi.branches');
    Route::post('absensi/sync-fingerprint', [AbsensiController::class, 'syncFingerprint'])->name('absensi.sync');
    Route::get('absensi/sync-status', [AbsensiController::class, 'getSyncStatus'])->name('absensi.sync.status');
    Route::get('absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::get('absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');

    //Route rekrutmen
    // Route::resource('rekrutmen', RekrutmenController::class);
    // Route rekrutmen Tanpa Resource
    Route::get('rekrutmen', [RekrutmenController::class, 'index'])->name('rekrutmen.index');
    Route::get('rekrutmen/create', [RekrutmenController::class, 'create'])->name('rekrutmen.create');
    Route::post('rekrutmen', [RekrutmenController::class, 'store'])->name('rekrutmen.store');
    Route::get('rekrutmen/{id}', [RekrutmenController::class, 'show'])->name('rekrutmen.show');
    Route::get('rekrutmen/{id}/edit', [RekrutmenController::class, 'edit'])->name('rekrutmen.edit');
    Route::put('rekrutmen/{id}', [RekrutmenController::class, 'update'])->name('rekrutmen.update');
    Route::delete('rekrutmen/{id}', [RekrutmenController::class, 'destroy'])->name('rekrutmen.destroy');





    //Route berkas
    Route::resource('berkas', BerkasController::class);
    Route::get('berkas', [BerkasController::class, 'index'])->name('berkas.index');
    Route::get('berkas/create', [BerkasController::class, 'create'])->name('berkas.create');
    Route::post('berkas', [BerkasController::class, 'store'])->name('berkas.store');
    Route::get('berkas/{id}', [BerkasController::class, 'show'])->name('berkas.show');
    Route::get('berkas/{id}/edit', [BerkasController::class, 'edit'])->name('berkas.edit');
    Route::put('berkas/{id}', [BerkasController::class, 'update'])->name('berkas.update');
    Route::delete('berkas/{id}', [BerkasController::class, 'destroy'])->name('berkas.destroy');
    Route::get('/berkas/view/{type}/{id}', [BerkasController::class, 'viewFile'])->name('berkas.view');


    //Route laporan
    Route::get('laporan/pegawai', [LaporanController::class, 'pegawai'])->name('laporan.pegawai');
    Route::get('laporan/absensi', [LaporanController::class, 'absensi'])->name('laporan.absensi');

    //Route cabang
    Route::get('cabang', [App\Http\Controllers\CabangController::class, 'index'])->name('cabang.index');
    Route::get('cabang/create', [App\Http\Controllers\CabangController::class, 'create'])->name('cabang.create');
    Route::post('cabang', [App\Http\Controllers\CabangController::class, 'store'])->name('cabang.store');
    Route::get('cabang/{id}', [App\Http\Controllers\CabangController::class, 'show'])->name('cabang.show');
    Route::get('cabang/{id}/edit', [App\Http\Controllers\CabangController::class, 'edit'])->name('cabang.edit');
    Route::put('cabang/{id}', [App\Http\Controllers\CabangController::class, 'update'])->name('cabang.update');
    Route::delete('cabang/{id}', [App\Http\Controllers\CabangController::class, 'destroy'])->name('cabang.destroy');

    // Route Fingerprint Management
    Route::get('fingerprint', [App\Http\Controllers\FingerprintController::class, 'index'])->name('fingerprint.index');
    Route::put('fingerprint/config/{cabang}', [App\Http\Controllers\FingerprintController::class, 'updateConfig'])->name('fingerprint.config.update');
    Route::post('fingerprint/test-connection', [App\Http\Controllers\FingerprintController::class, 'testConnection'])->name('fingerprint.test');
    Route::post('fingerprint/sync/{cabang}', [App\Http\Controllers\FingerprintController::class, 'syncAttendance'])->name('fingerprint.sync');
    Route::post('fingerprint/sync-all', [App\Http\Controllers\FingerprintController::class, 'syncAll'])->name('fingerprint.sync.all');
    Route::get('fingerprint/attendance-data', [App\Http\Controllers\FingerprintController::class, 'getAttendanceData'])->name('fingerprint.attendance.data');
    Route::get('fingerprint/sync-status', [App\Http\Controllers\FingerprintController::class, 'getSyncStatus'])->name('fingerprint.sync.status');

    // Route Fingerprint Attendance
    Route::get('fingerprint-attendance', [App\Http\Controllers\FingerprintAttendanceController::class, 'index'])->name('fingerprint-attendance.index');
    Route::post('fingerprint-attendance/reprocess', [App\Http\Controllers\FingerprintAttendanceController::class, 'reprocess'])->name('fingerprint-attendance.reprocess');
    Route::delete('fingerprint-attendance/{id}', [App\Http\Controllers\FingerprintAttendanceController::class, 'destroy'])->name('fingerprint-attendance.destroy');



    // Route Manajemen Akun Karyawan
    Route::get('manajemen-akun', [App\Http\Controllers\ManajemenAkunController::class, 'index'])->name('manajemen-akun.index');
    Route::post('manajemen-akun', [App\Http\Controllers\ManajemenAkunController::class, 'store'])->name('manajemen-akun.store');
    Route::put('manajemen-akun/{id}', [App\Http\Controllers\ManajemenAkunController::class, 'update'])->name('manajemen-akun.update');
    Route::delete('manajemen-akun/{id}', [App\Http\Controllers\ManajemenAkunController::class, 'destroy'])->name('manajemen-akun.destroy');


    Route::get('jabatan/{id}/schedule-data', [JabatanController::class, 'getScheduleData'])->name('jabatan.schedule-data');


});

// LOGIN GOOGLE
// Route::get('auth/google', [LoginController::class, 'redirectToGoogle']);
// Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('/redirect', [SocialiteController::class, 'redirect'])->name('redirect')->middleware('guest');
Route::get('/callback', [SocialiteController::class, 'callback'])->name('callback')->middleware('guest');
Route::get('/logout', [SocialiteController::class, 'logout'])->name('socialite.logout')->middleware('auth');

Auth::routes();

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    
    // Route Bon untuk Karyawan
    Route::get('bon', [BonController::class, 'index'])->name('user.bon.index');
    Route::get('bon/create', [BonController::class, 'create'])->name('user.bon.create');
    Route::post('bon', [BonController::class, 'store'])->name('user.bon.store');
    Route::get('bon/{bon}', [BonController::class, 'show'])->name('user.bon.show');
    Route::get('bon/{bon}/edit', [BonController::class, 'edit'])->name('user.bon.edit');
    Route::put('bon/{bon}', [BonController::class, 'update'])->name('user.bon.update');
    Route::delete('bon/{bon}', [BonController::class, 'destroy'])->name('user.bon.destroy');
    Route::get('bon/employees/search', [BonController::class, 'getEmployees'])->name('user.bon.employees');
    
    // Route Sakit/Izin untuk Karyawan
    Route::get('sakit-izin', [SakitIzinController::class, 'index'])->name('user.sakit-izin.index');
    Route::get('sakit-izin/create', [SakitIzinController::class, 'create'])->name('user.sakit-izin.create');
    Route::post('sakit-izin', [SakitIzinController::class, 'store'])->name('user.sakit-izin.store');
    Route::get('sakit-izin/{sakitIzin}', [SakitIzinController::class, 'show'])->name('user.sakit-izin.show');
    Route::get('sakit-izin/{sakitIzin}/edit', [SakitIzinController::class, 'edit'])->name('user.sakit-izin.edit');
    Route::put('sakit-izin/{sakitIzin}', [SakitIzinController::class, 'update'])->name('user.sakit-izin.update');
    Route::delete('sakit-izin/{sakitIzin}', [SakitIzinController::class, 'destroy'])->name('user.sakit-izin.destroy');

    // Route absensi dihapus karena sudah ada kalender absensi
    Route::post('/absen-sakit', [WelcomeController::class, 'absenSakit'])->name('welcome.absenSakit');
Route::post('/absen-pulang', [WelcomeController::class, 'absenPulang'])->name('welcome.absenPulang');
Route::get('/absensi-kalender', [WelcomeController::class, 'calendar'])->name('welcome.calendar');
Route::get('/absensi-kalender/data', [WelcomeController::class, 'getCalendarData'])->name('welcome.calendar.data');
// Route::post('/absen-sakit', [WelcomeController::class, 'absenSakit'])->name('welcome.absenSakit');

    Route::get('penggajian', [PenggajianController::class, 'index1'])->name('penggajian.index1');
    Route::get('penggajian/create', [PenggajianController::class, 'create1'])->name('penggajian.create1');
    Route::post('penggajian', [PenggajianController::class, 'store1'])->name('penggajian.store1');
    Route::get('penggajian/{id}', [PenggajianController::class, 'show1'])->name('penggajian.show1');
    Route::get('penggajian/{id}/edit', [PenggajianController::class, 'edit1'])->name('penggajian.edit1');
    Route::put('penggajian/{id}', [PenggajianController::class, 'update1'])->name('penggajian.update1');
    Route::delete('penggajian/{id}', [PenggajianController::class, 'destroy1'])->name('penggajian.destroy1');



    Route::get('/izin-sakit', [WelcomeController::class, 'izinSakit'])->name('izin.sakit');

    // Route Data Karyawan untuk User
    Route::get('karyawan', [App\Http\Controllers\UserKaryawanController::class, 'index'])->name('user.karyawan.index');
    Route::get('karyawan/{id}', [App\Http\Controllers\UserKaryawanController::class, 'show'])->name('user.karyawan.show');
    Route::get('karyawan/attendance/data', [App\Http\Controllers\UserKaryawanController::class, 'getAttendanceData'])->name('user.karyawan.attendance.data');

});
