# Status Proyek RentalGo

| Item | Nilai |
|---|---|
| Fase saat ini | Phase 2 — Authentication, Layout & Dashboard (implemented; PR pending) |
| Status fase | Implementasi lokal selesai (termasuk portal pelanggan); 47 test lulus; menunggu review/merge |
| Branch | `phase/02-auth-dashboard` |
| Implementasi aplikasi | Auth, registrasi, layout, dashboard, dan portal pelanggan selesai; CRUD admin Phase 3–4 belum |
| Terakhir diperbarui | 25 November 2026 |
| Diperbarui oleh | Steven Credentia Ivanemaga Zega (422024020) |

## 1. Ringkasan Status

Phase 0 (dokumentasi) dan Phase 1 (fondasi) sudah selesai. Phase 1 digabung ke `main` melalui PR #1
dengan merge commit `1b5caee`, sehingga migration, model, enum, domain OOP, service, factory, seeder,
dan route stub sudah tersedia di `main`.

Phase 2 sudah diimplementasikan pada branch `phase/02-auth-dashboard`: autentikasi custom, middleware
role Admin, layout responsif, komponen Blade reusable, dashboard dengan Chart.js, dan feature test.
Belum ada CRUD pelanggan/kendaraan, transaksi rental, atau pembayaran; itu scope Phase 3–4. Phase 2
belum di-PR dan belum di-merge.

## 2. Status Fase

| Fase | Status | Catatan |
|---|---|---|
| 0 — Requirements & Planning | Selesai | Dokumen dibuat dan di-commit di `main` |
| 1 — Foundation & Architecture | Merged | PR #1, merge commit `1b5caee`; 25 test saat itu |
| 2 — Auth, Layout & Dashboard | Implemented — PR pending | 47 test lulus; termasuk portal pelanggan; menunggu review |
| 3 — Master Data CRUD | Belum dimulai | Dependensi: Phase 2 merged |
| 4 — Rental & Payment Workflow | Belum dimulai | Dependensi: Phase 1–3 |
| 5 — Integration & Delivery | Belum dimulai | Dependensi: Phase 2–4 |

## 3. Progres Requirement

| Area | Status |
|---|---|
| Requirement UAS diekstrak | Selesai (66 ID) |
| Traceability matrix | Diperbarui; requirement Phase 1–2 berstatus `Implemented` |
| PRD | Selesai |
| Arsitektur dan UML | Selesai (dokumen) |
| Database schema dan ERD | Selesai (dokumen) |
| Phase plan dan pembagian tim | Selesai |
| Workflow Git dan AI handoff | Selesai |
| Implementasi OOP | Fondasi domain/service selesai; service dashboard ditambahkan |
| Database/migration | 5 tabel bisnis + role selesai (Phase 1) |
| Autentikasi | Implemented (Phase 2) — login, logout, registrasi pelanggan |
| Layout dan komponen UI | Implemented (Phase 2) — operator + publik/pelanggan |
| Portal pelanggan | Implemented (Phase 2) — katalog, detail/estimasi, booking, riwayat, profil |
| Dashboard | Implemented (Phase 2) |
| CRUD pelanggan/kendaraan | 0% (Phase 3) |
| Rental dan pembayaran | 0% (Phase 4) |
| Test | 47 passed (153 assertions) |
| SQL dump | 0% |
| Screenshot | Login dan dashboard desktop/mobile tersedia |
| Laporan akhir | 0% |
| Presentasi | 0% |

## 4. Yang Sudah Selesai

### 4.1 Phase 0 — Requirements & Planning

- [x] Membaca UAS dan mengekstrak 66 requirement ID.
- [x] Menyusun `PRD.md`, requirement, traceability, arsitektur, schema, keputusan teknis.
- [x] Menyusun phase plan, workflow Git, template laporan, AI handoff.
- [x] Commit Phase 0 `510a309` di `main`; baseline test dan Pint lulus.

### 4.2 Phase 1 — Foundation & Architecture (merged)

- [x] Konfigurasi `config/rental.php`, timezone `Asia/Jakarta`, dan `APP_TIMEZONE`.
- [x] Migration role user, `customers`, `vehicles`, `rentals`, `payments`.
- [x] Model `User`, `Customer`, `Vehicle`, `Rental`, `Payment` dengan relasi/cast/scope.
- [x] Enam enum dengan label Indonesia.
- [x] `Rentable`, abstract `Vehicle`, `Car`, `Motorcycle`, `VehicleFactory`.
- [x] Custom exception dan `DocumentCodeGenerator`.
- [x] `RentalService` untuk create/update/complete/cancel dan denda.
- [x] Factory, seeder demo, route stub, unit/feature test.
- [x] PR #1 direview Steven Credentia Ivanemaga Zega dan di-merge (`1b5caee`).

### 4.3 Phase 2 — Authentication, Layout & Dashboard (implementasi lokal)

