# Status Proyek RentalGo

| Item | Nilai |
|---|---|
| Fase saat ini | Phase 3 — Master Data CRUD (implemented; PR pending) |
| Status fase | Implementasi lokal selesai; 74 test lulus (293 assertions); menunggu review/merge |
| Branch | `phase/03-master-data` |
| Implementasi aplikasi | Auth, layout, dashboard, portal pelanggan, CRUD pelanggan, dan CRUD kendaraan selesai; rental/pembayaran belum (Phase 4) |
| Terakhir diperbarui | 25 November 2026 |
| Diperbarui oleh | Tim Phase 3 (pemilik fase: Jason / 422024004) |

## 1. Ringkasan Status

Phase 0 (dokumentasi), Phase 1 (fondasi), dan Phase 2 (autentikasi, layout, dashboard, portal pelanggan)
sudah selesai. Phase 1 digabung ke `main` melalui PR #1 (`1b5caee`); Phase 2 digabung melalui PR #2
(`c4847c2`). Fondasi migration, model, enum, domain OOP, service, factory, seeder, layout, dan komponen
Blade sudah tersedia di `main`.

Phase 3 sudah diimplementasikan pada branch `phase/03-master-data`: CRUD pelanggan dan kendaraan,
pencarian, sorting, filter, pagination, upload/ganti gambar kendaraan, detail dengan riwayat rental,
validasi Form Request, serta soft delete khusus Admin dengan perlindungan rental aktif. Tidak ada
perubahan schema atau dependency baru. Phase 3 belum di-commit, di-push, atau dibuatkan PR. Transaksi
rental dan pembayaran tetap menjadi scope Phase 4.

## 2. Status Fase

| Fase | Status | Catatan |
|---|---|---|
| 0 — Requirements & Planning | Selesai | Dokumen dibuat dan di-commit di `main` |
| 1 — Foundation & Architecture | Merged | PR #1, merge commit `1b5caee`; 25 test saat itu |
| 2 — Auth, Layout & Dashboard | Merged | PR #2, merge commit `c4847c2`; termasuk portal pelanggan; 47 test saat itu |
| 3 — Master Data CRUD | Implemented — PR pending | CRUD pelanggan/kendaraan, upload gambar, 27 test fase; 74 test total |
| 4 — Rental & Payment Workflow | Belum dimulai | Dependensi: Phase 1–3 |
| 5 — Integration & Delivery | Belum dimulai | Dependensi: Phase 2–4 |

## 3. Progres Requirement

| Area | Status |
|---|---|
| Requirement UAS diekstrak | Selesai (66 ID) |
| Traceability matrix | Diperbarui; requirement Phase 1–3 berstatus `Implemented`; `Verified` menunggu review |
| PRD | Selesai |
| Arsitektur dan UML | Selesai (dokumen) |
| Database schema dan ERD | Selesai (dokumen) |
| Phase plan dan pembagian tim | Selesai |
| Workflow Git dan AI handoff | Selesai |
| Implementasi OOP | Fondasi domain/service, dashboard, dan `VehicleImageService` |
| Database/migration | 5 tabel bisnis + role selesai (Phase 1); tidak ada perubahan Phase 3 |
| Autentikasi | Implemented & merged (Phase 2) |
| Layout dan komponen UI | Implemented & merged (Phase 2); link master diaktifkan Phase 3 |
| Portal pelanggan | Implemented & merged (Phase 2) |
| Dashboard | Implemented & merged (Phase 2) |
| CRUD pelanggan | Implemented lokal (Phase 3) — list/search/sort/pagination/create/edit/detail/delete |
| CRUD kendaraan | Implemented lokal (Phase 3) — list/filter/create/edit/detail/upload/delete |
| Rental dan pembayaran | 0% (Phase 4) |
| Test | 74 passed (293 assertions) |
| SQL dump | 0% |
| Screenshot | Login/dashboard (Phase 2) dan pelanggan/kendaraan (Phase 3) tersedia |
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

