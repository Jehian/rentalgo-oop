# Rencana Fase dan Pembagian Tim — RentalGo

Dokumen ini membagi pekerjaan menjadi empat tanggung jawab anggota, dengan Phase 0 (perencanaan)
dan Phase 5 (integrasi) sebagai fase lintas tim. Tujuannya: konflik file minimal, dependensi jelas,
dan setiap anggota memiliki bukti kontribusi yang dapat dinilai.

## 1. Prinsip Pembagian Kerja

1. **Satu modul, satu pemilik utama.** File modul tidak diedit anggota lain tanpa koordinasi.
2. **Fondasi lebih dulu.** Schema, model, enum, domain OOP, dan seeder diselesaikan Phase 1.
3. **Dependensi ditulis eksplisit.** Tidak ada pekerjaan yang dimulai tanpa memahami prasyarat.
4. **Commit kecil dan bermakna.** Setiap anggota commit atas namanya sendiri.
5. **PR untuk implementasi.** Phase 0 adalah pengecualian dokumentasi di `main`.
6. **Definition of Done wajib.** Fitur dianggap selesai hanya jika kode, test, dan dokumentasi fase
   sudah lengkap serta direview.

## 2. Ringkasan Fase

| Fase | Nama | Pemilik | Branch | Status |
|---|---|---|---|---|
| 0 | Requirements & Planning | Anggota 1 | `main` (dokumentasi saja) | Selesai |
| 1 | Foundation & Architecture | Anggota 1 | `phase/01-foundation` | Committed lokal; belum PR |
| 2 | Auth, Layout & Dashboard | Anggota 2 | `phase/02-auth-dashboard` | Belum dimulai |
| 3 | Master Data CRUD | Anggota 3 | `phase/03-master-data` | Belum dimulai |
| 4 | Rental & Payment Workflow | Anggota 4 | `phase/04-rental-payment` | Belum dimulai |
| 5 | Integration, Testing & Delivery | Semua | `phase/05-integration` | Belum dimulai |

## 3. Detail Fase

### Phase 0 — Requirements Analysis & Planning

**Pemilik:** Anggota 1
**Branch:** `main` (khusus dokumentasi; disetujui pemilik proyek)
**Dependensi:** —

**Scope:**

- Membaca dan mengekstrak UAS.
- Menyusun PRD, requirement, traceability, arsitektur, database schema, keputusan teknis.
- Menyusun phase plan, workflow Git, template laporan, AI handoff.
- Tidak membuat kode aplikasi, migration, model, controller, service, view, atau dependency baru.

**Deliverable:**

- `PRD.md`
- `AGENTS.md`
- `README.md`
- `docs/UAS_REQUIREMENTS.md`
- `docs/UAS_TRACEABILITY.md`
- `docs/PHASE_PLAN.md`
- `docs/PROJECT_STATUS.md`
- `docs/DATABASE_SCHEMA.md`
- `docs/ARCHITECTURE.md`
- `docs/DECISIONS.md`
- `docs/AI_HANDOFF.md`
- `docs/CONTRIBUTION_LOG.md`
- `docs/templates/PHASE_REPORT_TEMPLATE.md`
- `docs/templates/AI_PHASE_PROMPT_TEMPLATE.md`
- `docs/progress/README.md`

**Acceptance criteria:**

- [x] Semua requirement eksplisit UAS tercatat dan memiliki ID.
- [x] Semua ID memiliki baris traceability.
- [x] PRD, arsitektur, database, dan phase plan tidak saling bertentangan.
- [x] Tidak ada perubahan kode aplikasi/konfigurasi/database.
- [x] Empat pembagian tanggung jawab didefinisikan.
- [x] Review dan persetujuan manusia.

**Handoff:** Phase 1 sudah diimplementasikan dan di-commit lokal; push/PR berikutnya.

---

### Phase 1 — Foundation & Architecture

**Pemilik:** Anggota 1
**Branch:** `phase/01-foundation`
**Dependensi:** Phase 0 disetujui

**Scope:**

- Konfigurasi aplikasi: nama, timezone, locale, `config/rental.php`.
- Migration 5 tabel bisnis + role user.
- Eloquent model, relasi, cast, scope.
- Domain OOP: `Rentable`, abstract `Vehicle`, `Car`, `Motorcycle`, `VehicleFactory`.
- Enum, custom exception, `DocumentCodeGenerator`.
- `RentalService` lengkap dengan unit/feature test.
- Factory dan seeder data demo.
- Route file kosong per modul + include di `routes/web.php`.
- Tidak membuat UI/auth/controller CRUD.