- [x] Login/logout/session operator (`web` guard di tabel `users`) + login/registrasi/logout pelanggan terpisah (`customer` guard di tabel `customers`).
- [x] Validasi login/registrasi dan rate limit 5 percobaan.
- [x] Middleware `EnsureUserIsAdmin` (`admin`) serta isolasi guard `auth:web` vs `auth:customer`.
- [x] Redirect `guest` dan user terautentikasi sesuai area (`/login` ↔ `/dashboard`, `/customer/login` ↔ `/`).
- [x] Layout utama operator (`app.blade.php`), layout guest sederhana, dan layout publik/pelanggan (`public.blade.php`).
- [x] Komponen `x-alert`, `x-button`, `x-card`, `x-input`, `x-modal`, `x-pagination`, `x-status-badge`.
- [x] `DashboardService` dan `DashboardController`.
- [x] Kartu ringkasan, grafik pendapatan 6 bulan, komposisi status kendaraan, rental terbaru.
- [x] Chart.js 4.6.2 melalui npm.
- [x] Halaman 403 berbahasa Indonesia.
- [x] Portal pelanggan publik: katalog kendaraan (`/`), search, filter tipe/status, sorting tarif, detail & estimasi tarif live, booking mandiri via `RentalService`, riwayat/detail pesanan, pembatalan rental aktif pribadi, dan edit profil.
- [x] Feature test auth, registrasi, isolasi guard, dashboard, dan portal pelanggan (47 test total, 153 assertions).
- [x] Screenshot login/dashboard desktop dan mobile.
- [x] Dokumentasi Phase 2, PRD, ADR-028, skema, arsitektur, traceability, contribution log, dan README diperbarui.
- [ ] PR, review anggota lain, dan merge.

### 4.4 Verifikasi Phase 2

| Pemeriksaan | Hasil |
|---|---|
| `php artisan test` | 47 passed (153 assertions) |
| `vendor\bin\pint --test` | Passed |
| `npm run build` | Passed; CSS 77.10 kB (gzip 14.59 kB), JS 209.82 kB (gzip 72.05 kB) |
| `php artisan migrate:fresh --seed --force` | Perlu dijalankan ulang di MySQL lokal setelah penambahan migration `add_auth_columns_to_customers_and_nullable_user_id_on_rentals` |
| Login/registrasi & proteksi role | Lulus via feature test |
| Portal pelanggan (katalog, booking, riwayat, profil) | Lulus via feature test |
| Dashboard dengan data seeder | Lulus; chart dan tabel tampil |
| Screenshot | 4 file login/dashboard di `docs/assets/phase-02/` |

## 5. Yang Sedang Dikerjakan

Tidak ada pekerjaan implementasi lain yang berjalan. Phase 2 selesai secara lokal dan menunggu
PR/review. Phase 3 belum boleh dimulai sebelum Phase 2 digabung, sesuai urutan dependensi.

## 6. Langkah Berikutnya

1. Tunjukkan ringkasan perubahan dan hasil verifikasi Phase 2 kepada pemilik proyek.
2. Setelah disetujui: commit dengan Conventional Commits, push branch `phase/02-auth-dashboard`.
3. Buat PR ke `main`, minta reviewer anggota lain (bukan pemilik branch).
4. Review, lalu merge memakai merge commit (bukan squash).
5. Setelah merge, mulai Phase 3 dari `main` terbaru: CRUD pelanggan dan kendaraan.
6. Phase 4: rental dan pembayaran; Phase 5: integrasi, SQL dump, laporan, presentasi.

## 7. Metrik Rencana

| Metrik | Target UAS | Rencana RentalGo | Status |
|---|---:|---:|---|
| Class OOP | ≥ 8 | 16 class + 1 interface + 6 enum | 18 class + 1 interface + 6 enum |
| Inheritance | ≥ 2 | 2 (`Car`, `Motorcycle`) | Tercapai |
| Static method | ≥ 1 | 2+ | Tercapai |
| Tabel bisnis | ≥ 4 | 5 | Tercapai |
| Fitur tambahan | ≥ 2 | 6 | Chart selesai; search/pagination/sort/filter/upload menyusul |
| LOC | ≥ 1000 | Target ≥ 1000 | Belum dihitung |
| Entitas CRUD | ≥ 3 | Pelanggan, kendaraan, pembayaran (+ lifecycle rental) | Belum ada (Phase 3–4) |
| Test suite | Wajib berjalan | Unit + feature | 47 passed (153 assertions) |

## 8. Pertanyaan Terbuka / Blockers

| No | Hal | Dampak | Status |
|---|---|---|---|
| 1 | PR/review Phase 2 | Merge dan mulai Phase 3 | Implementasi selesai; PR belum dibuat |
| 2 | Reviewer Phase 2 | Syarat Definition of Done | Perlu anggota selain pemilik branch |
| 3 | Format UML final (Mermaid/PNG) | Presentasi/dokumen | Default: Mermaid + PNG jika diminta |
| 4 | Tanggal/format presentasi | Phase 5 | Menunggu informasi |
| 5 | Aktivasi link sidebar Phase 3/4 | Navigasi UI | Menunggu route dari fase terkait |

Tidak ada blocker teknis untuk review Phase 2.

## 9. Catatan Environment

- PHP CLI 8.4.8; Laravel 13.44.1; Node 22.16.0; npm 10.9.2.
- Database aktif di `.env`: MySQL 9.1, `127.0.0.1:3306`, database `rentalgo`.
- Service WAMP MySQL dan MariaDB berjalan; MariaDB memakai port 3307 dan tidak dipakai proyek.
- PHP CLI menampilkan peringatan startup `ext/curl` yang diblokir kebijakan sistem; Artisan tetap
  berjalan. Ini masalah environment lokal, bukan bug proyek.
- Ekstensi `intl` belum aktif di PHP CLI; aplikasi memakai `number_format`, bukan `Number::format`.
- Chart.js 4.6.2 terpasang melalui npm; `npm run build` menghasilkan aset di `public/build`.
- Screenshot Phase 2 diambil dengan headless Chrome pada aplikasi lokal.
- Docker tidak digunakan.

## 10. Aturan Pembaruan Status

1. Setiap fase wajib memperbarui file ini setelah pekerjaan selesai dan diverifikasi.
2. Jangan menandai fase selesai sebelum PR direview dan digabung.
3. Jangan menulis "100%" untuk implementasi yang belum diuji.
4. Catat blocker dan pertanyaan terbuka secara eksplisit.
5. Sertakan tanggal dan nama pembaru.
