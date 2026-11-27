# Phase Report — Phase 4: Transaksi Rental & Pembayaran

| Item | Keterangan |
|---|---|
| Fase | Phase 4 — Transaksi Rental & Pembayaran |
| Pelaksana commit | Jason (`jason.422024004@civitas.ukrida.ac.id`) |
| Reviewer / Merge | Merged ke `main` (`9bcdf98`); catatan review di repo belum ada |
| Branch | `phase/04-rental-payment` (pushed ke `origin`) |
| Pull Request | PR #4 — https://github.com/Jehian/rentalgo-oop/pull/4 |
| Commit implementasi | `448993f` — `feat(rental-payment): implement rental and payment workflow` |
| Commit test | `9a6073d` — `test(rental-payment): cover rental lifecycle and payment workflow` |
| Commit dokumentasi | `ce5ae7d` — `docs(phase-4): document rental and payment verification` |
| Tanggal mulai | 2026-11-26 |
| Tanggal selesai | 2026-11-26 |
| Status | Merged ke `main` (PR #4, `9bcdf98`) |

---

## 1. Ringkasan

Phase 4 mengimplementasikan alur transaksi rental dan pembayaran sesuai scope `docs/PHASE_PLAN.md`.
`RentalController` menangani daftar, create, detail, edit, pengembalian, dan pembatalan rental.
`PaymentController` dan `PaymentService` menangani pencatatan, pembaruan, penghapusan pembayaran
pending, serta aksi tandai lunas/gagal. Seluruh aturan harga, ketersediaan, dan denda tetap berada di
`RentalService` dan domain kendaraan; controller hanya memvalidasi input dan mengarahkan response.

Dua puluh dua feature test baru ditambahkan; total suite menjadi **96 passed, 499 assertions**.
Pint dan Vite production build lulus. Verifikasi MySQL lokal dilakukan dengan
`php artisan migrate:fresh --seed`, lalu smoke test browser untuk alur rental motor 7 hari
(diskon mingguan), pembayaran DP, pelunasan, pengembalian terlambat, dan pembatalan rental.
Sebelas screenshot desktop/mobile disimpan di `docs/assets/phase-04/`.

---

## 2. Tujuan vs Hasil

| Tujuan | Hasil | Bukti |
|---|---|---|
| Implementasi `RentalController` CRUD + return + cancel | Tercapai | `app/Http/Controllers/RentalController.php`; 12 feature test |
| Implementasi `PaymentController` CRUD + mark-paid + mark-failed | Tercapai | `app/Http/Controllers/PaymentController.php`; 10 feature test |
| `PaymentService` dengan logika bisnis & transaksi DB | Tercapai | `app/Services/PaymentService.php`; test isolasi di `PaymentCrudTest` |
| Form Request validasi rental (3 kelas) | Tercapai | `StoreRentalRequest`, `UpdateRentalRequest`, `ReturnRentalRequest` |
| Form Request validasi payment (2 kelas) | Tercapai | `StorePaymentRequest`, `UpdatePaymentRequest` |
| View Blade rental (index/create/edit/show) | Tercapai | `resources/views/rentals/*` |
| View Blade payment (index/create/edit/show) | Tercapai | `resources/views/payments/*` |
| Sidebar aktif untuk Rental dan Pembayaran | Tercapai | `resources/views/layouts/app.blade.php` |
| JS estimator biaya rental di form create/edit | Tercapai | `resources/js/app.js` — `data-rental-estimator` |
| JS sync sisa tagihan di form payment create | Tercapai | `resources/js/app.js` — `data-payment-form` |
| Denda keterlambatan otomatis via `RentalService` | Tercapai | `RentalService::completeRental()`; smoke test denda 2 hari |
| Test: 22 test baru (96 total, 499 assertions) | Tercapai | `php artisan test` — 96 passed |
| Pint lulus | Tercapai | `vendor/bin/pint --test` — passed |
| Vite production build | Tercapai | `npm run build` — CSS 62.30 kB, JS 212.44 kB |
| Verifikasi MySQL + browser | Tercapai | `migrate:fresh --seed`, smoke test, 11 screenshot |
| Dokumentasi diperbarui | Tercapai | Traceability, DECISIONS, PHASE_PLAN, README, CONTRIBUTION_LOG, AI_HANDOFF, status |

---

## 3. Scope yang Diselesaikan

- [x] `PaymentService` — `record()`, `updatePending()`, `markPaid()`, `markFailed()`, `deletePending()`, `remainingBill()`.
- [x] `RentalController` — index (search/filter/sort/paginate), create, store, show, edit, update, returnVehicle, cancel.
- [x] `PaymentController` — index, create, store, show, edit, update, destroy, markPaid, markFailed.
- [x] 5 Form Request: `StoreRentalRequest`, `UpdateRentalRequest`, `ReturnRentalRequest`, `StorePaymentRequest`, `UpdatePaymentRequest`.
- [x] 4 view rental: `rentals/index`, `rentals/create`, `rentals/edit`, `rentals/show`.
- [x] 4 view payment: `payments/index`, `payments/create`, `payments/edit`, `payments/show`.
- [x] Routes `routes/rental.php` — resource rentals (tanpa destroy) + return + cancel; resource payments + mark-paid + mark-failed; semua di bawah `auth:web`.
- [x] Sidebar link Rental dan Pembayaran diaktifkan (`app.blade.php`).
- [x] JS estimator biaya di form rental (pilih kendaraan → update tarif/hari/total).
- [x] JS sync sisa tagihan di form pembayaran (pilih rental → isi sisa tagihan otomatis).
- [x] Perbaikan `Rental::scopeOverlapping` — ganti `where()` → `whereDate()` agar SQLite dan MySQL konsisten.
- [x] Perbaikan `RentalService::updateRental()` — `array_key_exists` untuk izinkan `notes` dikosongkan.
- [x] Penguatan transaksi: `createRental()` mengunci baris kendaraan dan memeriksa ketersediaan di dalam `DB::transaction`; `updateRental()`, `completeRental()`, dan `cancelRental()` memakai `lockForUpdate`.
- [x] Guard “total baru tidak boleh lebih kecil dari pembayaran lunas” dipindahkan dari controller ke `RentalService`.
- [x] ADR-031 di `docs/DECISIONS.md` — aturan rental workflow, payment constraint, `whereDate()`, dan locking.
- [x] Feature test `tests/Feature/Rentals/RentalCrudTest.php` — 12 test.
- [x] Feature test `tests/Feature/Payments/PaymentCrudTest.php` — 10 test.

---

## 4. File yang Dibuat/Diubah

### File Baru

| File | Keterangan |
|---|---|
| `app/Services/PaymentService.php` | Layanan pembayaran: record, update, mark-paid, mark-failed, delete, remaining |
| `app/Http/Requests/StoreRentalRequest.php` | Validasi buat rental (customer, vehicle, tanggal, notes) |
| `app/Http/Requests/UpdateRentalRequest.php` | Validasi edit rental (tanggal, notes) |
| `app/Http/Requests/ReturnRentalRequest.php` | Validasi pengembalian kendaraan (returned_at) |
| `app/Http/Requests/StorePaymentRequest.php` | Validasi buat payment (rental_id, amount, method, status, paid_at, notes) |
| `app/Http/Requests/UpdatePaymentRequest.php` | Validasi edit payment pending |
| `app/Http/Controllers/RentalController.php` | Controller full CRUD rental + return + cancel |
| `app/Http/Controllers/PaymentController.php` | Controller full CRUD payment + mark-paid + mark-failed |
| `resources/views/rentals/index.blade.php` | Daftar rental — search, filter status/tanggal, sort, pagination |
| `resources/views/rentals/create.blade.php` | Form buat rental — select kendaraan/pelanggan, estimator biaya |
| `resources/views/rentals/edit.blade.php` | Form edit rental — panel info terkunci + tanggal/notes |
| `resources/views/rentals/show.blade.php` | Detail rental — info, tagihan, tabel payment, modal return/cancel |
| `resources/views/payments/index.blade.php` | Daftar payment — search, filter status/metode, sort, pagination |
| `resources/views/payments/create.blade.php` | Form buat payment — sync sisa tagihan JS |
| `resources/views/payments/edit.blade.php` | Form edit payment pending |
| `resources/views/payments/show.blade.php` | Detail payment — tombol mark-paid, mark-failed, edit, delete |
| `tests/Feature/Rentals/RentalCrudTest.php` | 12 feature test skenario rental lifecycle |
| `tests/Feature/Payments/PaymentCrudTest.php` | 10 feature test skenario payment lifecycle |
| `docs/assets/phase-04/*.png` | 11 screenshot desktop/mobile hasil smoke test |

### File Diubah

| File | Keterangan |
|---|---|
| `routes/rental.php` | Implementasi penuh semua route rental dan payment |
| `resources/views/layouts/app.blade.php` | Aktifkan link sidebar Rental dan Pembayaran |
| `resources/js/app.js` | Tambah blok JS `data-rental-estimator` dan `data-payment-form` |
| `app/Models/Rental.php` | `scopeOverlapping`: `where()` → `whereDate()` (fix SQLite boundary) |
| `app/Services/RentalService.php` | `array_key_exists` untuk notes; transaksi + `lockForUpdate`; guard pembayaran lunas |
| `docs/DECISIONS.md` | Tambah ADR-031 (rental workflow, payment rules, `whereDate` fix, locking) |
| `docs/PHASE_PLAN.md` | Perbaiki status Phase 3; centang acceptance criteria Phase 4; tambah `ReturnRentalRequest` |
| `docs/UAS_TRACEABILITY.md` | Tandai requirement Phase 4 `Implemented` dan catat bukti verifikasi |
| `docs/CONTRIBUTION_LOG.md` | Tambah baris Phase 4 (Jason, commit `448993f`, `9a6073d`) |
| `docs/PROJECT_STATUS.md` | Perbarui status ke Phase 4 implementasi + verifikasi lokal selesai |
| `README.md` | Perbarui status fitur, screenshot, dan daftar fitur Phase 2–4 |
| `docs/AI_HANDOFF.md` | Perbarui kondisi terkini dan handoff Phase 5 |
| `AGENTS.md` | Perbarui status repository dan branch Phase 4 |

---

## 5. Perubahan Database

**Tidak ada.** Tidak ada migration baru. Schema `rentals` dan `payments` sudah lengkap dari Phase 1
(`database/migrations/`). Phase 4 hanya mengimplementasikan layer controller/service/view di atas
schema yang ada.

---

## 6. Pemetaan OOP dan UAS

| Requirement | Implementasi | File | Verifikasi |
|---|---|---|---|
| UAS-FEA-05 Transaksi rental | CRUD rental + lifecycle (active → completed/cancelled) | `RentalController`, `RentalService` | `RentalCrudTest` — 12 test + smoke test |
| UAS-FEA-06 Pembayaran | Record, update, mark-paid/failed; guard locked status | `PaymentController`, `PaymentService` | `PaymentCrudTest` — 10 test + smoke test |
| UAS-FEA-07 Denda keterlambatan | `RentalService::completeRental()` hitung denda berdasarkan `config/rental.php` | `RentalService` | `test_return_vehicle_with_late_fee` + smoke test |
| UAS-FEA-08 Pengecekan ketersediaan kendaraan | `scopeOverlapping`, guard di `RentalService::createRental()` dengan row lock | `Rental`, `RentalService` | `test_store_rental_rejects_unavailable_vehicle` |
| UAS-OOP-01 Class & enkapsulasi | `PaymentService` — private method pembantu; property terlindungi | `PaymentService` | Pint lulus |
| UAS-OOP-02 Inheritance | `Car`, `Motorcycle` extends abstract `Vehicle` — dipakai dalam pricing rental | `Vehicle`, `Car`, `Motorcycle` | Unit test Phase 1 |
| UAS-OOP-03 Polymorphism | `calculateRentalCost()` dipanggil via `Rentable` interface di `RentalService` | `RentalService`, `VehicleFactory` | `RentalCrudTest` |
| UAS-OOP-07 Static method | `VehicleFactory::make()`, `DocumentCodeGenerator::generate()` | Phase 1 | Phase 1 test |
| UAS-ARC MVC | Route → Middleware → Controller → Form Request → Service → Model → DB | Seluruh Phase 4 | Test + build + smoke test |
| UAS-INT-05 Relasi rental–vehicle | `Rental::vehicle()` eager loaded di index; guard overlap | `RentalController`, `Rental` | Test |
| UAS-INT-07 Relasi rental–payment | `Rental::payments()`, `Payment::rental()`; sisa tagihan dari `remainingBill()` | `PaymentService`, `PaymentController` | `PaymentCrudTest` |

---

## 7. Pengujian dan Verifikasi

### 7.1 Perintah

```bash
php artisan test
vendor/bin/pint --test
npm run build
php artisan view:cache
php artisan migrate:fresh --seed
php artisan route:list --except-vendor
```

### 7.2 Hasil Otomatis

```text
php artisan test
  Tests:    96 passed (499 assertions)
  Duration: 16.33s

vendor/bin/pint --test
  PASS — no style issues found.

npm run build
  vite v8.3.8 building client environment for production...
  ✓ 7 modules transformed.
  public/build/assets/app-A9EKM53q.css   62.30 kB │ gzip: 12.60 kB
  public/build/assets/app-DEkTFlyS.js   212.44 kB │ gzip: 72.73 kB
  ✓ built in 6.96s

php artisan view:cache
  Blade templates cached successfully.

php artisan route:list --except-vendor
  Route rentals.* dan payments.* terdaftar lengkap.
```

### 7.3 Verifikasi MySQL

- `php artisan migrate:fresh --seed` berhasil pada MySQL 8.4.7 lokal, database `rentalgo`.
- Seeder menghasilkan 8 pelanggan, 4 mobil + 4 motor, 12 rental, dan 12 pembayaran.
- Setelah smoke test, database diperiksa langsung:
  - Rental `RNT-20261126-7VCD` berstatus `completed`, durasi 7 hari, total `761.865,30`, denda `120.931,00`, `returned_at` `2026-12-04 10:00:00`.
  - Payment `PAY-20261126-YQUQ` berstatus `paid`, nominal `100.000,00`.
  - Rental `RNT-20261126-U9AB` berstatus `cancelled`.
  - Kendaraan yang dipakai kembali berstatus `available`.

### 7.4 Manual Check Browser

Smoke test dijalankan dengan Chrome headless melalui Chrome DevTools Protocol pada aplikasi lokal
(`php artisan serve`, MySQL, aset Vite production build).

| Skenario | Langkah | Hasil |
|---|---|---|
| Login operator | Login `staff@rentalgo.test` | Berhasil, redirect dashboard |
| Daftar rental | Buka `/rentals` | 10 baris per halaman, filter/pagination tampil |
| Responsif daftar rental | Viewport 390×844 | Layout mobile tampil, tanpa error console |
| Buat rental motor | Pilih pelanggan + Honda Aerox, 26/11/2026–02/12/2026 | Rental `#13` dibuat, redirect detail |
| Estimator biaya | Pilih motor 7 hari | Durasi 7 hari, total Rp 761.865, diskon 10% tampil |
| Catat pembayaran | Dari detail rental, isi nominal Rp 100.000, status pending | Payment `#13` dibuat, sisa tagihan tersinkron |
| Tandai lunas | Klik “Tandai Lunas” | Status payment menjadi `paid` |
| Daftar pembayaran | Buka `/payments` desktop + mobile | 10 baris, filter status/metode tampil |
| Pengembalian + denda | Return `2026-12-04 10:00` untuk rental yang berakhir `2026-12-02` | Status `completed`, denda Rp 120.931 tampil |
| Pembatalan rental | Buat rental `#14`, klik “Ya, Batalkan” | Status `cancelled`, kendaraan kembali `available` |
| Error console browser | Pantau `Runtime.exceptionThrown` dan `Log.entryAdded` | Tidak ada error |

### 7.5 Catatan Teknis

- Test otomatis memakai SQLite in-memory; verifikasi MySQL dilakukan manual dengan data seeder.
- Pengecekan ketersediaan dan pembuatan rental sekarang berada dalam satu transaksi dengan
  `lockForUpdate()` pada baris kendaraan, sehingga dua request bersamaan tidak dapat double-book.
- `PaymentService` membatasi nominal satu pembayaran agar tidak melebihi sisa tagihan berdasarkan
  pembayaran `paid`; aturan ini juga berlaku untuk pembayaran pending dan dicatat di ADR-031.
- Guard “total baru tidak boleh lebih kecil dari pembayaran lunas” berada di `RentalService`, bukan
  controller, agar aturan bisnis tetap satu sumber.

---

## 8. Screenshot

Semua screenshot diambil dari aplikasi lokal dengan data seeder MySQL dan disimpan di
`docs/assets/phase-04/`.

| Halaman | File | Ukuran |
|---|---|---|
| Daftar Rental — desktop | [`rentals-desktop.png`](../assets/phase-04/rentals-desktop.png) | 1425×1188 |
| Daftar Rental — mobile | [`rentals-mobile.png`](../assets/phase-04/rentals-mobile.png) | 390×1701 |
| Form Buat Rental — desktop | [`rental-create-desktop.png`](../assets/phase-04/rental-create-desktop.png) | 1440×900 |
| Detail Rental — desktop | [`rental-show-desktop.png`](../assets/phase-04/rental-show-desktop.png) | 1425×926 |
| Form Catat Pembayaran — desktop | [`payment-create-desktop.png`](../assets/phase-04/payment-create-desktop.png) | 1440×900 |
| Detail Pembayaran — desktop | [`payment-show-desktop.png`](../assets/phase-04/payment-show-desktop.png) | 1440×900 |
| Detail Pembayaran Lunas — desktop | [`payment-paid-desktop.png`](../assets/phase-04/payment-paid-desktop.png) | 1440×900 |
| Daftar Pembayaran — desktop | [`payments-desktop.png`](../assets/phase-04/payments-desktop.png) | 1425×1088 |
| Daftar Pembayaran — mobile | [`payments-mobile.png`](../assets/phase-04/payments-mobile.png) | 390×1466 |
| Rental Selesai + Denda — desktop | [`rental-returned-desktop.png`](../assets/phase-04/rental-returned-desktop.png) | 1440×900 |
| Rental Dibatalkan — desktop | [`rental-cancelled-desktop.png`](../assets/phase-04/rental-cancelled-desktop.png) | 1425×926 |

---

## 9. Masalah, Deviasi, dan Keterbatasan

**Masalah yang ditemukan dan diperbaiki:**

1. **`Rental::scopeOverlapping` — boundary check gagal di SQLite.**
   SQLite menyimpan kolom DATE sebagai teks `YYYY-MM-DD 00:00:00`; `where('start_date', '<=', $endDate)`
   dengan string tanggal menghasilkan false negative. Diperbaiki dengan `whereDate()`.
   ADR ditulis di `docs/DECISIONS.md` (ADR-031).

2. **`RentalService::updateRental()` — notes tidak bisa dikosongkan.**
   `$data['notes'] ?? $rental->notes` tidak bisa membedakan “tidak ada key” vs “key ada tapi empty”.
   Diperbaiki dengan `array_key_exists('notes', $data)`.

3. **Cek ketersediaan di luar transaksi.**
   `createRental()` awalnya memeriksa ketersediaan sebelum `DB::transaction`, sehingga dua request
   bersamaan berpotensi double-book. Diperbaiki dengan mengunci baris kendaraan dan memeriksa
   ketersediaan di dalam transaksi.

4. **Guard pembayaran lunas di controller.**
   Guard “total baru tidak boleh lebih kecil dari pembayaran lunas” dipindahkan dari
   `RentalController@update` ke `RentalService::updateRental()` agar controller tetap tipis.

**Deviasi dari rencana:**

- Tidak ada. Scope Phase 4 sesuai `docs/PHASE_PLAN.md`; tidak ada migration atau dependency baru.

**Keterbatasan yang diketahui:**

- Verifikasi MySQL dan browser dilakukan pada mesin lokal Jason; reviewer tetap perlu menjalankan
  ulang test sebelum merge.
- Screenshot diambil dengan Chrome headless, bukan browser interaktif; modal return/cancel diuji
  melalui submit form langsung, bukan klik visual.
- E2E lintas modul, SQL dump, dan screenshot final seluruh aplikasi tetap menjadi scope Phase 5.

---

## 10. Handoff ke Fase Berikutnya

**Yang sudah stabil:**

- Seluruh alur rental (create → active → completed/cancelled) dan pembayaran (pending → paid/failed).
- `PaymentService` dengan guard locked status, overpayment, dan row lock.
- `RentalService` dengan transaksi, row lock kendaraan, guard overlap, dan denda.
- 96 test, 499 assertions — semua suite hijau.
- Smoke test MySQL + 11 screenshot Phase 4.

**Yang perlu dilanjutkan (Phase 5):**

- Push branch, buat PR, review, dan merge.
- SQL dump database setelah `migrate:fresh --seed` + data demo.
- Screenshot final semua halaman untuk laporan dan presentasi.
- Laporan akhir dan slide presentasi.
- Finalisasi `docs/CONTRIBUTION_LOG.md` dengan `git log` aktual setelah merge.
- Polish UI/UX (pagination payment di show rental, mobile responsif form).
- Dashboard widget: total rental aktif, total pendapatan bulan ini (opsional jika waktu cukup).

**File/kontrak yang harus dipakai:**

- `app/Services/RentalService.php` — `createRental`, `updateRental`, `completeRental`, `cancelRental`.
- `app/Services/PaymentService.php` — `record`, `updatePending`, `markPaid`, `markFailed`, `deletePending`, `remainingBill`.
- Enum: `RentalStatus`, `PaymentStatus`, `PaymentMethod` (di `app/Enums/`).
- Exception: `VehicleNotAvailableException` (jika perlu trap di Phase 5).

**Peringatan/risiko:**

- Phase 3 sudah merged ke `main` melalui PR #3 (`9a738fb`); branch Phase 4 sudah berbasis commit
  tersebut, jadi tidak ada lagi ketergantungan branch.
- Pastikan `git config user.name` dan `git config user.email` sudah benar sebelum commit/push.
- Jangan force-push branch yang sedang direview.

---

## 11. Checklist

- [x] Scope selesai.
- [x] Test ditambahkan dan lulus (96 passed, 499 assertions).
- [x] Pint lulus.
- [x] Vite build lulus.
- [x] MySQL `migrate:fresh --seed` berhasil.
- [x] Smoke test browser dan verifikasi database.
- [x] Screenshot desktop/mobile disertakan (11 file).
- [x] Dokumentasi status/traceability/contribution log diperbarui.
- [x] Tidak ada secret.
- [x] Branch di-push.
- [x] PR dibuat (PR #4).
- [ ] Merge ke `main` selesai.

---

## 12. Catatan Kontribusi

| Task | Kontributor (Author Email / Pelaksana) | Commit/PR | Bukti |
|---|---|---|---|
| `PaymentService` | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | File ada di `app/Services/PaymentService.php` |
| `RentalController`, `PaymentController` | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | `app/Http/Controllers/{Rental,Payment}Controller.php` |
| 5 Form Request | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | `app/Http/Requests/*` |
| Views rental & payment (8 file) | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | `resources/views/{rentals,payments}/*` |
| Routes, sidebar, JS | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | `routes/rental.php`, `app.blade.php`, `app.js` |
| 22 feature test | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `9a6073d` | `tests/Feature/{Rentals,Payments}/*` |
| Fix `scopeOverlapping`, locking, guard service | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` | `app/Models/Rental.php`, `app/Services/RentalService.php` |
| Dokumentasi & screenshot Phase 4 | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `ce5ae7d` | `docs/*`, `docs/assets/phase-04/*` |

> Kontribusi tidak dikunci per phase secara kaku; pencatatan mengikuti histori commit pada email
> Git masing-masing anggota dan direkonsiliasi di `docs/CONTRIBUTION_LOG.md` pada Phase 5.
