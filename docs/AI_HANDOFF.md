# AI Handoff — RentalGo

Dokumen ini adalah konteks serah-terima untuk anggota tim dan AI agent yang mengerjakan fase
berikutnya. Aturan kerja lengkap ada di `AGENTS.md`; dokumen ini menjelaskan **kondisi proyek** dan
**kontrak antar fase**.

## 1. Urutan Membaca Sebelum Bekerja

1. `docs/UAS_REQUIREMENTS.md` — requirement resmi yang diekstrak.
2. `PRD.md` — kebutuhan produk dan aturan bisnis.
3. `docs/UAS_TRACEABILITY.md` — status dan lokasi setiap requirement.
4. `docs/ARCHITECTURE.md` — struktur, class, diagram.
5. `docs/DATABASE_SCHEMA.md` — tabel, relasi, workflow data.
6. `docs/DECISIONS.md` — keputusan yang sudah disepakati.
7. `docs/PHASE_PLAN.md` — scope fase dan kepemilikan file.
8. `AGENTS.md` — aturan kerja dan konvensi.
9. `docs/PROJECT_STATUS.md` — status terbaru.
10. `docs/progress/` — laporan fase sebelumnya.

Dokumen UAS asli di `docs/reference/` adalah sumber kebenaran utama. Jika dokumen lain bertentangan,
dokumen UAS menang; perbaiki dokumen proyek melalui PR.

## 2. Kondisi Saat Ini

| Item | Status |
|---|---|
| Repository | `https://github.com/Jehian/rentalgo-oop` |
| Branch dokumentasi | `main` |
| Commit terakhir | `main`: merge PR #2 `c4847c2`; Phase 3 di `origin/phase/03-master-data` (`80af0a8`) |
| Framework | Laravel 13.44.1 |
| PHP CLI | 8.4.12 (minimum 8.3) |
| Node/npm | 22.23.4 / npm terpasang |
| Database lokal | MySQL 8.4.3 Laragon, `127.0.0.1:3306`, database `rentalgo` |
| Implementasi aplikasi | Phase 1–2 merged; Phase 3 (CRUD pelanggan/kendaraan) pushed & PR dibuat di GitHub, menunggu review/merge; rental/pembayaran belum |
| Migration bisnis | 5 tabel + role + auth kolom `customers` + `rentals.user_id` nullable |
| Model/controller/service/view | Model/service/auth/dashboard/portal pelanggan/master data ada; rental/payment Phase 4 |
| Test | 74 test lulus (293 assertions) |
| Docker | Tidak digunakan |

Phase 0, Phase 1, dan Phase 2 sudah selesai. Phase 2 digabung ke `main` melalui PR #2 (`c4847c2`).
Phase 3 (CRUD pelanggan dan kendaraan) sudah di-commit oleh `steven.422024001@civitas.ukrida.ac.id`,
di-push ke `origin/phase/03-master-data`, dan PR-nya di GitHub menunggu review/merge. Transaksi
rental dan pembayaran belum tersedia (Phase 4).

## 3. Kontrak Phase 1 (Tersedia di Main)

Kontrak ini sudah tersedia di `main` dan boleh dipakai Phase 3–4:

### 3.1 Database dan Model

- Tabel: `users` (+ `role`), `customers`, `vehicles`, `rentals`, `payments`.
- Model: `User`, `Customer`, `Vehicle`, `Rental`, `Payment`.
- Relasi:
  - `User::rentals()`
  - `Customer::rentals()`
  - `Vehicle::rentals()`
  - `Rental::customer()`, `vehicle()`, `user()`, `payments()`
  - `Payment::rental()`
- Cast enum/date/decimal pada model.
- Scope `Vehicle::available()`, `Vehicle::ofType()`.
- Soft delete pada `Customer` dan `Vehicle`.

### 3.2 Enum