**File/modul utama:**

- `app/Contracts/`, `app/Domain/`, `app/Enums/`, `app/Exceptions/`, `app/Support/`
- `app/Models/User.php`, `Customer.php`, `Vehicle.php`, `Rental.php`, `Payment.php`
- `app/Services/RentalService.php`
- `config/app.php`, `config/rental.php`, `.env.example`
- `database/migrations/`, `database/factories/`, `database/seeders/`
- `routes/web.php`, `routes/auth.php`, `routes/dashboard.php`, `routes/master.php`, `routes/rental.php`
- `tests/Unit/Domain/`, `tests/Feature/Foundation/`

**Acceptance criteria:**

- [x] `php artisan migrate:fresh --seed` berhasil di MySQL dan SQLite test.
- [x] 5 tabel bisnis memiliki foreign key dan relasi yang benar.
- [x] Domain pricing mobil/motor lulus unit test.
- [x] `RentalService` mencegah double booking dan menghitung biaya/denda dengan benar.
- [x] Custom exception tertangkap dan pesannya jelas.
- [x] Semua test lulus; Pint bersih.
- [x] Tidak ada controller/view/auth yang dikerjakan.
- [x] Phase report, status, dan contribution log diperbarui.
- [ ] PR direview dan digabung.

**Handoff ke Phase 2–4:** schema stabil, model/relasi, seeder, domain/service, route stub.

---

### Phase 2 — Authentication, Layout & Dashboard

**Pemilik:** Anggota 2
**Branch:** `phase/02-auth-dashboard`
**Dependensi:** Phase 1 (users role, model, seeder) dan Phase 1 route stub

**Scope:**

- Custom login/logout/session; tidak ada registrasi publik.
- Middleware `auth` dan `EnsureUserIsAdmin`.
- Layout utama responsif + komponen Blade reusable.
- Dashboard: kartu ringkasan, grafik pendapatan/status, rental terbaru.
- `DashboardService`, `DashboardController`.
- Chart.js melalui npm.
- Feature test auth dan dashboard.

**File/modul utama:**

- `app/Http/Controllers/Auth/AuthController.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Services/DashboardService.php`
- `resources/views/layouts/`, `components/`, `auth/`, `dashboard/`
- `routes/auth.php`, `routes/dashboard.php`
- `tests/Feature/Auth/`, `tests/Feature/Dashboard/`
- `package.json` (Chart.js)

**Acceptance criteria:**

- [ ] Login valid/invalid, logout, dan proteksi route lulus feature test.
- [ ] Session regenerate saat login dan invalidate saat logout.
- [ ] Layout responsive di desktop/tablet/mobile.
- [ ] Dashboard menampilkan angka yang sesuai data seeder dan grafik tampil.
- [ ] Tidak ada akses halaman operasional tanpa login.
- [ ] Phase report, status, traceability, contribution log diperbarui.
- [ ] PR direview dan digabung.

**Handoff ke Phase 3–4:** layout, komponen, navigasi, pola controller/request/view.

---

### Phase 3 — Master Data CRUD (Customers & Vehicles)

**Pemilik:** Anggota 3
**Branch:** `phase/03-master-data`
**Dependensi:** Phase 1 (model/schema) dan Phase 2 (layout/komponen)

**Scope:**

- CRUD pelanggan: list, create, edit, soft delete, detail + riwayat rental.
- CRUD kendaraan: list, create, edit, soft delete, detail + riwayat rental.
- Search, pagination, sorting, filter tipe/status kendaraan.
- Upload/ganti gambar kendaraan + `VehicleImageService`.
- Validasi Form Request dan aturan hapus.
- Feature test CRUD dan upload.

**File/modul utama:**

- `app/Http/Controllers/CustomerController.php`, `VehicleController.php`
- `app/Http/Requests/StoreCustomerRequest.php`, `UpdateCustomerRequest.php`,
  `StoreVehicleRequest.php`, `UpdateVehicleRequest.php`
- `app/Services/VehicleImageService.php`
- `resources/views/customers/`, `vehicles/`
- `routes/master.php`
- `tests/Feature/Customers/`, `tests/Feature/Vehicles/`

**Acceptance criteria:**

