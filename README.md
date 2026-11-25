# RentalGo

Aplikasi web sistem informasi **rental kendaraan** yang dibangun dengan Laravel dan PHP OOP untuk
UAS mata kuliah Pemrograman Berorientasi Objek (SI2WPO084), Program Studi Sistem Informasi.

> **Status:** Phase 0 selesai. Phase 1 (Foundation & Architecture) sudah diimplementasikan dan
> di-commit lokal pada branch `phase/01-foundation`; UI/auth/CRUD belum tersedia dan Phase 1 belum
> di-push/di-merge.

## Tentang Proyek

RentalGo membantu pengelolaan operasional rental kendaraan: data pelanggan, data mobil/motor,
transaksi penyewaan, pengembalian, denda keterlambatan, pembayaran, dan dashboard ringkas.
Fokus proyek adalah penerapan OOP yang dapat dipahami, MVC sederhana, CRUD, relasi database,
autentikasi sederhana, validasi, dan dokumentasi.

## Fitur yang Direncanakan

- Login, logout, session, dan pembatasan akses Admin/Staff.
- Dashboard ringkasan dan grafik pendapatan/status kendaraan.
- CRUD pelanggan dengan pencarian, pagination, sorting, dan riwayat rental.
- CRUD kendaraan dengan filter, upload gambar, dan riwayat rental.
- Transaksi rental: cek ketersediaan, perhitungan biaya, pengembalian, denda, pembatalan.
- Pembayaran: catat, ubah status, edit/hapus selama pending.
- Validasi form dan error handling.
- UI responsif dengan Tailwind CSS.
- Bonus yang direncanakan: REST API read-only kendaraan/rental dan export PDF invoice/laporan.

Fitur tambahan yang ditargetkan: search, pagination, sorting, filter, upload gambar, dan dashboard
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
| Frontend | Blade, Tailwind CSS 4, Vite 8 |
| Testing | PHPUnit 12 |
| Code style | Laravel Pint |
| Version control | Git + GitHub |

Tidak digunakan: Docker, Breeze/Jetstream, framework SPA.

## Requirements

- PHP >= 8.3 dengan ekstensi standar Laravel.
- Composer.
- Node.js dan npm.
- MySQL 9.x via WAMP untuk aplikasi; SQLite hanya untuk automated test.

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

Jalankan aplikasi:

```powershell
npm run dev
php artisan serve
```

Jalankan test:

```powershell
php artisan test
```

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

## Tim

| Anggota | Nama / NIM | Tanggung Jawab |
|---|---|---|
| Anggota 1 | Benny Anderson / 422024008 | Phase 0 (planning) + Phase 1 (foundation) |
| Anggota 2 | Steven Credentia Ivanemaga Zega / 422024020 | Phase 2 (auth, layout, dashboard) |
| Anggota 3 | Jason / 422024004 | Phase 3 (master data) |
| Anggota 4 | Steven / 422024001 | Phase 4 (rental & payment) |

## Lisensi

Proyek akademik untuk UAS. Tidak untuk distribusi komersial. Seluruh kode ditulis untuk keperluan
pembelajaran dan harus dipahami oleh seluruh anggota tim.