`UserRole`, `VehicleType`, `VehicleStatus`, `RentalStatus`, `PaymentMethod`, `PaymentStatus`.
Semua memiliki method `label()` untuk UI.

### 3.3 Domain OOP

- `App\Contracts\Rentable`
- `App\Domain\Vehicles\Vehicle` (abstract)
- `App\Domain\Vehicles\Car`
- `App\Domain\Vehicles\Motorcycle`
- `App\Domain\Vehicles\VehicleFactory`
- `App\Exceptions\VehicleNotAvailableException`
- `App\Exceptions\UnsupportedVehicleTypeException`
- `App\Support\DocumentCodeGenerator`

### 3.4 Service

`App\Services\RentalService` dengan method publik yang direncanakan:

```php
createRental(Customer $customer, VehicleModel $vehicle, Carbon $start, Carbon $end, User $user): Rental
updateRental(Rental $rental, array $data): Rental
completeRental(Rental $rental, Carbon $returnedAt): Rental
cancelRental(Rental $rental): Rental
calculateLateFee(Rental $rental, Carbon $returnedAt): float
```

Phase 4 boleh menambah method kecil melalui PR dengan koordinasi Anggota 1; jangan menduplikasi
logika availability/pricing di controller.

### 3.5 Konfigurasi

`config/rental.php` direncanakan berisi:

```php
return [
    'currency' => 'IDR',
    'timezone' => 'Asia/Jakarta',
    'car_insurance_rate' => 0.10,
    'motorcycle_weekly_discount_rate' => 0.10,
    'motorcycle_weekly_min_days' => 7,
    'late_fee_rate_per_day' => 0.50,
    'image_disk' => 'public',
    'image_folder' => 'vehicles',
    'code_prefixes' => ['rental' => 'RNT', 'payment' => 'PAY'],
    'demo_password' => env('RENTAL_DEMO_PASSWORD', 'password'),
];
```

### 3.6 Seeder

Akun demo (lokal saja):

- `admin@rentalgo.test` / `password` (tabel `users`, login `/login`)
- `staff@rentalgo.test` / `password` (tabel `users`, login `/login`)
- `customer@rentalgo.test` / `password` (tabel `customers`, login `/customer/login`)

Data demo saat ini: 8 pelanggan, 4 mobil + 4 motor, 12 rental (6 aktif, 6 selesai), dan 12
pembayaran. Seeder hanya dimiliki Anggota 1.

### 3.7 Route Stub

`routes/web.php` meng-include:

```php
require __DIR__.'/auth.php';
require __DIR__.'/customer.php';
require __DIR__.'/dashboard.php';
require __DIR__.'/master.php';
require __DIR__.'/rental.php';
```

File route modul:

- `auth.php`, `customer.php`, `dashboard.php` — Anggota 2
- `master.php` — Anggota 3 (sudah berisi route CRUD pelanggan/kendaraan Phase 3)
- `rental.php` — Anggota 4 (masih stub)

## 4. Handoff per Fase

### Phase 2 — Anggota 2

**Baca:** PRD §6.1–6.2, ARCHITECTURE §8–9, DATABASE_SCHEMA §3.1, PHASE_PLAN Phase 2.

**Lakukan:**

- Custom auth operator (`AuthController`, `LoginRequest`, `web` guard) dan auth pelanggan terpisah (`CustomerAuthController`, `CustomerLoginRequest`, `CustomerRegisterRequest`, `customer` guard).
- Middleware `auth:web`, `auth:customer`, dan `EnsureUserIsAdmin`.
- Layout utama (`app.blade.php`), guest, dan layout publik/pelanggan (`public.blade.php`) + komponen Blade reusable.
- Portal pelanggan publik: `PortalController`, `CustomerRentalController`, `CustomerProfileController`, katalog kendaraan (`/`), detail & estimasi tarif, booking mandiri, riwayat rental, profil.
- `DashboardService`, `DashboardController`, dashboard cards/chart.
- Chart.js via npm.
- Feature test auth/registrasi/isolasi guard/dashboard/portal pelanggan.

