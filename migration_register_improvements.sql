-- ============================================================
-- Migration: Perbaikan Sistem Pendaftaran
-- Tanggal   : 2026-07-27
-- Keterangan: Menambahkan kolom verifikasi email pada tabel user
-- ============================================================

-- 1. Tambah kolom email_verified dan verification_token ke tabel user
ALTER TABLE `user`
  ADD COLUMN `email_verified` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Status verifikasi email: 0=belum, 1=sudah' AFTER `level`,
  ADD COLUMN `verification_token` VARCHAR(64) DEFAULT NULL COMMENT 'Token verifikasi email satu kali pakai' AFTER `email_verified`;

-- 2. Tandai semua akun LAMA sebagai sudah terverifikasi
--    agar tidak terkunci dari sistem
UPDATE `user` SET `email_verified` = 1 WHERE `email_verified` = 0;

-- 3. (Opsional) Tambah index untuk pencarian token yang lebih cepat
ALTER TABLE `user`
  ADD INDEX `idx_verification_token` (`verification_token`);

-- Selesai. Jalankan script ini sekali saja di phpMyAdmin.
