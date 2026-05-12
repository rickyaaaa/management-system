# Database Schema - 3D Pipeline Management

## Ketentuan Teknis
- **Database:** MySQL
- **Primary Key:** UUID (`VARCHAR(36)`) untuk semua tabel.
- **Soft Delete:** Semua tabel dilengkapi dengan `deleted_at`.

---

### 1. Tabel `users`
Menyimpan data pengguna beserta role mereka (Super Admin Lvl 1, Production Lvl 2, Reviewer Lvl 3).

| Field Name | Data Type | Modifiers | Description |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(36) | PRIMARY KEY | UUID pengguna |
| `username` | VARCHAR(50) | UNIQUE, NOT NULL | Username untuk login |
| `password` | VARCHAR(255) | NOT NULL | Password (hashed) |
| `role_level` | INT | NOT NULL | 1: Super Admin, 2: Production, 3: Reviewer |
| `role_specialty`| VARCHAR(50) | NULL | Khusus Lvl 2 (Modeling, Texturing, RIG, Animation, LRC) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu record dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu record diubah |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

### 2. Tabel `tasks`
Menyimpan informasi tugas yang diberikan oleh Super Admin ke Production.

| Field Name | Data Type | Modifiers | Description |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(36) | PRIMARY KEY | UUID tugas |
| `admin_id` | VARCHAR(36) | FOREIGN KEY | Pembuat tugas (Super Admin Lvl 1) |
| `assignee_id` | VARCHAR(36) | FOREIGN KEY, NULL | Yang mengerjakan (Production Lvl 2) |
| `title` | VARCHAR(150) | NOT NULL | Judul tugas |
| `description` | TEXT | NULL | Detail tugas |
| `status` | ENUM | NOT NULL | 'pending', 'in_progress', 'awaiting_review', 'revision', 'ready_for_admin', 'completed' |
| `version` | INT | DEFAULT 1 | Melacak iterasi versi (v1, v2, dst) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu record dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu record diubah |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

### 3. Tabel `submissions`
Menyimpan file hasil pekerjaan dari Production (Lvl 2). Wajib ada `.blend` dan `.mov`.

| Field Name | Data Type | Modifiers | Description |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(36) | PRIMARY KEY | UUID submission |
| `task_id` | VARCHAR(36) | FOREIGN KEY | Relasi ke tugas terkait |
| `production_id` | VARCHAR(36) | FOREIGN KEY | Yang melakukan submission (Production Lvl 2) |
| `version` | INT | NOT NULL | Versi submission tugas ini (e.g. 1, 2) |
| `file_blend_url`| TEXT | NOT NULL | Path/URL penyimpanan file mentah `.blend` |
| `file_mov_url` | TEXT | NOT NULL | Path/URL penyimpanan file video preview `.mov` |
| `notes` | TEXT | NULL | Catatan tambahan dari Production |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu record dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu record diubah |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

### 4. Tabel `reviews`
Menyimpan feedback atau keputusan (Approve/Reject) dari Reviewer (Lvl 3).

| Field Name | Data Type | Modifiers | Description |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(36) | PRIMARY KEY | UUID review |
| `submission_id` | VARCHAR(36) | FOREIGN KEY | Relasi ke submission yang direview |
| `reviewer_id` | VARCHAR(36) | FOREIGN KEY | Reviewer yang menilai (Lvl 3) |
| `status` | ENUM | NOT NULL | 'approved', 'rejected' |
| `feedback` | TEXT | NULL | Catatan revisi (biasanya wajib jika rejected) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu record dibuat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu record diubah |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

### 5. Tabel `task_logs`
Menyimpan riwayat perubahan status pengerjaan untuk keperluan history dan tracking di UI.

| Field Name | Data Type | Modifiers | Description |
| :--- | :--- | :--- | :--- |
| `id` | VARCHAR(36) | PRIMARY KEY | UUID log |
| `task_id` | VARCHAR(36) | FOREIGN KEY | Tugas terkait yang statusnya berubah |
| `user_id` | VARCHAR(36) | FOREIGN KEY | User yang memicu perubahan |
| `previous_status`| VARCHAR(50) | NULL | Status tugas sebelum diubah |
| `new_status` | VARCHAR(50) | NOT NULL | Status tugas yang baru |
| `action_note` | TEXT | NULL | Keterangan aksi (cth: "File v2 submitted", "Rejected by QC") |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu log dicatat |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu record diubah |
| `deleted_at` | TIMESTAMP | NULL | Soft delete |

---

## Relasi Antar Tabel

1. **`users` (1) ke `tasks` (N)**
   - `tasks.admin_id` merujuk ke `users.id` (Tugas dibuat oleh Super Admin Lvl 1).
   - `tasks.assignee_id` merujuk ke `users.id` (Tugas diberikan ke Production Lvl 2).

2. **`tasks` (1) ke `submissions` (N)**
   - `submissions.task_id` merujuk ke `tasks.id`. Sebuah tugas dapat memiliki banyak history submission jika terjadi proses revisi berulang kali (v1, v2, dst).

3. **`users` (1) ke `submissions` (N)**
   - `submissions.production_id` merujuk ke `users.id` (Siapa dari Lvl 2 yang meng-upload submission tersebut).

4. **`submissions` (1) ke `reviews` (N)**
   - `reviews.submission_id` merujuk ke `submissions.id`. Setiap file submission yang masuk dinilai oleh Reviewer (Lvl 3).

5. **`users` (1) ke `reviews` (N)**
   - `reviews.reviewer_id` merujuk ke `users.id` (Reviewer Lvl 3 mana yang memberikan putusan QC).

6. **`tasks` (1) ke `task_logs` (N)**
   - `task_logs.task_id` merujuk ke `tasks.id`. Seluruh riwayat perjalanan sebuah tugas (dari *pending* ke *completed*) dicatat ke tabel ini.

7. **`users` (1) ke `task_logs` (N)**
   - `task_logs.user_id` merujuk ke `users.id`. Mencatat user spesifik yang melakukan perubahan pada log tersebut.
