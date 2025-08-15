# Biometric Integration with Employee Management System

## Fitur yang Telah Diintegrasikan

### 1. Field Device User ID
- **Lokasi**: Tabel `users` kolom `device_user_id`
- **Fungsi**: Menyimpan ID unik pegawai di mesin fingerprint
- **Validasi**: Nullable, string, unique

### 2. Form Management
- **Create Employee**: Form tambah pegawai dengan field Device User ID
- **Edit Employee**: Form edit pegawai dengan field Device User ID
- **Validation**: Validasi unique untuk mencegah duplikasi ID

### 3. Fingerprint Service Enhancement
- **User Lookup**: Pencarian user berdasarkan `device_user_id`
- **Sync Method**: Sinkronisasi data absensi dari mesin fingerprint
- **Error Handling**: Log warning untuk user yang tidak ditemukan

### 4. Artisan Command
- **Command**: `php artisan fingerprint:sync`
- **Options**:
  - `--all`: Sync semua cabang aktif
  - `--cabang=ID`: Sync cabang tertentu
  - Default: Proses record yang belum diproses

### 5. Scheduled Tasks
- **Sync Attendance**: Setiap 15 menit
- **Sync All Branches**: Setiap jam
- **Background**: Berjalan di background tanpa overlap

### 6. UI Enhancements
- **Employee List**: Kolom ID Fingerprint dengan badge status
- **Attendance Report**: Kolom ID Fingerprint dan sumber data
- **Visual Indicators**: Badge untuk status registrasi fingerprint

## Cara Penggunaan

### Menambah Pegawai dengan Fingerprint
1. Buka halaman tambah pegawai
2. Isi data pegawai termasuk Device User ID
3. Device User ID harus unik dan sesuai dengan ID di mesin fingerprint

### Sync Manual
```bash
# Proses record yang belum diproses
php artisan fingerprint:sync

# Sync semua cabang
php artisan fingerprint:sync --all

# Sync cabang tertentu
php artisan fingerprint:sync --cabang=1
```

### Monitoring
- Cek log aplikasi untuk error sync
- Monitor kolom ID Fingerprint di daftar pegawai
- Review laporan absensi untuk sumber data

## Struktur Database

### Tabel users
- `device_user_id`: VARCHAR nullable, unique
- Relasi dengan data absensi melalui field ini

### Tabel fingerprint_attendance
- Tabel sementara untuk data dari mesin fingerprint
- Diproses menjadi data absensi final

## Troubleshooting

### User Tidak Ditemukan
- Pastikan `device_user_id` di database sesuai dengan ID di mesin
- Cek log untuk warning message

### Sync Gagal
- Periksa koneksi ke mesin fingerprint
- Pastikan konfigurasi IP dan port benar
- Cek log error untuk detail masalah