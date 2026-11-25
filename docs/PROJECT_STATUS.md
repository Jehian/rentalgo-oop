# Status Proyek RentalGo

| Item | Nilai |
|---|---|
| Fase saat ini | Phase 0 — Requirements & Planning (selesai) |
| Status fase | Selesai dan di-commit; Phase 1 menunggu instruksi |
| Branch | `main` |
| Implementasi aplikasi | **Belum dimulai** |
| Terakhir diperbarui | 25 November 2026 |
| Diperbarui oleh | Benny Anderson (422024008) |

## 1. Ringkasan Status

RentalGo masih berada pada tahap perencanaan. Yang sudah ada hanya:

- Skeleton Laravel 13 dari commit awal.
- Repository GitHub dan branch.
- Dokumen Phase 0 (PRD, requirement, traceability, arsitektur, database, phase plan, workflow).

Belum ada migration, model, controller, service, view, route fitur, autentikasi, atau database
bisnis yang dibuat. Angka "0%" pada bagian implementasi berarti belum ada kode fitur, bukan berarti
repository kosong.

## 2. Status Fase

| Fase | Status | Catatan |
|---|---|---|
| 0 — Requirements & Planning | Selesai | Dokumen dibuat dan di-commit; tidak ada kode aplikasi |
| 1 — Foundation & Architecture | Belum dimulai | Menunggu instruksi pemilik proyek |
| 2 — Auth, Layout & Dashboard | Belum dimulai | Dependensi: Phase 1 |
| 3 — Master Data CRUD | Belum dimulai | Dependensi: Phase 1–2 |
| 4 — Rental & Payment Workflow | Belum dimulai | Dependensi: Phase 1–3 |
| 5 — Integration & Delivery | Belum dimulai | Dependensi: Phase 2–4 |

## 3. Progres Requirement

| Area | Status |
|---|---|
| Requirement UAS diekstrak | Selesai |
| Traceability matrix | Selesai (status implementasi masih `Planned`) |
| PRD | Selesai |
| Arsitektur dan UML | Selesai (dokumen) |
| Database schema dan ERD | Selesai (dokumen) |
| Phase plan dan pembagian tim | Selesai (nama/NIM sudah diisi) |
| Workflow Git dan AI handoff | Selesai |
| Implementasi OOP | 0% |
| Database/migration | 0% |
| Autentikasi | 0% |
| CRUD | 0% |
| Dashboard | 0% |
| Test fitur | 0% (baseline skeleton: 2 test lulus) |
| SQL dump | 0% |
| Screenshot | 0% |
| Laporan akhir | 0% |
| Presentasi | 0% |

## 4. Yang Sudah Selesai

- [x] Membaca dokumen UAS dan mengekstrak requirement.
- [x] Menyusun `PRD.md`.
- [x] Menyusun `docs/UAS_REQUIREMENTS.md`.
- [x] Menyusun `docs/UAS_TRACEABILITY.md` untuk 66 requirement ID.
- [x] Menyusun `docs/ARCHITECTURE.md` dengan class diagram, use case, dan sequence diagram.
- [x] Menyusun `docs/DATABASE_SCHEMA.md` dengan 5 tabel dan ERD.
- [x] Menyusun `docs/DECISIONS.md`.
- [x] Menyusun `docs/PHASE_PLAN.md`.
- [x] Menyusun `docs/AI_HANDOFF.md`.
- [x] Menyusun `AGENTS.md`.
- [x] Menyusun template phase report dan AI prompt.
- [x] Menyusun `docs/CONTRIBUTION_LOG.md`.
- [x] Menyusun `docs/progress/README.md`.
- [x] Memperbarui `README.md`.
- [x] Mengisi nama/NIM anggota pada dokumen.
- [x] Merapikan `.env` lokal dan `.env.example` untuk MySQL `rentalgo`.
- [x] Memverifikasi koneksi MySQL 9.1 port 3306.
- [x] Verifikasi 66/66 requirement ID memiliki baris traceability.
- [x] `php artisan test` baseline lulus (2 test, 2 assertion).
- [x] `vendor/bin/pint --test` lulus.
- [x] `git status` hanya menunjukkan perubahan dokumentasi dan `.env.example`.

### 4.1 Verifikasi Phase 0

