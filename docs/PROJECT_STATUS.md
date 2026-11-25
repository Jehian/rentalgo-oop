# Status Proyek RentalGo

| Item | Nilai |
|---|---|
| Fase saat ini | Phase 1 — Foundation & Architecture (committed lokal) |
| Status fase | Draft: sudah di-push; belum PR/merge |
| Branch | `phase/01-foundation` |
| Implementasi aplikasi | Fondasi Phase 1 selesai dan committed lokal; UI/auth/CRUD/dashboard belum |
| Terakhir diperbarui | 25 November 2026 |
| Diperbarui oleh | Benny Anderson (422024008) |

## 1. Ringkasan Status

Phase 0 selesai dan sudah di-commit di `main`. Phase 1 sudah diimplementasikan secara lokal pada
branch `phase/01-foundation`: konfigurasi, lima tabel bisnis, model/relasi, domain OOP, service,
factory, seeder, dan test. Belum ada controller, view, autentikasi, dashboard, atau CRUD UI.
Phase 1 sudah di-commit pada lima commit `8510ea6..22fb7c5` dan branch sudah di-push; belum di-PR
dan belum direview.

## 2. Status Fase

| Fase | Status | Catatan |
|---|---|---|
| 0 — Requirements & Planning | Selesai | Dokumen dibuat dan di-commit di `main` |
| 1 — Foundation & Architecture | Pushed | 25 test lulus; commit `8510ea6..22fb7c5`; menunggu PR/review |
| 2 — Auth, Layout & Dashboard | Belum dimulai | Dependensi: Phase 1 merged |
| 3 — Master Data CRUD | Belum dimulai | Dependensi: Phase 1–2 |
| 4 — Rental & Payment Workflow | Belum dimulai | Dependensi: Phase 1–3 |
| 5 — Integration & Delivery | Belum dimulai | Dependensi: Phase 2–4 |

## 3. Progres Requirement

| Area | Status |
|---|---|
| Requirement UAS diekstrak | Selesai |
| Traceability matrix | Diperbarui; 12 ID Phase 1 berstatus `Implemented` |
| PRD | Selesai |
| Arsitektur dan UML | Selesai (dokumen) |
| Database schema dan ERD | Selesai (dokumen) |
| Phase plan dan pembagian tim | Selesai |
| Workflow Git dan AI handoff | Selesai |
| Implementasi OOP | Fondasi domain/service selesai |
| Database/migration | 5 tabel bisnis + role selesai |
| Autentikasi | 0% |
| CRUD | 0% |
| Dashboard | 0% |
| Test fitur | 25 passed (57 assertions) |
| SQL dump | 0% |
| Screenshot | 0% |
| Laporan akhir | 0% |
| Presentasi | 0% |

## 4. Yang Sudah Selesai

### 4.1 Phase 0

- [x] Membaca dokumen UAS dan mengekstrak 66 requirement ID.
- [x] Menyusun `PRD.md`, `docs/UAS_REQUIREMENTS.md`, `docs/UAS_TRACEABILITY.md`.
- [x] Menyusun `docs/ARCHITECTURE.md`, `docs/DATABASE_SCHEMA.md`, `docs/DECISIONS.md`.
- [x] Menyusun `docs/PHASE_PLAN.md`, `docs/AI_HANDOFF.md`, `AGENTS.md`, template phase report.
- [x] Mengisi nama/NIM anggota dan merapikan `.env.example` untuk MySQL `rentalgo`.
- [x] Commit Phase 0 `510a309` di `main`; baseline test dan Pint lulus.

### 4.2 Phase 1 — implementasi lokal

- [x] Konfigurasi `config/rental.php`, timezone `Asia/Jakarta`, dan `APP_TIMEZONE`.
- [x] Migration role user, `customers`, `vehicles`, `rentals`, `payments`.
- [x] Model `User`, `Customer`, `Vehicle`, `Rental`, `Payment` dengan relasi/cast/scope.
- [x] Enam enum dengan label Indonesia.
- [x] `Rentable`, abstract `Vehicle`, `Car`, `Motorcycle`, `VehicleFactory`.
- [x] Custom exception dan `DocumentCodeGenerator`.
- [x] `RentalService` create/update/complete/cancel dan late fee.
- [x] Factory, seeder admin/staff, 8 pelanggan, 8 kendaraan, 12 rental, 12 pembayaran.
- [x] Route file kosong per modul dan include di `routes/web.php`.
- [x] Unit/feature test Phase 1.
- [x] Commit lokal Phase 1 (`8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`, `22fb7c5`).
- [x] Push branch `phase/01-foundation`.
- [ ] PR/review/merge Phase 1.