**Jangan:**

- Mengubah file modul Phase 3/4.
- Menambah dependency selain Chart.js tanpa persetujuan.

**Serahkan ke Phase 3/4:** layout, komponen, navigasi, pola controller/request/view.

### Phase 3 — Master Data CRUD (PR dibuat, menunggu review/merge)

**Baca:** PRD §6.3–6.4, ARCHITECTURE §3, DATABASE_SCHEMA §3.2–3.3, PHASE_PLAN Phase 3.

**Prasyarat:** Phase 1 merged (model/schema) dan Phase 2 merged (layout).

**Status:** CRUD pelanggan/kendaraan, search/filter/sort/pagination, upload/ganti gambar, detail
riwayat rental, validasi, dan soft delete guard sudah diimplementasikan pada branch
`phase/03-master-data`. Test Phase 3 lulus. Branch sudah di-push (3 commit oleh Steven /
`steven.422024001@civitas.ukrida.ac.id`) dan PR di GitHub menunggu review/merge.

**Kontrak untuk Phase 4:**

- Route `customers.*` dan `vehicles.*` memakai `auth:web`; hapus memakai middleware `admin`.
- Form/route pelanggan dan kendaraan dapat dipakai untuk memilih data pada transaksi rental.
- `VehicleImageService` menangani simpan/hapus gambar; jangan menyimpan file langsung dari controller.
- Kendaraan dengan rental aktif tidak boleh dihapus atau diubah tipe/statusnya.
- Status `rented` hanya diatur alur rental; form master hanya menampilkan `available`/`maintenance`.

**Jangan:**

- Mengubah schema; jika butuh kolom baru, ajukan ke Anggota 1.
- Menduplikasi logika availability/pricing di controller.
- Menghapus data master yang punya rental aktif.

### Phase 4 — Rental & Payment (Terbuka untuk Anggota Mana Saja)

**Baca:** PRD §6.5–6.6, ARCHITECTURE §4–5, DATABASE_SCHEMA §8, PHASE_PLAN Phase 4.

**Prasyarat:** Phase 1 (`RentalService`/domain), Phase 2 (layout), Phase 3 (halaman master).

**Lakukan:**

- Rental create/update/return/cancel dengan `RentalService`.
- Payment CRUD pending + mark paid/failed via `PaymentService`.
- Filter/pagination/detail.
- Feature test availability, biaya, denda, overpayment.

**Jangan:**

- Menduplikasi logika pricing/availability di controller.
- Mengubah schema/model tanpa koordinasi.
- Menghapus rental secara permanen.

### Phase 5 — Semua

**Lakukan:**

- E2E test, verifikasi MySQL, SQL dump, screenshot.
- Laporan akhir 8 bagian, slide, rehearsal.
- Verifikasi traceability, LOC, class, tabel, fitur tambahan.
- Bonus REST API dan PDF direncanakan setelah fitur wajib stabil.
- Perbarui contribution log.

## 5. Environment dan Perintah

### 5.1 Setup Awal

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

### 5.2 Database MySQL

Pastikan MySQL Laragon berjalan. Database `rentalgo` sudah dibuat di MySQL port 3306.

Jika perlu membuat ulang dari phpMyAdmin atau MySQL client:

```powershell
mysql -u root -p -e "CREATE DATABASE rentalgo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Atur `.env` (jangan commit; password lokal sudah diisi oleh pemilik proyek):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentalgo
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan:

```powershell
php artisan migrate:fresh --seed
```

### 5.3 Menjalankan Aplikasi

```powershell
npm run dev
php artisan serve
```

### 5.4 Test dan Style

```powershell
php artisan test
vendor/bin/pint --test
```

### 5.5 Storage (Phase 3)

```powershell
php artisan storage:link
```

### 5.6 Git

```powershell
git switch main
git pull
git switch -c phase/03-master-data
```

Branch Phase 3 saat ini: `phase/03-master-data` (sudah di-push ke `origin`). Phase 4 menggunakan
`phase/04-rental-payment` dari `main` setelah Phase 3 digabung.

## 6. Gotcha Environment

1. **PHP `ext/curl` warning** — pada mesin ini PHP CLI menampilkan peringatan startup karena
   `php_curl.dll` diblokir Application Control. Artisan tetap berjalan. Jangan menganggapnya bug kode;
   catat di troubleshooting laporan jika muncul saat demo.
2. **MySQL Laragon** — MySQL harus berjalan sebelum migrasi. Environment aktif memakai MySQL 8.4.3
   Laragon di `127.0.0.1:3306` (bukan MariaDB).
3. **SQLite vs MySQL** — test memakai SQLite in-memory; demo memakai MySQL. Hindari SQL manual yang
   hanya valid di salah satu.
4. **Ekstensi `intl`** — belum aktif di PHP CLI. Gunakan `number_format` untuk nominal uang;
   jangan memakai `Number::format`/`Number::currency` tanpa mengaktifkan `intl`.
5. **Laravel 13 attributes** — model skeleton memakai `#[Fillable]`/`#[Hidden]`, bukan `$fillable`
   array. Ikuti gaya yang ada.
6. **Tailwind 4 + Vite 8** — build memakai `npm run build`; dev memakai `npm run dev`. Jangan
   menambah config Tailwind lama (`tailwind.config.js`) tanpa alasan.
7. **Windows PowerShell** — gunakan `Copy-Item`, bukan `cp`; path dengan spasi harus dikutip.
8. **Chart.js** — dependency baru hanya di Phase 2; jalankan `npm install` setelah perubahan
   `package.json`.
9. **Tidak ada Docker** — jangan menambahkan Dockerfile/compose.

## 7. Aturan untuk AI Agent

Aturan lengkap di `AGENTS.md`. Ringkasan:

- Jangan bekerja di `main` untuk implementasi; Phase 0 dokumentasi adalah pengecualian.
- Baca dokumen sebelum mengubah kode.
- Jangan mengubah file milik anggota lain tanpa koordinasi.
- Jangan menambah dependency tanpa persetujuan.
- Jangan mengklaim selesai tanpa test/verifikasi.
- Pembagian kerja tidak dikunci per phase: siapa saja boleh mengerjakan phase mana pun selama commit
  memakai email Git masing-masing anggota.
- Commit memakai identitas dan email Git milik anggota yang menjalankan commit; rekap dicatat di
  `docs/CONTRIBUTION_LOG.md`.
- Jangan commit rahasia/`.env`.
- Jangan menyalin kode dari internet tanpa atribusi; proyek harus orisinal.
- Jika requirement ambigu, tanyakan; jangan mengarang.
- Jelaskan perubahan file dan hasil test di akhir.

## 8. Checklist Handoff

- [ ] Dokumen yang relevan sudah dibaca.
- [ ] Branch fase dibuat dari `main` terbaru.
- [ ] Dependency fase sudah digabung.
- [ ] Scope dan file yang boleh diubah dipahami.
- [ ] Rencana test ditulis sebelum implementasi.
- [ ] Phase report disiapkan.
- [ ] Pertanyaan terbuka dicatat.

## 9. Pertanyaan Terbuka

1. PR Phase 3: 3 commit sudah di-push dan PR dibuka di GitHub; menunggu review/merge Benny (atau reviewer lain).
2. Format final UML (Mermaid/PNG).
3. Jadwal presentasi.

Nama/NIM anggota, kredensial MySQL lokal, dan bonus REST API + PDF sudah dikonfirmasi.

Jangan memblokir pekerjaan yang tidak bergantung pada jawaban ini; gunakan default yang tercatat di
`docs/DECISIONS.md` dan tandai asumsi.