| Pemeriksaan | Hasil |
|---|---|
| ID requirement vs traceability | 66 vs 66; tidak ada yang hilang/berlebih |
| Balanced Markdown code fences | Lulus untuk 15 file Markdown |
| Baseline test | 2 passed (2 assertions) |
| Laravel Pint | Passed |
| Koneksi database | MySQL 9.1, `127.0.0.1:3306`, database `rentalgo` |
| Tabel bisnis di database | 0 tabel; aman untuk `migrate:fresh` |
| Perubahan aplikasi/database | Tidak ada; hanya `.env` lokal dan `.env.example` |
| File yang berubah | `README.md`, `AGENTS.md`, `PRD.md`, `.env.example`, `docs/` |
| Commit Phase 0 | `main` |

## 5. Yang Sedang Dikerjakan

Tidak ada pekerjaan implementasi yang berjalan. Phase 0 selesai dan sudah di-commit; Phase 1
menunggu instruksi eksplisit dari pemilik proyek.

## 6. Langkah Berikutnya

1. Pemilik proyek memberikan instruksi untuk memulai Phase 1.
2. Buat branch `phase/01-foundation` dari `main` terbaru.
3. Phase 1 membuat konfigurasi, migration, model, domain OOP, service, seeder, dan test fondasi.
4. Verifikasi `migrate:fresh --seed`, `php artisan test`, dan Pint sebelum PR.

## 7. Metrik Rencana

| Metrik | Target UAS | Rencana RentalGo | Status |
|---|---:|---:|---|
| Class OOP | ≥ 8 | 16 class + 1 interface + 6 enum | Direncanakan |
| Inheritance | ≥ 2 | 2 (`Car`, `Motorcycle`) | Direncanakan |
| Static method | ≥ 1 | 2+ | Direncanakan |
| Tabel bisnis | ≥ 4 | 5 | Direncanakan |
| Fitur tambahan | ≥ 2 | 6 | Direncanakan |
| LOC | ≥ 1000 | Target ≥ 1000 | 0 |
| Entitas CRUD | ≥ 3 | Pelanggan, kendaraan, pembayaran (+ lifecycle rental) | Direncanakan |
| Test suite | Wajib berjalan | Unit + feature | Belum ada test fitur |

## 8. Pertanyaan Terbuka / Blockers

| No | Hal | Dampak | Status |
|---|---|---|---|
| 1 | Nama/NIM anggota tim | Dokumen dan penilaian individu | Selesai |
| 2 | Kredensial MySQL lokal | Setup Phase 1 | Selesai: MySQL 9.1, port 3306, `rentalgo` |
| 3 | Bonus yang dikerjakan | Scope Phase 5 | Selesai: REST API + PDF direncanakan |
| 4 | Format UML final (Mermaid/PNG) | Presentasi/dokumen | Default: Mermaid + PNG jika diminta |
| 5 | Tanggal/format presentasi | Phase 5 | Menunggu informasi |
| 6 | Waktu mulai Phase 1 | Penjadwalan | Menunggu instruksi pemilik proyek |

Tidak ada blocker teknis untuk memulai Phase 1.

## 9. Catatan Environment

- PHP CLI 8.4.8; Laravel 13.44.1; Node 22.16.0; npm 10.9.2.
- Database aktif di `.env`: MySQL 9.1, `127.0.0.1:3306`, database `rentalgo`.
- Service WAMP MySQL dan MariaDB berjalan; MariaDB memakai port 3307 dan tidak dipakai proyek.
- PHP CLI menampilkan peringatan startup `ext/curl` yang diblokir kebijakan sistem; Artisan tetap
  berjalan. Ini masalah environment lokal, bukan bug proyek.
- Ekstensi `intl` belum aktif di PHP CLI; aplikasi akan memakai `number_format`, bukan
  `Number::format`. Error `db:show` setelah koneksi berasal dari keterbatasan ini, bukan koneksi.
- Docker tidak digunakan.

## 10. Aturan Pembaruan Status

1. Setiap fase wajib memperbarui file ini setelah pekerjaan selesai dan diverifikasi.
2. Jangan menandai fase selesai sebelum PR direview dan digabung.
3. Jangan menulis "100%" untuk implementasi yang belum diuji.
4. Catat blocker dan pertanyaan terbuka secara eksplisit.
5. Sertakan tanggal dan nama pembaru.
