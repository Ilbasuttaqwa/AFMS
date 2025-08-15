-- =====================================================
-- DATABASE STRUCTURE FOR DASHBOARD FINGERPRINT PROJECT
-- Generated from Laravel Migrations
-- =====================================================

-- Set SQL mode and charset
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS `dashboard_fingerprint` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `dashboard_fingerprint`;

-- =====================================================
-- TABLE: jabatans (Job Positions)
-- =====================================================
CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(255) NOT NULL,
  `gaji_pokok` decimal(15,2) DEFAULT 0.00,
  `batas_keterlambatan` int(11) DEFAULT 15,
  `potongan_keterlambatan` decimal(10,2) DEFAULT 5000.00,
  `jam_masuk` time DEFAULT '08:00:00',
  `toleransi_keterlambatan` int(11) DEFAULT 15,
  `jam_masuk_siang` time DEFAULT '13:00:00',
  
  -- Lateness penalty ranges
  `potongan_0_30` decimal(10,2) DEFAULT 0.00,
  `potongan_31_45` decimal(10,2) DEFAULT 10000.00,
  `potongan_46_60` decimal(10,2) DEFAULT 15000.00,
  `potongan_61_100` decimal(10,2) DEFAULT 25000.00,
  `potongan_101_200` decimal(10,2) DEFAULT 50000.00,
  `potongan_200_plus` decimal(10,2) DEFAULT 100000.00,
  
  -- Afternoon penalty ranges
  `potongan_siang_0_30` decimal(10,2) DEFAULT 0.00,
  `potongan_siang_31_45` decimal(10,2) DEFAULT 10000.00,
  `potongan_siang_46_60` decimal(10,2) DEFAULT 15000.00,
  `potongan_siang_61_100` decimal(10,2) DEFAULT 25000.00,
  `potongan_siang_101_200` decimal(10,2) DEFAULT 50000.00,
  `potongan_siang_200_plus` decimal(10,2) DEFAULT 100000.00,
  
  -- Leave settings
  `jatah_libur_per_bulan` int(11) DEFAULT 2,
  `denda_per_hari_libur` decimal(10,2) DEFAULT 50000.00,
  `bonus_tidak_libur` decimal(10,2) DEFAULT 25000.00,
  
  -- Minimal attendance settings
  `minimal_absen_pagi` time DEFAULT '07:00:00' COMMENT 'Minimal waktu absen pagi, absen sebelum jam ini akan dikenakan potongan',
  `minimal_absen_siang` time DEFAULT '12:00:00' COMMENT 'Minimal waktu absen siang, absen sebelum jam ini akan dikenakan potongan',
  `potongan_absen_awal` decimal(10,2) DEFAULT 10000.00 COMMENT 'Potongan gaji per kejadian absen sebelum waktu minimal',
  
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cabang (Branch)
-- =====================================================
CREATE TABLE `cabang` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_cabang` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kode_cabang` varchar(255) NOT NULL UNIQUE,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: users (Employees/Users)
-- =====================================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pegawai` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `gaji` int(11) DEFAULT 0,
  `status_pegawai` tinyint(1) DEFAULT 0,
  
  `id_jabatan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_cabang` bigint(20) UNSIGNED DEFAULT NULL,
  
  `provinsi` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `role` varchar(50) DEFAULT 'user' COMMENT 'User role: admin, manager, user',
  `google_id` varchar(255) DEFAULT NULL UNIQUE,
  `device_user_id` varchar(255) DEFAULT NULL COMMENT 'ID user di device fingerprint',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `users_id_jabatan_foreign` (`id_jabatan`),
  KEY `users_id_cabang_foreign` (`id_cabang`),
  CONSTRAINT `users_id_jabatan_foreign` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatans` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `users_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: pegawais (Legacy Employee Table)
-- =====================================================
CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pegawai` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: absensis (Attendance)
-- =====================================================
CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `tanggal_absen` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `jam_istirahat_keluar` time DEFAULT NULL,
  `jam_istirahat_masuk` time DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Hadir',
  `note` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `absensis_id_user_foreign` (`id_user`),
  CONSTRAINT `absensis_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: penggajians (Payroll)
-- =====================================================
CREATE TABLE `penggajians` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tanggal_gaji` date NOT NULL,
  `jumlah_gaji` int(11) NOT NULL,
  `bonus` int(11) DEFAULT 0,
  `potongan` int(11) DEFAULT 0,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `penggajians_id_user_foreign` (`id_user`),
  CONSTRAINT `penggajians_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: bonus_gaji (Salary Bonus)
-- =====================================================
CREATE TABLE `bonus_gaji` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `jumlah_bonus` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `bulan_tahun` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `bonus_gaji_id_user_foreign` (`id_user`),
  CONSTRAINT `bonus_gaji_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: potongan_gaji (Salary Deductions)