### 4.3 Verifikasi Phase 1

| Pemeriksaan | Hasil |
|---|---|
| `php artisan migrate:fresh --seed --force` | Berhasil di MySQL 9.1 |
| Data demo | 2 users, 8 customers, 8 vehicles, 12 rentals, 12 payments |
| Status data demo | 6 rental aktif, 6 selesai, 2 kendaraan tersedia, 6 disewa |
| `php artisan test` | 25 passed (57 assertions) |
| `vendor/bin/pint --test` | Passed |
| Route file | Kosong dan ter-include; route fitur diisi Phase 2–4 |
| Commit/PR | Commit `8510ea6..22fb7c5` sudah di-push; PR belum dibuat |

## 5. Yang Sedang Dikerjakan

Implementasi Phase 1 selesai, sudah diverifikasi, dan branch sudah di-push. Pekerjaan berikutnya
adalah membuat PR dan review Phase 1.

## 6. Langkah Berikutnya

1. Buat PR dari `phase/01-foundation` ke `main`.
2. Reviewer memeriksa kode, test, dan dokumentasi; merge setelah disetujui.
3. Mulai Phase 2: autentikasi, layout, dashboard, dan middleware role.

## 7. Metrik Rencana

| Metrik | Target UAS | Rencana RentalGo | Status |
|---|---:|---:|---|
| Class OOP | ≥ 8 | 16 class + 1 interface + 6 enum | 13 class + 1 interface + 6 enum (Phase 1) |
| Inheritance | ≥ 2 | 2 (`Car`, `Motorcycle`) | Selesai |
| Static method | ≥ 1 | 2+ | 2 (`VehicleFactory`, `DocumentCodeGenerator`) |
| Tabel bisnis | ≥ 4 | 5 | Selesai |
| Fitur tambahan | ≥ 2 | 6 | Direncanakan Phase 2–4 |
| LOC | ≥ 1000 | Target ≥ 1000 | Belum dihitung |
| Entitas CRUD | ≥ 3 | Pelanggan, kendaraan, pembayaran (+ lifecycle rental) | Direncanakan Phase 3–4 |
| Test suite | Wajib berjalan | Unit + feature | 25 passed |

## 8. Pertanyaan Terbuka / Blockers

| No | Hal | Dampak | Status |
|---|---|---|---|
| 1 | Nama/NIM anggota tim | Dokumen dan penilaian individu | Selesai |
| 2 | Kredensial MySQL lokal | Setup Phase 1 | Selesai: MySQL 9.1, port 3306, `rentalgo` |
| 3 | Bonus yang dikerjakan | Scope Phase 5 | Selesai: REST API + PDF direncanakan |
| 4 | Format UML final (Mermaid/PNG) | Presentasi/dokumen | Default: Mermaid + PNG jika diminta |
| 5 | Tanggal/format presentasi | Phase 5 | Menunggu informasi |
| 6 | PR/review Phase 1 | Submission dan review | Branch sudah di-push; menunggu PR/review |

Tidak ada blocker teknis; Phase 1 sudah lulus verifikasi lokal.

## 9. Catatan Environment

- PHP CLI 8.4.8; Laravel 13.44.1; Node 22.16.0; npm 10.9.2.
- Database aktif di `.env`: MySQL 9.1, `127.0.0.1:3306`, database `rentalgo`.
- Service WAMP MySQL dan MariaDB berjalan; MariaDB memakai port 3307 dan tidak dipakai proyek.
- MySQL lokal membatasi key index 1000 byte; string terindeks pada migration skeleton dibatasi
  (`email` 150, key/id 191) agar kompatibel.
- PHP CLI menampilkan peringatan startup `ext/curl` yang diblokir kebijakan sistem; Artisan tetap
  berjalan. Ini masalah environment lokal, bukan bug proyek.
- Ekstensi `intl` belum aktif di PHP CLI; aplikasi akan memakai `number_format`, bukan
  `Number::format`. Error `db:show` setelah koneksi berasal dari keterbatasan ini.
- Docker tidak digunakan.

## 10. Aturan Pembaruan Status

1. Setiap fase wajib memperbarui file ini setelah pekerjaan selesai dan diverifikasi.
2. Jangan menandai fase selesai sebelum PR direview dan digabung.
3. Jangan menulis "100%" untuk implementasi yang belum diuji.
4. Catat blocker dan pertanyaan terbuka secara eksplisit.
5. Sertakan tanggal dan nama pembaru.