- [ ] CRUD pelanggan dan kendaraan lulus feature test.
- [ ] Search, pagination, sorting, filter berfungsi.
- [ ] Upload gambar tervalidasi dan tampil; gambar lama dihapus saat replace.
- [ ] Soft delete ditolak jika ada rental aktif.
- [ ] Detail menampilkan riwayat rental.
- [ ] UI responsif dan konsisten dengan layout Phase 2.
- [ ] Phase report, status, traceability, contribution log diperbarui.
- [ ] PR direview dan digabung.

**Handoff ke Phase 4:** halaman/master data siap dipilih pada form rental.

---

### Phase 4 — Rental & Payment Workflow

**Pemilik:** Anggota 4
**Branch:** `phase/04-rental-payment`
**Dependensi:** Phase 1 (`RentalService`, domain), Phase 2 (layout), Phase 3 (data pelanggan/kendaraan)

**Scope:**

- Buat rental: availability check, durasi, biaya, transaction.
- List/detail rental, filter status/tanggal, pagination.
- Ubah rental aktif (tanggal/catatan) dengan validasi ulang.
- Pengembalian + denda; pembatalan.
- CRUD pembayaran (create/edit/delete pending, mark paid/failed), detail.
- `PaymentService`, `RentalController`, `PaymentController`, Form Requests, views.
- Feature test alur rental dan pembayaran.

**File/modul utama:**

- `app/Http/Controllers/RentalController.php`, `PaymentController.php`
- `app/Http/Requests/StoreRentalRequest.php`, `UpdateRentalRequest.php`,
  `StorePaymentRequest.php`, `UpdatePaymentRequest.php`
- `app/Services/PaymentService.php`
- `resources/views/rentals/`, `payments/`
- `routes/rental.php`
- `tests/Feature/Rentals/`, `tests/Feature/Payments/`

**Acceptance criteria:**

- [ ] Rental tidak dapat dibuat jika kendaraan tidak tersedia/overlap.
- [ ] Biaya mobil/motor sesuai aturan dan snapshot tarif tersimpan.
- [ ] Return menghitung denda dan membebaskan kendaraan.
- [ ] Cancel membebaskan kendaraan dan membatalkan pembayaran pending.
- [ ] Pembayaran melebihi tagihan ditolak; paid terkunci.
- [ ] Filter/pagination berfungsi; detail lengkap.
- [ ] Phase report, status, traceability, contribution log diperbarui.
- [ ] PR direview dan digabung.

**Handoff ke Phase 5:** seluruh alur bisnis siap diuji end-to-end.

---

### Phase 5 — Integration, Testing & Delivery

**Pemilik:** Semua anggota
**Branch:** `phase/05-integration`
**Dependensi:** Phase 2, 3, 4 selesai

**Scope:**

- End-to-end test seluruh alur.
- Verifikasi MySQL, `migrate:fresh --seed`, import SQL.
- SQL dump `database/schema/rentalgo.sql`.
- Screenshot desktop/mobile.
- Laporan akhir sesuai 8 bagian dokumentasi UAS.
- Slide presentasi dan pembagian materi.
- Implementasi bonus yang direncanakan:
  - REST API read-only: `GET /api/vehicles`, `GET /api/vehicles/{vehicle}`,
    `GET /api/rentals`, `GET /api/rentals/{rental}`.
  - Export PDF: invoice rental dan laporan rental.
  - Commit rapi dan aktif.
- Verifikasi traceability, LOC ≥1000, class ≥8, tabel ≥4, fitur tambahan ≥2.
- Perbarui contribution log.

**Acceptance criteria:**

- [ ] Semua test lulus di MySQL dan SQLite.
- [ ] Aplikasi berjalan dari clone bersih sesuai README.
- [ ] SQL dump dapat diimpor ke database kosong.
- [ ] Screenshot dan laporan lengkap.
- [ ] Presentasi diuji (rehearsal) dan demo berjalan.
- [ ] Semua requirement UAS berstatus `Verified` atau `Not Pursued` dengan alasan.
- [ ] Tidak ada plagiarisme; setiap anggota bisa menjelaskan kodenya.

## 4. Matriks Kepemilikan File

