# Rencana Fase dan Pembagian Tim — RentalGo

Dokumen ini membagi pekerjaan menjadi empat tanggung jawab anggota, dengan Phase 0 (perencanaan)
dan Phase 5 (integrasi) sebagai fase lintas tim. Tujuannya: konflik file minimal, dependensi jelas,
dan setiap anggota memiliki bukti kontribusi yang dapat dinilai.

## 1. Prinsip Pembagian Kerja

1. **Kontribusi terbuka lintas phase.** Pembagian kerja tidak berpatokan pada phase secara kaku;
   siapa saja boleh mengerjakan task pada phase mana saja selama histori commit tercatat memakai
   **email Git masing-masing anggota**.
2. **Koordinasi untuk hindari konflik.** Matriks area kerja dipakai untuk koordinasi agar dua orang
   tidak mengedit file yang sama secara bersamaan, bukan untuk melarang siapa pun berkontribusi.
3. **Fondasi lebih dulu.** Schema, model, enum, domain OOP, dan seeder diselesaikan Phase 1.
4. **Dependensi ditulis eksplisit.** Tidak ada pekerjaan yang dimulai tanpa memahami prasyarat.
5. **Commit kecil dan bermakna.** Setiap anggota commit memakai `user.name` dan `user.email` Git
   masing-masing; kontribusi direkonsiliasi di `docs/CONTRIBUTION_LOG.md` pada Phase 5.
6. **PR untuk implementasi.** Phase 0 adalah pengecualian dokumentasi di `main`.
7. **Definition of Done wajib.** Fitur dianggap selesai hanya jika kode, test, dan dokumentasi fase
   sudah lengkap serta direview.

## 2. Ringkasan Fase

