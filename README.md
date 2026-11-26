# RentalGo

Aplikasi web sistem informasi **rental kendaraan** yang dibangun dengan Laravel dan PHP OOP untuk
UAS mata kuliah Pemrograman Berorientasi Objek (SI2WPO084), Program Studi Sistem Informasi.

> **Status:** Phase 0, Phase 1, dan Phase 2 selesai dan sudah merged ke `main` (PR #1 `1b5caee`,
> PR #2 `c4847c2`). Phase 3 (Master Data CRUD pelanggan dan kendaraan) sudah di-commit dan di-push ke
> branch `phase/03-master-data` dan PR-nya sedang menunggu review/merge di GitHub. Transaksi rental
> dan pembayaran belum tersedia (Phase 4).

## Tentang Proyek

RentalGo membantu pengelolaan operasional rental kendaraan: data pelanggan, data mobil/motor,
transaksi penyewaan, pengembalian, denda keterlambatan, pembayaran, dan dashboard ringkas.
Fokus proyek adalah penerapan OOP yang dapat dipahami, MVC sederhana, CRUD, relasi database,
autentikasi sederhana, validasi, dan dokumentasi.

## Fitur

Sudah tersedia (Phase 2–3):

- Portal pelanggan publik: katalog kendaraan (`/`), pencarian, filter tipe/status, sorting tarif, detail kendaraan, dan estimasi biaya otomatis.
- Registrasi pelanggan publik (`/customer/register`), login, logout, session, serta pemisahan akses Admin, Staff (`operator`), dan Pelanggan (`customer`).
- Booking mandiri pelanggan, riwayat dan detail pesanan pribadi, pembatalan rental aktif pribadi, serta pengelolaan profil pelanggan.
- Layout responsif operator (`app.blade.php`) dan layout publik/pelanggan (`public.blade.php`).
- Dashboard Admin/Staff: ringkasan, grafik pendapatan enam bulan, komposisi status kendaraan, dan rental terbaru.
- CRUD pelanggan: daftar dengan pencarian/sorting/pagination, tambah, edit, detail + riwayat rental, dan soft delete khusus Admin.
- CRUD kendaraan: daftar dengan pencarian/filter tipe-status/sorting/pagination, tambah, edit, detail + riwayat rental, upload/ganti gambar, dan soft delete khusus Admin.
- Komponen Blade reusable dan halaman error 403 berbahasa Indonesia.

Direncanakan (Phase 4):

- Transaksi rental: cek ketersediaan, perhitungan biaya, pengembalian, denda, pembatalan.
- Pembayaran: catat, ubah status, edit/hapus selama pending.
- Bonus yang direncanakan: REST API read-only kendaraan/rental dan export PDF invoice/laporan.

Fitur tambahan yang sudah tersedia: search, pagination, sorting, filter, upload gambar, dan dashboard
chart (UAS mewajibkan minimal dua).

## OOP yang Diterapkan

- **Encapsulation** — property domain private/protected dan getter.
- **Inheritance** — `Car` dan `Motorcycle` extends abstract `Vehicle`.
- **Polymorphism** — override `calculateRentalCost()`.
- **Abstraction** — interface `Rentable` dan abstract class `Vehicle`.
- **Constructor** — constructor domain dan dependency injection service.
- **Static method** — `VehicleFactory::fromModel()` dan `DocumentCodeGenerator`.
- **Exception handling** — custom exception dan try-catch.
- **Namespace** — PSR-4 `App\...`.

## Teknologi

| Komponen | Teknologi |
|---|---|
| Bahasa | PHP 8.4 (minimum 8.3) |
| Framework | Laravel 13 |
| Database utama | MySQL 8+/9.x (MariaDB kompatibel) |
| Database test | SQLite in-memory |
| Frontend | Blade, Tailwind CSS 4, Vite 8, Chart.js 4 |
| Testing | PHPUnit 12 |
| Code style | Laravel Pint |
| Version control | Git + GitHub |

Tidak digunakan: Docker, Breeze/Jetstream, framework SPA.

## Requirements

- PHP >= 8.3 dengan ekstensi standar Laravel.
- Composer.
- Node.js dan npm.
- MySQL 8.4 (Laragon) untuk aplikasi; SQLite hanya untuk automated test.

## Instalasi

Langkah berikut menjadi acuan setelah fondasi Phase 1 tersedia:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Buat database MySQL:

```powershell
mysql -u root -p -e "CREATE DATABASE rentalgo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Atur koneksi database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentalgo
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seeder:

```powershell
php artisan migrate:fresh --seed
php artisan storage:link
```

## Akun Demo

Seeder membuat tiga akun lokal:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@rentalgo.test` | `password` |
| Staff | `staff@rentalgo.test` | `password` |
| Pelanggan | `customer@rentalgo.test` | `password` |

Jalankan aplikasi:

```powershell
npm run dev
php artisan serve
```

Jalankan test:

```powershell
php artisan test
```

## Screenshot

| Halaman | Desktop | Mobile |
|---|---|---|
| Login | [login-desktop.png](docs/assets/phase-02/login-desktop.png) | [login-mobile.png](docs/assets/phase-02/login-mobile.png) |
| Dashboard | [dashboard-desktop.png](docs/assets/phase-02/dashboard-desktop.png) | [dashboard-mobile.png](docs/assets/phase-02/dashboard-mobile.png) |
| Pelanggan | [customers-desktop.png](docs/assets/phase-03/customers-desktop.png) | [customers-mobile.png](docs/assets/phase-03/customers-mobile.png) |
| Kendaraan | [vehicles-desktop.png](docs/assets/phase-03/vehicles-desktop.png) | [vehicles-mobile.png](docs/assets/phase-03/vehicles-mobile.png) |

## Dokumentasi

| Dokumen | Isi |
|---|---|
| [PRD.md](PRD.md) | Product requirements, fitur, aturan bisnis, acceptance criteria |
| [docs/UAS_REQUIREMENTS.md](docs/UAS_REQUIREMENTS.md) | Ekstraksi requirement UAS |
| [docs/UAS_TRACEABILITY.md](docs/UAS_TRACEABILITY.md) | Traceability requirement → implementasi → verifikasi |
| [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md) | Arsitektur, OOP class design, UML |
| [docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md) | Skema database dan ERD |
| [docs/DECISIONS.md](docs/DECISIONS.md) | Keputusan teknis (ADR) |
| [docs/PHASE_PLAN.md](docs/PHASE_PLAN.md) | Rencana fase dan pembagian tim |
| [docs/PROJECT_STATUS.md](docs/PROJECT_STATUS.md) | Status proyek terkini |
| [docs/AI_HANDOFF.md](docs/AI_HANDOFF.md) | Konteks handoff untuk anggota/AI agent |
| [AGENTS.md](AGENTS.md) | Aturan kerja dan konvensi kode |

Dokumen UAS asli ada di `docs/reference/` dan tidak diubah.

## Alur Kolaborasi

- Phase 0 (dokumentasi) dikerjakan langsung di `main`.
- Fase implementasi memakai branch `phase/XX-nama` dan pull request ke `main`.
- Commit memakai Conventional Commits.
- Setiap fase memperbarui laporan di `docs/progress/`, `PROJECT_STATUS.md`, `UAS_TRACEABILITY.md`,
  dan `CONTRIBUTION_LOG.md`.
- Pembagian kerja bersifat fleksibel: setiap anggota boleh mengerjakan task pada fase mana saja
  selama commit menggunakan **email Git masing-masing**. Kontribusi dicatat berdasarkan histori
  commit, review PR, dan dokumentasi di `docs/CONTRIBUTION_LOG.md`, lalu difinalkan pada Phase 5.

## Tim

| Anggota | Nama / NIM | Email Git |
|---|---|---|
| Anggota 1 | Benny Anderson / 422024008 | `benanderson2nds@gmail.com` |
| Anggota 2 | Steven Credentia Ivanemaga Zega / 422024020 | `steven.422024020@civitas.ukrida.ac.id` |
| Anggota 3 | Jason / 422024004 | Email Git masing-masing (belum ada commit tercatat) |
| Anggota 4 | Steven / 422024001 | `steven.422024001@civitas.ukrida.ac.id` |

> Pembagian kontribusi tidak berpatokan pada fase secara kaku. Setiap anggota dapat mengerjakan
> fase mana saja selama riwayat commit-nya tercatat pada email Git masing-masing. Rekap kontribusi
> lengkap dicatat di `docs/CONTRIBUTION_LOG.md`.

## Lisensi

Proyek akademik untuk UAS. Tidak untuk distribusi komersial. Seluruh kode ditulis untuk keperluan
pembelajaran dan harus dipahami oleh seluruh anggota tim.