-- =====================================================
CREATE TABLE `potongan_gaji` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `jumlah_potongan` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `bulan_tahun` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `potongan_gaji_id_user_foreign` (`id_user`),
  CONSTRAINT `potongan_gaji_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: bons (Employee Loans)
-- =====================================================
CREATE TABLE `bons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `bons_pegawai_id_foreign` (`pegawai_id`),
  KEY `bons_created_by_foreign` (`created_by`),
  CONSTRAINT `bons_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: lokasis (Locations)
-- =====================================================
CREATE TABLE `lokasis` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: fingerprint_attendance (Fingerprint Device Data)
-- =====================================================
CREATE TABLE `fingerprint_attendance` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_user_id` varchar(255) NOT NULL COMMENT 'ID user di device fingerprint',
  `device_ip` varchar(255) NOT NULL COMMENT 'IP address device',
  `attendance_time` datetime NOT NULL COMMENT 'Waktu absensi dari device',
  `attendance_type` tinyint(4) DEFAULT 1 COMMENT '1=masuk, 2=keluar, 3=istirahat_keluar, 4=istirahat_masuk',
  `verification_type` varchar(255) DEFAULT NULL COMMENT 'Tipe verifikasi (fingerprint, password, card, etc)',
  `is_processed` tinyint(1) DEFAULT 0 COMMENT 'Apakah sudah diproses ke tabel absensi utama',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Foreign key ke users table (setelah mapping)',
  `cabang_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Foreign key ke cabang table',
  `raw_data` text DEFAULT NULL COMMENT 'Data mentah dari device untuk debugging',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  KEY `fingerprint_attendance_device_user_id_device_ip_index` (`device_user_id`,`device_ip`),
  KEY `fingerprint_attendance_attendance_time_index` (`attendance_time`),
  KEY `fingerprint_attendance_is_processed_index` (`is_processed`),
  KEY `fingerprint_attendance_user_id_index` (`user_id`),
  KEY `fingerprint_attendance_cabang_id_foreign` (`cabang_id`),
  CONSTRAINT `fingerprint_attendance_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fingerprint_attendance_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: pengaturan_libur (Holiday Settings)
-- =====================================================
CREATE TABLE `pengaturan_libur` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tanggal_libur` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `nama_libur` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis_libur` enum('nasional','perusahaan','khusus') DEFAULT 'perusahaan',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: pengaturan_hari_libur_mingguan (Weekly Holiday Settings)
-- =====================================================
CREATE TABLE `pengaturan_hari_libur_mingguan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') NOT NULL,
  `is_libur` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: jadwal_kerja (Work Schedule)
-- =====================================================
CREATE TABLE `jadwal_kerja` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jam_masuk` time DEFAULT '08:00:00',
  `jam_masuk_siang` time DEFAULT '13:00:00',
  `toleransi_keterlambatan` int(11) DEFAULT 15,
  `potongan_per_menit` int(11) DEFAULT 1000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: rekrutmens (Recruitment)
-- =====================================================
CREATE TABLE `rekrutmens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: berkas (Documents)
-- =====================================================
CREATE TABLE `berkas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: password_reset_tokens
-- =====================================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: sessions
-- =====================================================
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cache
-- =====================================================
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: jobs
-- =====================================================
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL UNIQUE,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SAMPLE DATA INSERTS (Optional)
-- =====================================================

