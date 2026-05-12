# Product Requirements Document (PRD) - 3D Pipeline Management

## Deskripsi Produk
Sistem monitoring alur kerja produksi asset 3D. Sistem ini berjalan secara Lokal.

## User Roles
- **Super Admin (Lvl 1):** Owner/Pemberi tugas.
- **Production (Lvl 2):** Spesialis (Modeling, Texturing, RIG, Animation, LRC).
- **Reviewer (Lvl 3):** QC/Gatekeeper.

## Fitur Utama
- Login menggunakan Username & Password.
- Submission Lvl 2 wajib menyertakan file mentah (`.blend`) dan video preview (`.mov`).
- Reviewer Lvl 3 bisa Approve (lanjut ke Lvl 1) atau Reject (Revisi balik ke Lvl 2).
- Track Status pengerjaan tampil di semua halaman user.

## Business Rules
- Tugas wajib lewat Reviewer sebelum sampai ke Admin Lvl 1.
- Simpan history revisi (v1, v2, dst).

## Out of Scope
- Tidak pakai hosting online.
- Tidak ada chat real-time.