| Area | Pemilik Utama | Boleh Diubah Oleh | Catatan |
|---|---|---|---|
| `app/Contracts`, `app/Domain`, `app/Enums`, `app/Exceptions`, `app/Support` | Anggota 1 | Anggota 1 | Fondasi OOP |
| `app/Models/*` | Anggota 1 | Anggota 1 | Perubahan relasi via PR ke Anggota 1 |
| `database/migrations/*`, `database/factories/*`, `database/seeders/*` | Anggota 1 | Anggota 1 | Hindari konflik schema |
| `app/Services/RentalService.php` | Anggota 1 | Anggota 4 (koordinasi) | Phase 4 dapat menambah method via PR |
| `app/Services/PaymentService.php` | Anggota 4 | Anggota 4 | Phase 4 |
| `app/Services/DashboardService.php` | Anggota 2 | Anggota 2 | Phase 2 |
| `app/Services/VehicleImageService.php` | Anggota 3 | Anggota 3 | Phase 3 |
| Auth, layout, dashboard | Anggota 2 | Anggota 2 | Phase 2 |
| Pelanggan & kendaraan | Anggota 3 | Anggota 3 | Phase 3 |
| Rental & pembayaran | Anggota 4 | Anggota 4 | Phase 4 |
| `routes/web.php` | Anggota 1 | Anggota 1 | Hanya include route modul |
| `routes/auth.php`, `routes/dashboard.php` | Anggota 2 | Anggota 2 | Phase 2 |
| `routes/master.php` | Anggota 3 | Anggota 3 | Phase 3 |
| `routes/rental.php` | Anggota 4 | Anggota 4 | Phase 4 |
| `resources/views/layouts`, `components` | Anggota 2 | Anggota 2 | Dipakai semua modul |
| `config/*`, `composer.json`, `package.json` | Anggota 1 | Koordinasi | Dependency baru harus disetujui |
| `README.md`, `PRD.md`, `docs/*` | Anggota 1 | Semua (PR) | Traceability/status/log diperbarui tiap fase |

Aturan konflik:

1. Jangan mengedit file milik anggota lain tanpa persetujuan di PR/diskusi.
2. Untuk perubahan lintas modul, buat PR kecil dan sebutkan file yang disentuh.
3. Rebase branch pada `main` terbaru sebelum membuka PR.
4. Jangan force-push branch yang sedang direview.
5. `database/seeders/DatabaseSeeder.php` hanya diedit Anggota 1; fase lain memakai factory/state.

## 5. Workflow Git dan Pull Request

### 5.1 Branch

```text
main
├── phase/01-foundation        (Anggota 1)
├── phase/02-auth-dashboard    (Anggota 2)
├── phase/03-master-data       (Anggota 3)
├── phase/04-rental-payment    (Anggota 4)
└── phase/05-integration       (Semua)
```

Ketentuan:

- Phase 0 dokumentasi langsung di `main` (pengecualian disetujui).
- Implementasi tidak boleh langsung ke `main`.
- Branch dibuat dari `main` terbaru setelah fase prasyarat digabung.
- Nama branch mengikuti pola `phase/XX-nama-singkat`.
- Satu branch = satu fase; jangan mencampur pekerjaan fase lain.

### 5.2 Commit

Format Conventional Commits:

```text
feat(auth): add login and logout flow
fix(rental): prevent overlapping active rentals
docs(phase-1): add foundation phase report
test(domain): cover motorcycle weekly discount
refactor(vehicle): extract image service
chore(config): add rental pricing config
```

Ketentuan:

- Commit kecil, satu tujuan.
- Jangan commit `.env`, kredensial, `vendor/`, `node_modules/`, atau build artifact.
- Setiap anggota commit dengan identitas Git mereka sendiri.
- Jangan menulis ulang histori branch bersama.

### 5.3 Pull Request

Checklist PR:

- [ ] Branch berasal dari `main` terbaru.
- [ ] Scope sesuai fase; tidak ada file di luar kepemilikan tanpa alasan.
- [ ] `php artisan test` lulus.
- [ ] `vendor/bin/pint --test` lulus.
- [ ] Tidak ada secret/credential.
- [ ] Phase report dibuat di `docs/progress/`.
- [ ] `PROJECT_STATUS.md`, `UAS_TRACEABILITY.md`, `CONTRIBUTION_LOG.md` diperbarui.
- [ ] Screenshot disertakan untuk perubahan UI.
- [ ] Reviewer minimal satu anggota lain.
- [ ] Merge memakai **merge commit** untuk mempertahankan commit individu.

