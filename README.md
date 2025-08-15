# HRMS Pro - Human Resource Management System

HRMS Pro adalah platform manajemen sumber daya manusia yang komprehensif, dirancang khusus untuk mengoptimalkan operasional perusahaan dalam mengelola karyawan, sistem penggajian, kehadiran, dan proses rekrutmen dengan teknologi fingerprint terintegrasi.

---

## 🚀 Fitur Unggulan

### Modul Administrator

| Modul                    | Fungsi Utama                                                                       |
| ------------------------ | ---------------------------------------------------------------------------------- |
| **Employee Management**  | Sistem pengelolaan data pegawai lengkap dengan profil dan dokumentasi digital     |
| **Payroll System**       | Kalkulasi gaji otomatis dengan bonus, potongan, dan laporan komprehensif          |
| **Recruitment Portal**   | Platform rekrutmen terintegrasi dari posting lowongan hingga seleksi kandidat     |
| **Attendance Tracking**  | Monitoring kehadiran real-time dengan integrasi fingerprint scanner               |
| **Report Analytics**     | Dashboard analitik dengan visualisasi data dan export multi-format                |
| **Document Management**  | Penyimpanan dan pengelolaan dokumen karyawan yang aman dan terorganisir           |

### Portal Karyawan

-   ✅ Check-in/Check-out digital dengan verifikasi fingerprint
-   📋 Pengajuan cuti dan izin dengan approval workflow
-   💰 Akses slip gaji dan riwayat pembayaran personal
-   📊 Dashboard personal dengan statistik kehadiran

---

## 🛠️ Tech Stack

### Backend Framework
-   **Laravel 11** - Modern PHP framework dengan arsitektur MVC yang robust
-   **MySQL 8.0** - Database engine untuk performa optimal dan keamanan tinggi

### Frontend Technologies
-   **Blade Templates** - Server-side rendering untuk UI yang responsif
-   **Bootstrap 5** - CSS framework untuk desain yang modern dan mobile-first
-   **jQuery & AJAX** - Interaksi dinamis dan real-time updates

### Package Dependencies
-   **Laravel Excel** - Import/export data dalam berbagai format spreadsheet
-   **SweetAlert2** - Notifikasi dan dialog interaktif yang elegan
-   **Laravel Notification** - Sistem notifikasi multi-channel
-   **Carbon** - Manipulasi tanggal dan waktu yang powerful

## 📦 Installation Guide

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM (opsional untuk asset compilation)

### Setup Instructions

1. **Download Project**
   ```bash
   # Clone repository
   git clone [your-repository-url]
   cd hrms-pro
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install  # jika menggunakan asset compilation
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Buat database MySQL baru
   - Update konfigurasi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hrms_pro
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Migration & Seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

7. **Access Application**
   - URL: `http://localhost:8000`
   - Default Admin: admin@hrms.com / password

---

## 🔧 Development Notes

### Useful Commands
```bash
# Clear application cache
php artisan cache:clear

# Clear view cache
php artisan view:clear

# Run database migrations
php artisan migrate

# Interactive shell
php artisan tinker
```

### System Requirements
- Minimum RAM: 512MB
- Storage: 1GB free space
- Web Server: Apache/Nginx
- SSL Certificate (recommended for production)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

**HRMS Pro** - Transforming Human Resource Management with Modern Technology! 🚀