### 4.3 Phase 2 — Authentication, Layout & Dashboard (merged)

- [x] Login/logout/session operator (`web` guard) + login/registrasi/logout pelanggan (`customer` guard).
- [x] Middleware `EnsureUserIsAdmin` (`admin`) serta isolasi guard.
- [x] Layout operator, guest, dan publik/pelanggan + 7 komponen Blade reusable.
- [x] Dashboard dengan kartu ringkasan, grafik Chart.js, dan rental terbaru.
- [x] Portal pelanggan: katalog, detail/estimasi, booking mandiri, riwayat, pembatalan, profil.
- [x] Feature test auth, dashboard, dan portal pelanggan (47 test total saat itu).
- [x] PR #2 digabung ke `main` melalui merge commit `c4847c2`.

### 4.4 Phase 3 — Master Data CRUD (implementasi lokal)

- [x] CRUD pelanggan: daftar, pencarian nama/email/telepon/NIK/SIM, sorting, pagination.
- [x] Tambah/edit pelanggan dengan validasi wajib, unik, dan pesan Bahasa Indonesia.
- [x] Detail pelanggan dengan jumlah rental dan riwayat rental.
- [x] Soft delete pelanggan hanya Admin; ditolak jika masih ada rental aktif.
- [x] CRUD kendaraan: daftar, pencarian, filter tipe/status, sorting, pagination.
- [x] Tambah/edit kendaraan dengan field kondisional mobil (`seat_count`, `transmission`) dan motor (`engine_cc`).
- [x] Upload/ganti gambar kendaraan via `VehicleImageService`; JPG/JPEG/PNG/WebP maksimal 2 MB.
- [x] Gambar lama dihapus saat replace; gambar dihapus saat kendaraan dihapus.
- [x] Detail kendaraan dengan foto, spesifikasi, dan riwayat rental.
- [x] Soft delete kendaraan hanya Admin; ditolak jika masih ada rental aktif.
- [x] Status manual kendaraan dibatasi `available`/`maintenance`; `rented` dikelola Phase 4.
- [x] Tipe dan status kendaraan dikunci saat memiliki rental aktif.
- [x] Link sidebar Pelanggan dan Kendaraan diaktifkan dengan perubahan layout minimal.
- [x] Feature test Phase 3 (27 test) termasuk validasi, unik terhadap data soft-deleted, upload, role, dan aturan hapus.
- [x] Screenshot desktop/mobile daftar pelanggan dan kendaraan.
- [x] Dokumentasi Phase 3, status, traceability, contribution log, phase plan, AI handoff, ADR-029, dan README diperbarui.
- [ ] Commit, push, PR, review, dan merge.

### 4.5 Verifikasi Phase 3

| Pemeriksaan | Hasil |
|---|---|
| `php artisan test` | 74 passed (293 assertions) |
| `vendor\bin\pint --test` | Passed |
| `npm run build` | Passed; CSS 56.57 kB (gzip 11.90 kB), JS 209.82 kB (gzip 72.05 kB) |
| `php artisan route:list --except-vendor` | Route `customers.*` dan `vehicles.*` terdaftar |
| `public/storage` | Symlink tersedia |
| Smoke test HTTP | Login Admin; daftar/create/detail/edit pelanggan dan kendaraan HTTP 200 |
| Role dan aturan hapus | Lulus via feature test (Staff 403; rental aktif menolak delete) |
| Upload/ganti gambar | Lulus via feature test (tersimpan, gambar lama terhapus) |
| Responsif | Screenshot 1440px dan 390px di `docs/assets/phase-03/` |
| Data transmisi lokal | Dinormalisasi menjadi `manual`/`automatic` sesuai schema |

## 5. Yang Sedang Dikerjakan