| Fase | Nama | Pelaksana / Histori Commit | Branch | Status |
|---|---|---|---|---|
| 0 | Requirements & Planning | Benny Anderson (`benanderson2nds@gmail.com`) | `main` (dokumentasi saja) | Selesai |
| 1 | Foundation & Architecture | Benny Anderson (`benanderson2nds@gmail.com`), review Steven Zega | `phase/01-foundation` | Merged (PR #1, `1b5caee`) |
| 2 | Auth, Layout & Dashboard | Steven Zega (`steven.422024020@civitas.ukrida.ac.id`), merge Benny | `phase/02-auth-dashboard` | Merged (PR #2, `c4847c2`) |
| 3 | Master Data CRUD | Steven (`steven.422024001@civitas.ukrida.ac.id`) | `phase/03-master-data` | Merged (PR #3, `9a738fb`) |
| 4 | Rental & Payment Workflow | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `phase/04-rental-payment` | PR #4 — menunggu review |
| 5 | Integration, Testing & Delivery | Semua anggota (commit dengan email Git masing-masing) | `phase/05-integration` | Belum dimulai |

## 3. Detail Fase

### Phase 0 — Requirements Analysis & Planning

**Pelaksana (Histori Commit):** Benny Anderson (`benanderson2nds@gmail.com`)
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

**Handoff:** Phase 1 sudah merged melalui PR #1 (`1b5caee`); lanjut Phase 2.

---

### Phase 1 — Foundation & Architecture

**Pelaksana (Histori Commit):** Benny Anderson (`benanderson2nds@gmail.com`)
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
- [x] PR direview dan digabung (PR #1, `1b5caee`).

**Handoff ke Phase 2–4:** schema stabil, model/relasi, seeder, domain/service, route stub.

---

### Phase 2 — Authentication, Layout & Dashboard

**Pelaksana (Histori Commit):** Steven Credentia Ivanemaga Zega (`steven.422024020@civitas.ukrida.ac.id`)
**Branch:** `phase/02-auth-dashboard`
**Dependensi:** Phase 1 (users role, model, seeder) dan Phase 1 route stub

**Scope:**

- Custom login/logout/session operator (`web` guard) + login/registrasi/logout pelanggan terpisah (`customer` guard).
- Middleware `auth:web`, `auth:customer`, dan `EnsureUserIsAdmin`.
- Layout utama responsif (`app.blade.php`), layout guest, dan layout publik/pelanggan (`public.blade.php`) + komponen Blade reusable.
- Portal pelanggan publik: halaman katalog kendaraan (`/`), detail & estimasi tarif kendaraan, booking mandiri via `RentalService`, riwayat & detail rental pribadi, pembatalan rental aktif pribadi, dan profil pelanggan.
- Dashboard Admin/Staff: kartu ringkasan, grafik pendapatan/status, rental terbaru.
- `DashboardService`, `DashboardController`, `PortalController`, `CustomerAuthController`, `CustomerRentalController`, `CustomerProfileController`.
- Chart.js melalui npm.
- Feature test auth, registrasi, isolasi guard, dashboard, dan portal pelanggan.

**File/modul utama:**

- `app/Http/Controllers/Auth/AuthController.php`
- `app/Http/Controllers/PortalController.php`, `Customer/CustomerAuthController.php`, `Customer/CustomerRentalController.php`, `Customer/CustomerProfileController.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Requests/Customer/CustomerLoginRequest.php`, `CustomerRegisterRequest.php`, `CustomerBookingRequest.php`, `UpdateCustomerProfileRequest.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Services/DashboardService.php`
- `config/auth.php`, `database/migrations/2026_11_25_000006_*`
- `resources/views/layouts/`, `components/`, `auth/`, `dashboard/`, `portal/`, `customer/`
- `routes/auth.php`, `routes/dashboard.php`, `routes/customer.php`
- `tests/Feature/Auth/`, `tests/Feature/Dashboard/`, `tests/Feature/Customer/`
- `package.json` (Chart.js)

**Acceptance criteria:**

- [x] Login valid/invalid, logout, dan proteksi route lulus feature test.
- [x] Session regenerate saat login dan invalidate saat logout.
- [x] Layout responsive di desktop/tablet/mobile.
- [x] Dashboard menampilkan angka yang sesuai data seeder dan grafik tampil.
- [x] Tidak ada akses halaman operasional tanpa login.
- [x] Phase report, status, traceability, contribution log diperbarui.
- [x] PR direview dan digabung (PR #2, `c4847c2`).

**Handoff ke Phase 3–4:** layout, komponen, navigasi, pola controller/request/view.

---

### Phase 3 — Master Data CRUD (Customers & Vehicles)

**Pelaksana (Histori Commit):** Steven (`steven.422024001@civitas.ukrida.ac.id`)
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

- [x] CRUD pelanggan dan kendaraan lulus feature test.
- [x] Search, pagination, sorting, filter berfungsi.
- [x] Upload gambar tervalidasi dan tampil; gambar lama dihapus saat replace.
- [x] Soft delete ditolak jika ada rental aktif.
- [x] Detail menampilkan riwayat rental.
- [x] UI responsif dan konsisten dengan layout Phase 2.
- [x] Phase report, status, traceability, dan contribution log diperbarui.
- [x] Commit dan push branch `phase/03-master-data` (`8336f17`, `be149f3`, `80af0a8`).
- [x] PR di GitHub direview dan digabung (PR #3, `9a738fb`).

**Handoff ke Phase 4:** halaman/master data siap dipilih pada form rental.

---

### Phase 4 — Rental & Payment Workflow

**Pelaksana:** Terbuka untuk anggota mana saja (commit memakai email Git masing-masing)
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
  `ReturnRentalRequest.php`, `StorePaymentRequest.php`, `UpdatePaymentRequest.php`
- `app/Services/PaymentService.php`
- `resources/views/rentals/`, `payments/`
- `routes/rental.php`
- `tests/Feature/Rentals/`, `tests/Feature/Payments/`

**Acceptance criteria:**

- [x] Rental tidak dapat dibuat jika kendaraan tidak tersedia/overlap.
- [x] Biaya mobil/motor sesuai aturan dan snapshot tarif tersimpan.
- [x] Return menghitung denda dan membebaskan kendaraan.
- [x] Cancel membebaskan kendaraan dan membatalkan pembayaran pending.
- [x] Pembayaran melebihi tagihan ditolak; paid terkunci.
- [x] Filter/pagination berfungsi; detail lengkap.
- [x] Phase report, status, traceability, contribution log diperbarui.
- [ ] PR direview dan digabung.

**Handoff ke Phase 5:** seluruh alur bisnis siap diuji end-to-end.

---

### Phase 5 — Integration, Testing & Delivery

**Koordinator:** Semua anggota
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
- Rekonsiliasi contribution log berbasis task/commit dengan `git log`, phase report, PR, dan reviewer;
  pastikan semua anggota mengerjakan deliverable akhir.

**Acceptance criteria:**

- [ ] Semua test lulus di MySQL dan SQLite.
- [ ] Aplikasi berjalan dari clone bersih sesuai README.
- [ ] SQL dump dapat diimpor ke database kosong.
- [ ] Screenshot dan laporan lengkap.
- [ ] Presentasi diuji (rehearsal) dan demo berjalan.
- [ ] Semua requirement UAS berstatus `Verified` atau `Not Pursued` dengan alasan.
- [ ] Contribution log final konsisten dengan `git log` dan phase report.
- [ ] Tidak ada plagiarisme; setiap anggota bisa menjelaskan kodenya.

## 4. Matriks Area Kerja dan Koordinasi

| Area | Fase Terkait | Catatan Koordinasi |
|---|---|---|
| `app/Contracts`, `app/Domain`, `app/Enums`, `app/Exceptions`, `app/Support` | Phase 1 | Fondasi OOP; perubahan lewat koordinasi PR |
| `app/Models/*` | Phase 1 | Perubahan relasi/cast lewat PR agar tidak mematahkan modul lain |
| `database/migrations/*`, `database/factories/*`, `database/seeders/*` | Phase 1 | Hindari konflik schema; koordinasikan sebelum mengubah |
| `app/Services/RentalService.php` | Phase 1 & 4 | Sumber tunggal aturan rental; perubahan lewat PR |
| `app/Services/PaymentService.php` | Phase 4 | Logika pembayaran |
| `app/Services/DashboardService.php` | Phase 2 | Statistik dashboard |
| `app/Services/VehicleImageService.php` | Phase 3 | Upload & hapus gambar kendaraan |
| Auth, layout, dashboard, portal pelanggan | Phase 2 | Layout dipakai lintas modul |
| Pelanggan & kendaraan (admin CRUD) | Phase 3 | Master data |
| Rental & pembayaran (admin CRUD) | Phase 4 | Transaksi rental & pembayaran |
| `routes/web.php` | Lintas fase | Include route modul |
| `routes/auth.php`, `routes/dashboard.php`, `routes/customer.php` | Phase 2 | Route auth, dashboard, dan portal pelanggan |
| `routes/master.php` | Phase 3 | Route master data |
| `routes/rental.php` | Phase 4 | Route rental & pembayaran |
| `resources/views/layouts`, `components` | Phase 2 | Dipakai semua modul; koordinasikan perubahan |
| `config/*`, `composer.json`, `package.json` | Lintas fase | Dependency baru harus disetujui tim |
| `README.md`, `PRD.md`, `docs/*` | Lintas fase | Traceability/status/log diperbarui tiap fase |

Matriks ini dipakai untuk koordinasi agar tidak terjadi merge conflict, bukan membatasi kontribusi.
Siapa saja boleh mengerjakan file atau fase mana saja selama histori commit tercatat memakai
email Git masing-masing dan perubahan lintas modul dikoordinasikan.

Aturan konflik:

1. Koordinasikan terlebih dahulu sebelum mengubah file lintas modul/shared (`layouts/`, `models/`,
   `migrations/`, `RentalService.php`) agar tidak terjadi konflik.
2. Untuk perubahan lintas modul, buat PR kecil dan sebutkan file yang disentuh.
3. Rebase branch pada `main` terbaru sebelum membuka PR.
4. Jangan force-push branch yang sedang direview.
5. Perubahan `database/seeders/DatabaseSeeder.php` harus dikoordinasikan; test memakai factory/state.

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
- Commit memakai identitas orang yang menjalankan commit. Kontribusi task (utama/pendukung/reviewer)
  dicatat di `docs/CONTRIBUTION_LOG.md`; finalisasi di Phase 5.
- Jangan menulis ulang histori branch bersama.

### 5.3 Pull Request

Checklist PR:

- [ ] Branch berasal dari `main` terbaru.
- [ ] Scope sesuai fase; perubahan file lintas modul sudah dikoordinasikan.
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
2. Setelah merge, pelaksana fase berikutnya menjalankan `git switch main; git pull`, lalu membuat/
   memperbarui branch fase.
3. Konflik route diselesaikan dengan file route terpisah per modul.
4. Konflik layout diselesaikan melalui koordinasi bersama sebelum merge.
5. Konflik schema diselesaikan bersama lewat PR; fase lain tidak menambah migration tanpa diskusi.
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
- [ ] `CONTRIBUTION_LOG.md` diperbarui dengan kontribusi nyata berbasis task/commit; finalisasi di Phase 5.
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
| Kontribusi tidak tercatat konsisten | Penilaian individu | Catat task/commit sejak awal; rekonsiliasi dengan `git log` dan phase report di Phase 5 |

## 10. Anggota Tim

| Anggota | Nama / NIM | Email Git |
|---|---|---|
| Anggota 1 | Benny Anderson / 422024008 | `benanderson2nds@gmail.com` |
| Anggota 2 | Steven Credentia Ivanemaga Zega / 422024020 | `steven.422024020@civitas.ukrida.ac.id` |
| Anggota 3 | Jason / 422024004 | `jason.422024004@civitas.ukrida.ac.id` |
| Anggota 4 | Steven / 422024001 | `steven.422024001@civitas.ukrida.ac.id` |

Identitas di atas sudah dikonfirmasi pemilik proyek. Pembagian kerja tidak dikunci per fase; setiap
anggota bebas mengerjakan fase mana saja selama histori commit tercatat pada email Git masing-masing.
Rekap kontribusi final mengikuti `docs/CONTRIBUTION_LOG.md` dan `git log`.