-- Insert default job positions
INSERT INTO `jabatans` (`id`, `nama_jabatan`, `gaji_pokok`, `minimal_absen_pagi`, `minimal_absen_siang`, `potongan_absen_awal`, `created_at`, `updated_at`) VALUES
(1, 'Manager', 8000000.00, '07:00:00', '12:00:00', 10000.00, NOW(), NOW()),
(2, 'Supervisor', 6000000.00, '07:00:00', '12:00:00', 10000.00, NOW(), NOW()),
(3, 'Staff', 4000000.00, '07:00:00', '12:00:00', 10000.00, NOW(), NOW()),
(4, 'Operator', 3500000.00, '07:00:00', '12:00:00', 10000.00, NOW(), NOW());

-- Insert default branches
INSERT INTO `cabang` (`id`, `nama_cabang`, `alamat`, `kode_cabang`, `created_at`, `updated_at`) VALUES
(1, 'Cabang Pusat', 'Jl. Raya No. 123', 'CP001', NOW(), NOW()),
(2, 'Cabang Timur', 'Jl. Timur No. 456', 'CT002', NOW(), NOW());

-- Insert default users (ALL PASSWORDS: 'password')
-- LOGIN CREDENTIALS FOR DASHBOARD ACCESS:
-- 1. admin@gmail.com / password (Admin Role)
-- 2. manager@gmail.com / password (Manager Role) 
-- 3. demo@demo.com / password (Demo Admin)
-- 4. test@test.com / password (Test Admin)
INSERT INTO `users` (`id`, `nama_pegawai`, `email`, `password`, `is_admin`, `role`, `id_jabatan`, `id_cabang`, `status_pegawai`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin', 1, 1, 1, NOW(), NOW()),
(2, 'Manager', 'manager@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'manager', 1, 1, 1, NOW(), NOW()),
(3, 'Demo User', 'demo@demo.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin', 1, 1, 1, NOW(), NOW()),
(4, 'Test Admin', 'test@test.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin', 1, 1, 1, NOW(), NOW());

-- Insert weekly holiday settings (default: Saturday and Sunday)
INSERT INTO `pengaturan_hari_libur_mingguan` (`hari`, `is_libur`, `created_at`, `updated_at`) VALUES
('senin', 0, NOW(), NOW()),
('selasa', 0, NOW(), NOW()),
('rabu', 0, NOW(), NOW()),
('kamis', 0, NOW(), NOW()),
('jumat', 0, NOW(), NOW()),
('sabtu', 1, NOW(), NOW()),
('minggu', 1, NOW(), NOW());

-- Insert default work schedule
INSERT INTO `jadwal_kerja` (`jam_masuk`, `jam_masuk_siang`, `toleransi_keterlambatan`, `potongan_per_menit`, `created_at`, `updated_at`) VALUES
('08:00:00', '13:00:00', 15, 1000, NOW(), NOW());

COMMIT;

-- =====================================================
-- END OF DATABASE STRUCTURE
-- =====================================================

/*
NOTES:
1. This SQL file contains the complete database structure for the Dashboard Fingerprint project
2. All tables are created with proper foreign key relationships
3. Default values are set based on the Laravel migrations
4. Sample data is included for initial setup
5. The database uses utf8mb4 charset for full Unicode support
6. All timestamps use Laravel's standard created_at and updated_at columns

TO USE THIS FILE:
1. Create a new MySQL database
2. Import this SQL file into your database
3. Update your Laravel .env file with the correct database credentials
4. Run 'php artisan migrate:status' to verify the structure
5. Access the web application and login with any of the provided credentials

LOGIN CREDENTIALS (Ready to use for dashboard access):
- admin@gmail.com / password (Admin Role - Full Access)
- manager@gmail.com / password (Manager Role - Management Access)
- demo@demo.com / password (Demo Admin - Full Access)
- test@test.com / password (Test Admin - Full Access)

FEATURES INCLUDED:
- Employee management with job positions and branches
- Fingerprint device integration
- Attendance tracking with break times
- Payroll system with bonuses and deductions
- Holiday and leave management
- Lateness penalty system with multiple ranges
- Early attendance penalty system
- Employee loan (bon) management

SECURITY NOTE:
Change default passwords immediately after first login in production environment!
*/