Setelah merge: hapus branch, tarik `main` terbaru, mulai fase berikutnya.

## 6. Dependensi dan Peluang Paralel

```text
Phase 0 ──> Phase 1 ──> Phase 2 ──> Phase 3 ──> Phase 4 ──> Phase 5
                │           │           │
                │           └── layout/komponen
                └── schema/model/domain/service
```

| Pekerjaan | Bisa dimulai setelah | Catatan |
|---|---|---|
| Auth & dashboard backend | Phase 1 | Butuh model user/role dan seeder |
| Layout & komponen | Phase 1 | Dibutuhkan Phase 3/4 untuk UI |
| Customer/vehicle backend | Phase 1 | Butuh model/schema |
| Customer/vehicle UI | Phase 2 | Butuh layout/komponen |
| Rental/payment backend | Phase 1 | Butuh `RentalService`; UI belum |
| Rental/payment UI | Phase 3 | Butuh halaman pelanggan/kendaraan |
| E2E & dokumentasi akhir | Phase 2–4 | Phase 5 |

Rekomendasi urutan merge: **1 → 2 → 3 → 4 → 5**. Jika Phase 3/4 dikerjakan paralel, mereka harus
rebase ke `main` setelah Phase 2 digabung dan hanya mengedit file modulnya sendiri.

## 7. Integrasi

1. Setiap fase digabung ke `main` melalui PR dan merge commit.
2. Setelah merge, pemilik fase berikutnya menjalankan `git switch main; git pull`, lalu membuat/
   memperbarui branch fase.
3. Konflik route diselesaikan dengan file route terpisah per modul.
4. Konflik layout diselesaikan oleh Anggota 2 sebagai pemilik layout.
5. Konflik schema diselesaikan oleh Anggota 1; fase lain tidak menambah migration tanpa diskusi.
6. Phase 5 melakukan regression test seluruh aplikasi sebelum demo.

## 8. Definition of Done per Fase

Sebuah fase dianggap selesai jika:

- [ ] Scope fase diimplementasikan dan tidak melebar.
- [ ] Unit/feature test relevan ditambahkan dan lulus.
- [ ] `php artisan test` lulus.
- [ ] `vendor/bin/pint --test` lulus.
- [ ] Tidak ada error kritis di browser/log.
- [ ] UI responsif (jika ada UI).
- [ ] Phase report dibuat dari template.
- [ ] `PROJECT_STATUS.md` diperbarui.
- [ ] `UAS_TRACEABILITY.md` diperbarui.
- [ ] `CONTRIBUTION_LOG.md` diperbarui dengan kontribusi nyata.
- [ ] PR direview minimal satu anggota lain.
- [ ] Merge ke `main` selesai.

## 9. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Konflik `routes/web.php` | Merge conflict | Route file terpisah per modul; `web.php` hanya include |
| Konflik layout | UI tidak konsisten | Layout dimiliki Anggota 2; komponen dipakai ulang |
| Schema berubah di tengah jalan | Model/test rusak | Schema hanya Anggota 1; perubahan via PR |
| Seeder saling menimpa | Data demo kacau | Seeder hanya Anggota 1; fase lain pakai factory |
| Dependensi baru tanpa koordinasi | Build rusak | Persetujuan Anggota 1; commit `composer.json`/`package.json` terpisah |
| Scope bonus menggeser fitur wajib | Fitur inti tidak selesai | Bonus hanya Phase 5 setelah core lulus |
| MySQL berbeda dari SQLite | Bug saat demo | Uji MySQL di Phase 1 dan Phase 5 |
| LOC kurang dari 1000 | Nilai intermediate berkurang | Pantau di setiap fase; target fitur wajib sudah cukup |
| Anggota tidak memahami kode | Nilai individu turun | Code walkthrough dan presentasi internal |

## 10. Anggota Tim

| Label | Tanggung Jawab | Nama/NIM |
|---|---|---|
| Anggota 1 | Phase 0 + Phase 1 (foundation) | Benny Anderson / 422024008 |
| Anggota 2 | Phase 2 (auth, layout, dashboard) | Steven Credentia Ivanemaga Zega / 422024020 |
| Anggota 3 | Phase 3 (master data) | Jason / 422024004 |
| Anggota 4 | Phase 4 (rental & payment) | Steven / 422024001 |

Identitas di atas sudah dikonfirmasi pemilik proyek. Jangan mengarang identitas lain pada laporan.
