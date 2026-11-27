# Status Proyek RentalGo

| Item | Nilai |
|---|---|
| Fase saat ini | Phase 4 — Transaksi Rental & Pembayaran (sudah di-merge, PR #4) |
| Status fase | Implementasi dan verifikasi lokal selesai oleh Jason (`phase/04-rental-payment`); 96 test lulus (499 assertions); Pint passed; Vite build passed; MySQL `migrate:fresh --seed` dan smoke test browser passed; 11 screenshot tersedia; PR #4 sudah di-merge ke `main` (`9bcdf98`) |
| Branch | `phase/04-rental-payment` (pushed; commit `448993f`, `9a6073d`, `ce5ae7d`) |
| Implementasi aplikasi | Auth, layout, dashboard, portal pelanggan, CRUD pelanggan, CRUD kendaraan, transaksi rental (create/edit/return/cancel), dan CRUD pembayaran (record/edit/delete pending/mark-paid/mark-failed) selesai |
| Terakhir diperbarui | 26 November 2026 |
| Diperbarui oleh | Tim RentalGo (branch `phase/04-rental-payment`) |

## 1. Ringkasan Status

Phase 0 (dokumentasi), Phase 1 (fondasi), Phase 2 (autentikasi, layout, dashboard, portal pelanggan),
dan Phase 3 (CRUD pelanggan & kendaraan) sudah selesai dan digabung ke `main`. Phase 1 melalui PR #1
(`1b5caee`), Phase 2 melalui PR #2 (`c4847c2`), dan Phase 3 melalui PR #3 (`9a738fb`). Fondasi
migration, model, enum, domain OOP, service, factory, seeder, layout, komponen Blade, serta halaman
master data sudah tersedia di `main`.

Phase 4 sudah diimplementasikan pada branch `phase/04-rental-payment` oleh Jason
(`jason.422024004@civitas.ukrida.ac.id`) dengan commit `448993f` (implementasi) dan `9a6073d` (test).
Scope yang selesai: `RentalController`, `PaymentController`, `PaymentService`, 5 Form Request,
8 view Blade (rentals + payments), routes, sidebar, JS estimator/sisa tagihan, perbaikan
`scopeOverlapping`, penguatan transaksi dengan `lockForUpdate`, dan 22 feature test baru.
Total suite: **96 passed, 499 assertions**. Pint dan Vite build lulus. Verifikasi MySQL lokal dengan
`migrate:fresh --seed` dan smoke test browser (rental motor 7 hari, pembayaran DP, pelunasan,
pengembalian terlambat, pembatalan) juga lulus; 11 screenshot disimpan di `docs/assets/phase-04/`.

PR #4 sudah di-merge ke `main` (merge commit `9bcdf98`). Link PR:
https://github.com/Jehian/rentalgo-oop/pull/4. Reviewer (`StevenZega`, `Steven-422024001`) sudah
diminta untuk review. Catatan review di repo belum ada.

## 2. Status Fase

| Fase | Status | Catatan |
|---|---|---|
| 0 — Requirements & Planning | Selesai | Dokumen dibuat dan di-commit di `main` |
| 1 — Foundation & Architecture | Merged | PR #1, merge commit `1b5caee`; 25 test saat itu |
| 2 — Auth, Layout & Dashboard | Merged | PR #2, merge commit `c4847c2`; termasuk portal pelanggan; 47 test saat itu |
| 3 — Master Data CRUD | Merged | PR #3, merge commit `9a738fb`; CRUD pelanggan/kendaraan, upload gambar, 74 test saat itu |
| 4 — Rental & Payment Workflow | Merged (PR #4, `9bcdf98`) | Commit `448993f`, `9a6073d`, `ce5ae7d`; 96 test; MySQL + browser smoke test; 11 screenshot |
| 5 — Integration & Delivery | Belum dimulai | Dependensi: Phase 2–4 |

## 3. Progres Requirement

| Area | Status |
|---|---|
| Requirement UAS diekstrak | Selesai (66 ID) |
| Traceability matrix | Diperbarui; requirement Phase 1–4 berstatus `Implemented`; `Verified` menunggu bukti review PR #4 |
| PRD | Selesai |
| Arsitektur dan UML | Selesai (dokumen) |
| Database schema dan ERD | Selesai (dokumen) |
| Phase plan dan pembagian tim | Selesai |
| Workflow Git dan AI handoff | Selesai |
| Implementasi OOP | Domain/service, dashboard, `VehicleImageService`, `PaymentService`, dan `RentalService` |
| Database/migration | 5 tabel bisnis + role (Phase 1) + kolom auth pelanggan dan `rentals.user_id` nullable (Phase 2); tidak ada perubahan Phase 3/4 |
| Autentikasi | Implemented & merged (Phase 2) |
| Layout dan komponen UI | Implemented & merged (Phase 2); link master/transaksi aktif |
| Portal pelanggan | Implemented & merged (Phase 2) |
| Dashboard | Implemented & merged (Phase 2) |
| CRUD pelanggan | Implemented & merged (Phase 3) |
| CRUD kendaraan | Implemented & merged (Phase 3) |
| Rental dan pembayaran | Implemented + merged (Phase 4) |
| Test | 96 passed (499 assertions) |
| SQL dump | 0% (Phase 5) |
| Screenshot | Login/dashboard (Phase 2), pelanggan/kendaraan (Phase 3), rental/pembayaran (Phase 4) tersedia |
| Laporan akhir | 0% (Phase 5) |
| Presentasi | 0% (Phase 5) |

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
- [x] PR #1 di-merge (`1b5caee`); review oleh Steven Credentia Ivanemaga Zega diklaim tim, belum tercatat di GitHub.

### 4.3 Phase 2 — Authentication, Layout & Dashboard (merged)

- [x] Login/logout/session operator (`web` guard) + login/registrasi/logout pelanggan (`customer` guard).
- [x] Middleware `EnsureUserIsAdmin` (`admin`) serta isolasi guard.
- [x] Layout operator, guest, dan publik/pelanggan + 7 komponen Blade reusable.
- [x] Dashboard dengan kartu ringkasan, grafik Chart.js, dan rental terbaru.
- [x] Portal pelanggan: katalog, detail/estimasi, booking mandiri, riwayat, pembatalan, profil.
- [x] Feature test auth, dashboard, dan portal pelanggan (47 test total saat itu).
- [x] PR #2 digabung ke `main` melalui merge commit `c4847c2`.

### 4.4 Phase 3 — Master Data CRUD (merged)

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
- [x] Commit, push, PR #3, dan merge ke `main` (`9a738fb`); review belum tercatat di GitHub.

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

### 4.6 Phase 4 — Rental & Payment Workflow (implemented + verified locally)

- [x] `RentalController`: index (search/filter status-tanggal/sort/pagination), create, store, show, edit, update, return, cancel.
- [x] `PaymentController`: index (search/filter status-metode/sort/pagination), create, store, show, edit, update, destroy, mark-paid, mark-failed.
- [x] `PaymentService`: record, update pending, mark paid/failed, delete pending, remaining bill; transaksi + row lock.
- [x] `RentalService`: create/update/complete/cancel dengan transaksi, row lock kendaraan, guard overlap, dan denda.
- [x] 5 Form Request: `StoreRentalRequest`, `UpdateRentalRequest`, `ReturnRentalRequest`, `StorePaymentRequest`, `UpdatePaymentRequest`.
- [x] 8 view Blade: `rentals/index`, `rentals/create`, `rentals/edit`, `rentals/show`, `payments/index`, `payments/create`, `payments/edit`, `payments/show`.
- [x] Routes `rentals.*` dan `payments.*` di bawah `auth:web`; tidak ada route destroy rental.
- [x] Sidebar Rental dan Pembayaran aktif.
- [x] JS estimator biaya rental (termasuk diskon motor mingguan) dan sync sisa tagihan pembayaran.
- [x] Perbaikan `Rental::scopeOverlapping` (`whereDate`) untuk konsistensi SQLite/MySQL.
- [x] Guard pembayaran lunas dipindahkan ke service; transaksi tidak lagi di controller.
- [x] 22 feature test baru; total 96 passed (499 assertions).
- [x] MySQL `migrate:fresh --seed` berhasil; smoke test browser dan verifikasi database lulus.
- [x] 11 screenshot desktop/mobile di `docs/assets/phase-04/`.
- [x] Dokumentasi Phase 4, status, traceability, contribution log, phase plan, AI handoff, ADR-031, dan README diperbarui.
- [x] Commit implementasi `448993f`, test `9a6073d`, dan dokumentasi `ce5ae7d`.
- [x] Push branch dan buat PR #4.
- [x] Merge PR #4 ke `main` (`9bcdf98`); catatan review di repo belum ada.

### 4.7 Verifikasi Phase 4

| Pemeriksaan | Hasil |
|---|---|
| `php artisan test` | 96 passed (499 assertions) |
| `vendor\bin\pint --test` | Passed |
| `npm run build` | Passed; CSS 62.30 kB (gzip 12.60 kB), JS 212.44 kB (gzip 72.73 kB) |
| `php artisan view:cache` | Blade templates cached successfully |
| `php artisan route:list --except-vendor` | Route `rentals.*` dan `payments.*` terdaftar lengkap |
| MySQL `migrate:fresh --seed` | Berhasil pada MySQL 8.4.7, database `rentalgo` |
| Smoke test browser | Login, daftar rental, create rental motor 7 hari, estimator, payment pending → paid, return + denda, cancel — semua lulus |
| Error console browser | Tidak ada `Runtime.exceptionThrown` atau `Log.entryAdded` level error |
| Verifikasi database | Rental `#13` completed + denda Rp 120.931; payment `#13` paid Rp 100.000; rental `#14` cancelled; kendaraan kembali `available` |
| Responsif | Screenshot 1440px dan 390px di `docs/assets/phase-04/` |
| Reviewer | Merged (`9bcdf98`); catatan review di repo belum ada |

## 5. Yang Sedang Dikerjakan

Phase 4 sudah di-merge ke `main` (`9bcdf98`). Pekerjaan
yang tersisa hanya catatan review PR #4 di repo, jika ada.
Tidak ada pekerjaan implementasi lain yang berjalan.

## 6. Langkah Berikutnya

1. Catat review PR #4 di repo jika sudah dilakukan (`php artisan test`, Pint, dan
   memeriksa screenshot/smoke test.
2. Merge PR #4 sudah selesai (`9bcdf98`).
3. Mulai Phase 5 dari `main` terbaru: E2E, SQL dump, screenshot final, laporan akhir, presentasi, dan
   finalisasi contribution log berdasarkan `git log`.

## 7. Metrik Rencana

| Metrik | Target UAS | Rencana RentalGo | Status |
|---|---:|---:|---|
| Class OOP | ≥ 8 | 18 class + 1 interface + 6 enum | Tercapai |
| Inheritance | ≥ 2 | 2 (`Car`, `Motorcycle`) | Tercapai |
| Static method | ≥ 1 | 2+ | Tercapai |
| Tabel bisnis | ≥ 4 | 5 | Tercapai |
| Fitur tambahan | ≥ 2 | 6 | Tercapai: chart, search, pagination, sorting, filter, upload gambar |
| LOC | ≥ 1000 | Target ≥ 1000 | Belum dihitung final |
| Entitas CRUD | ≥ 3 | Pelanggan, kendaraan, pembayaran (+ lifecycle rental) | 3 dari 3 selesai |
| Test suite | Wajib berjalan | Unit + feature | 96 passed (499 assertions) |

## 8. Pertanyaan Terbuka / Blockers

| No | Hal | Dampak | Status |
|---|---|---|---|
| 1 | PR Phase 4 | Catatan review | PR #4 sudah di-merge ke `main` (`9bcdf98`); catatan review di repo belum ada |
| 2 | Reviewer Phase 4 | Syarat Definition of Done | `StevenZega` dan `Steven-422024001` sudah diminta review |
| 3 | Format UML final (Mermaid/PNG) | Presentasi/dokumen | Default: Mermaid + PNG jika diminta |
| 4 | Tanggal/format presentasi | Phase 5 | Menunggu informasi |
| 5 | Polish UI/UX dan tampilan tabel mobile | Kenyamanan demo | Ditunda ke Phase 5 sesuai arahan pemilik proyek |
| 6 | Finalisasi kontribusi | Penilaian individu | Log berbasis task/commit disiapkan; final di Phase 5 |
| 7 | Review PR #1–#4 tidak tercatat di GitHub | Bukti review untuk penilaian individu | Selesai: aturan review dihapus (ADR-032); konfirmasi tim lewat WhatsApp |
| 8 | Commit `1fd5f7b` (dokumentasi) langsung ke `main` tanpa PR | Menyimpang dari aturan PR (ADR-014 hanya untuk Phase 0) | Keputusan tim: terima, atau buat PR susulan |
| 9 | Urutan fase: PHASE_PLAN §6 mengizinkan paralel, AGENTS §6 mewajibkan prasyarat merged dulu | Dua aturan tidak sama | Keputusan tim: pilih satu aturan dan perbarui dokumen |

Tidak ada blocker teknis untuk review Phase 4.

## 9. Catatan Environment

- PHP CLI 8.4.15; Laravel 13.44.1; Node 22.16.0.
- Database aktif di `.env`: MySQL 8.4.7 WAMP, `127.0.0.1:3306`, database `rentalgo`, password kosong.
- MySQL WAMP dijalankan manual dan perlu dinyalakan ulang setelah reboot.
- `public/storage` symlink tersedia untuk gambar kendaraan.
- PHP CLI menampilkan peringatan startup `ext/curl` yang diblokir kebijakan sistem; Artisan tetap berjalan.
  Ini masalah environment lokal, bukan bug proyek.
- Ekstensi `intl` belum aktif; aplikasi memakai `number_format`, bukan `Number::format`.
- Chart.js 4.6.2 terpasang melalui npm; `npm run build` menghasilkan aset di `public/build`.
- Screenshot Phase 4 diambil dengan Chrome headless pada aplikasi lokal dan data seeder MySQL.
- Docker tidak digunakan.

## 10. Aturan Pembaruan Status

1. Setiap fase wajib memperbarui file ini setelah pekerjaan selesai dan diverifikasi.
2. Jangan menandai fase selesai sebelum PR digabung.
3. Jangan menulis "100%" untuk implementasi yang belum diuji.
4. Catat blocker dan pertanyaan terbuka secara eksplisit.
5. Sertakan tanggal dan nama pembaru.
6. Kontribusi dicatat berbasis task/commit di `docs/CONTRIBUTION_LOG.md`; rekap final dilakukan pada Phase 5.