Tidak ada pekerjaan implementasi lain yang berjalan. Phase 3 selesai secara lokal dan menunggu
commit/push/PR/review. Phase 4 belum dimulai.

## 6. Langkah Berikutnya

1. Tunjukkan ringkasan perubahan dan hasil verifikasi Phase 3 kepada pemilik proyek.
2. Setelah disetujui: commit dengan Conventional Commits, push branch `phase/03-master-data`.
3. Buat PR ke `main`, minta reviewer anggota lain (bukan pemilik branch).
4. Review, lalu merge memakai merge commit (bukan squash).
5. Setelah merge, mulai Phase 4 dari `main` terbaru: rental dan pembayaran.
6. Phase 5: integrasi, SQL dump, screenshot final, laporan, dan presentasi.

## 7. Metrik Rencana

| Metrik | Target UAS | Rencana RentalGo | Status |
|---|---:|---:|---|
| Class OOP | ≥ 8 | 18 class + 1 interface + 6 enum | Tercapai |
| Inheritance | ≥ 2 | 2 (`Car`, `Motorcycle`) | Tercapai |
| Static method | ≥ 1 | 2+ | Tercapai |
| Tabel bisnis | ≥ 4 | 5 | Tercapai |
| Fitur tambahan | ≥ 2 | 6 | Tercapai: chart, search, pagination, sorting, filter, upload gambar |
| LOC | ≥ 1000 | Target ≥ 1000 | Belum dihitung final |
| Entitas CRUD | ≥ 3 | Pelanggan, kendaraan, pembayaran (+ lifecycle rental) | 2 dari 3 selesai lokal; pembayaran Phase 4 |
| Test suite | Wajib berjalan | Unit + feature | 74 passed (293 assertions) |

## 8. Pertanyaan Terbuka / Blockers

| No | Hal | Dampak | Status |
|---|---|---|---|
| 1 | Commit/push/PR Phase 3 | Review dan merge | Implementasi selesai; belum ada commit/PR |
| 2 | Reviewer Phase 3 | Syarat Definition of Done | Perlu anggota selain pemilik branch |
| 3 | Format UML final (Mermaid/PNG) | Presentasi/dokumen | Default: Mermaid + PNG jika diminta |
| 4 | Tanggal/format presentasi | Phase 5 | Menunggu informasi |
| 5 | Polish UI/UX dan tampilan tabel mobile | Kenyamanan demo | Ditunda ke Phase 5 sesuai arahan pemilik proyek |

Tidak ada blocker teknis untuk review Phase 3.

## 9. Catatan Environment

- PHP CLI 8.4.12; Laravel 13.44.1; Node 22.23.4.
- Database aktif di `.env`: MySQL 8.4.3 Laragon, `127.0.0.1:3306`, database `rentalgo`, password kosong.
- MySQL Laragon dijalankan manual (bukan Windows service) dan perlu dinyalakan ulang setelah reboot.
- `public/storage` symlink tersedia untuk gambar kendaraan.
- PHP CLI menampilkan peringatan startup `ext/curl` yang diblokir kebijakan sistem; Artisan tetap berjalan.
  Ini masalah environment lokal, bukan bug proyek.
- Ekstensi `intl` belum aktif; aplikasi memakai `number_format`, bukan `Number::format`.
- Chart.js 4.6.2 terpasang melalui npm; `npm run build` menghasilkan aset di `public/build`.
- Screenshot Phase 3 diambil dengan Chrome headless pada aplikasi lokal dan data seeder MySQL.
- Docker tidak digunakan.

## 10. Aturan Pembaruan Status

1. Setiap fase wajib memperbarui file ini setelah pekerjaan selesai dan diverifikasi.
2. Jangan menandai fase selesai sebelum PR direview dan digabung.
3. Jangan menulis "100%" untuk implementasi yang belum diuji.
4. Catat blocker dan pertanyaan terbuka secara eksplisit.
5. Sertakan tanggal dan nama pembaru.
