# Phase 1 — Foundation & Architecture

| Item | Keterangan |
|---|---|
| Fase | Phase 1 — Foundation & Architecture |
| Pelaksana commit | Benny Anderson (422024008) — `benanderson2nds@gmail.com` |
| Branch | `phase/01-foundation` |
| Pull Request | [#1](https://github.com/Jehian/rentalgo-oop/pull/1) (merged) |
| Tanggal mulai | 2026-11-25 |
| Tanggal selesai | 2026-11-25 (merged `1b5caee`) |
| Status | Merged |
| Reviewer | Steven Credentia Ivanemaga Zega (422024020) |

## 1. Ringkasan

Phase 1 membangun fondasi backend RentalGo: konfigurasi, lima tabel bisnis, model Eloquent,
enum, domain OOP kendaraan, `RentalService`, factory, seeder demo, dan test. Tidak ada controller,
view, autentikasi, atau UI yang dibuat pada fase ini.

## 2. Tujuan vs Hasil

| Tujuan | Hasil | Bukti |
|---|---|---|
| Konfigurasi aplikasi dan aturan rental | Tercapai | `config/app.php`, `config/rental.php`, `.env.example` |
| Migration 5 tabel bisnis + role | Tercapai | 5 migration baru + 1 migration role |
| Model, relasi, cast, scope | Tercapai | `app/Models/*`, `tests/Feature/Foundation/FoundationSchemaTest.php` |
| Domain OOP kendaraan | Tercapai | `app/Contracts`, `app/Domain/Vehicles` |
| `RentalService` dan aturan bisnis | Tercapai | `app/Services/RentalService.php`, `RentalServiceTest` |
| Factory dan seeder demo | Tercapai | `database/factories`, `database/seeders` |
| Route file kosong per modul | Tercapai | `routes/auth.php`, `dashboard.php`, `master.php`, `rental.php` |
| Test dan Pint | Tercapai | 25 test lulus; Pint lulus |
| PR/review/merge | Tercapai | PR #1 direview Steven Credentia Ivanemaga Zega; merge commit `1b5caee` |

## 3. Scope yang Diselesaikan

- [x] Konfigurasi nama, timezone `Asia/Jakarta`, dan `config/rental.php`.
- [x] Migration `users.role`, `customers`, `vehicles`, `rentals`, `payments`.
- [x] Model `User`, `Customer`, `Vehicle`, `Rental`, `Payment` beserta relasi, cast, dan scope.
- [x] Enum `UserRole`, `VehicleType`, `VehicleStatus`, `RentalStatus`, `PaymentMethod`, `PaymentStatus`.
- [x] `Rentable`, abstract `Vehicle`, `Car`, `Motorcycle`, `VehicleFactory`.
- [x] Custom exception dan `DocumentCodeGenerator`.
- [x] `RentalService` untuk create/update/complete/cancel dan perhitungan denda.
- [x] Factory dan seeder data demo.
- [x] Route file kosong per modul dan include di `routes/web.php`.
- [x] Unit/feature test Phase 1.
- [x] Commit lokal Phase 1 (`8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`).
- [x] Push branch `phase/01-foundation` (`22fb7c5`).
- [x] PR #1 direview Steven Credentia Ivanemaga Zega dan di-merge (`1b5caee`).

## 4. File yang Dibuat/Diubah

| File | Jenis | Keterangan |
|---|---|---|
| `config/rental.php` | Baru | Tarif, diskon, denda, prefix kode, image folder |
| `config/app.php` | Diubah | Timezone dari `APP_TIMEZONE`, default `Asia/Jakarta` |
| `.env.example` | Diubah | `APP_TIMEZONE=Asia/Jakarta` |
| `app/Models/User.php` | Diubah | Role cast, fillable, relasi rental |
| `app/Models/Customer.php` | Baru | Model pelanggan, soft delete, relasi |
| `app/Models/Vehicle.php` | Baru | Model kendaraan, enum cast, scope, soft delete |
| `app/Models/Rental.php` | Baru | Model rental, relasi, scope overlap, total |
| `app/Models/Payment.php` | Baru | Model pembayaran, relasi, scope pending |
| `app/Enums/*` | Baru | 6 enum dengan label Indonesia |
| `app/Contracts/Rentable.php` | Baru | Interface domain kendaraan |
| `app/Domain/Vehicles/*` | Baru | Abstract `Vehicle`, `Car`, `Motorcycle`, `VehicleFactory` |
| `app/Exceptions/*` | Baru | Dua exception domain |
| `app/Support/DocumentCodeGenerator.php` | Baru | Generator kode rental/payment |
| `app/Services/RentalService.php` | Baru | Service lifecycle rental |
| `database/migrations/2026_11_25_000001_add_role_to_users_table.php` | Baru | Menambah role + index |
| `database/migrations/2026_11_25_000002_create_customers_table.php` | Baru | Tabel pelanggan |
| `database/migrations/2026_11_25_000003_create_vehicles_table.php` | Baru | Tabel kendaraan |
| `database/migrations/2026_11_25_000004_create_rentals_table.php` | Baru | Tabel rental + FK |
| `database/migrations/2026_11_25_000005_create_payments_table.php` | Baru | Tabel pembayaran + FK |
| `database/migrations/0001_01_01_000000_create_users_table.php` | Diubah | Panjang email/session id untuk batas index MySQL lokal |
| `database/migrations/0001_01_01_000001_create_cache_table.php` | Diubah | Panjang key untuk batas index MySQL lokal |
| `database/migrations/0001_01_01_000002_create_jobs_table.php` | Diubah | Panjang string terindeks untuk batas index MySQL lokal |
| `database/factories/UserFactory.php` | Diubah | State `admin()`/`staff()` dan role default |
| `database/factories/CustomerFactory.php` | Baru | Factory pelanggan |
| `database/factories/VehicleFactory.php` | Baru | Factory mobil/motor |
| `database/factories/RentalFactory.php` | Baru | Factory rental |
| `database/factories/PaymentFactory.php` | Baru | Factory pembayaran |
| `database/seeders/DatabaseSeeder.php` | Diubah | Memanggil seeder fase 1 |
| `database/seeders/UserSeeder.php` | Baru | Admin/staff demo |
| `database/seeders/CustomerSeeder.php` | Baru | 8 pelanggan demo |
| `database/seeders/VehicleSeeder.php` | Baru | 4 mobil + 4 motor |
| `database/seeders/RentalSeeder.php` | Baru | 12 rental demo |
| `database/seeders/PaymentSeeder.php` | Baru | 12 pembayaran demo |
| `routes/web.php` | Diubah | Include route modul |
| `routes/auth.php`, `dashboard.php`, `master.php`, `rental.php` | Baru | File kosong untuk fase berikutnya |
| `tests/Unit/Domain/*` | Baru | Test pricing, inheritance, factory |
| `tests/Unit/Support/DocumentCodeGeneratorTest.php` | Baru | Test kode dokumen |
| `tests/Feature/Foundation/RentalServiceTest.php` | Baru | Test lifecycle dan aturan bisnis |
| `tests/Feature/Foundation/FoundationSchemaTest.php` | Baru | Test tabel, enum cast, soft delete |

## 5. Perubahan Database

| Tabel/Kolom | Perubahan | Migration |
|---|---|---|
| `users.role` | `VARCHAR(20)` default `staff`, index | `2026_11_25_000001_add_role_to_users_table.php` |
| `customers` | Tabel baru, unique email/identity/license, soft delete | `2026_11_25_000002_create_customers_table.php` |
| `vehicles` | Tabel baru, unique plate, enum string, soft delete | `2026_11_25_000003_create_vehicles_table.php` |
| `rentals` | Tabel baru, FK customer/vehicle/user, kode unik | `2026_11_25_000004_create_rentals_table.php` |
| `payments` | Tabel baru, FK rental, kode unik | `2026_11_25_000005_create_payments_table.php` |
| Tabel skeleton | Panjang string terindeks disesuaikan dengan batas index MySQL lokal | Tiga migration skeleton |

## 6. Pemetaan OOP dan UAS

| Requirement | Implementasi | File | Verifikasi |
|---|---|---|---|
| UAS-OOP-01 | 13 class + 1 interface + 6 enum | `app/Domain`, `app/Models`, `app/Services`, `app/Support` | Hitung class + test |
| UAS-OOP-02 | Property protected/private + getter | `app/Domain/Vehicles/Vehicle.php` | Code review + unit test |
| UAS-OOP-03 | `Car`/`Motorcycle` extends `Vehicle` | `app/Domain/Vehicles` | `CarTest`, `MotorcycleTest` |
| UAS-OOP-04 | Override `calculateRentalCost()` | `Car.php`, `Motorcycle.php` | Unit test pricing |
| UAS-OOP-05 | Interface `Rentable` + abstract `Vehicle` | `app/Contracts`, `app/Domain` | Code review |
| UAS-OOP-06 | Constructor domain + typed dependency | Domain classes, service | Test + review |
| UAS-OOP-07 | `VehicleFactory`, `DocumentCodeGenerator` static | `app/Domain/Vehicles/VehicleFactory.php`, `app/Support` | Unit test |
| UAS-OOP-08 | PSR-4 namespace | `app/*`, `database/*`, `tests/*` | Autoload + test |
| UAS-OOP-09 | Custom exception + try-catch | `app/Exceptions`, `VehicleFactory` | Unit/feature test |
| UAS-INT-02 | Minimal 8 class | Lihat UAS-OOP-01 | Hitung class |
| UAS-INT-03 | Minimal 4 tabel | 5 tabel bisnis | `FoundationSchemaTest` + seeder |
| UAS-INT-06 | Relasi database | FK migration + relasi Eloquent | `FoundationSchemaTest`, `RentalServiceTest` |

## 7. Pengujian dan Verifikasi

### 7.1 Perintah

```bash
php artisan test
php artisan migrate:fresh --seed --force
vendor/bin/pint --test
```

### 7.2 Hasil

```text
Tests: 25 passed (57 assertions)
Pint: passed
migrate:fresh --seed: berhasil di MySQL 9.1
```

Data demo setelah seeder:

```text
users=2, customers=8, vehicles=8, rentals=12, payments=12
active_rentals=6, completed_rentals=6
available_vehicles=2, rented_vehicles=6
```

### 7.3 Manual Check

| Skenario | Langkah | Hasil |
|---|---|---|
| Migrasi MySQL | `php artisan migrate:fresh --seed --force` | Lulus |
| Test SQLite | `php artisan test` | 25 lulus |
| Gaya kode | `vendor/bin/pint --test` | Lulus |
| Seeder konsisten | Hitung data via Tinker | 12 rental, 12 payment, status kendaraan konsisten |
| Route file ter-include | `php artisan route:list --except-vendor` | Perintah berjalan; route fitur masih kosong sesuai scope |

## 8. Screenshot (Jika Ada UI)

Tidak ada; Phase 1 tidak membuat UI.

## 9. Masalah, Deviasi, dan Keterbatasan

- MySQL 9.1 lokal menolak unique index `VARCHAR(255)` dengan `utf8mb4` karena batas key 1000 byte.
  Solusi: panjang string terindeks pada migration skeleton dibatasi (`email` 150, key/id 191).
- `intl` belum aktif di PHP CLI; verifikasi memakai `number_format`/Tinker, bukan `Number::format`.
- `curl` diblokir Application Control; hanya warning startup, tidak menghalangi test/Artisan.
- Route file sengaja kosong; route fitur baru diisi Phase 2–4.
- PR #1 sudah direview dan di-merge ke `main` (`1b5caee`); status laporan diperbarui saat Phase 2.

## 10. Handoff ke Fase Berikutnya

- Stabil: schema, model/relasi, enum, domain pricing, `RentalService`, factory, seeder.
- Phase 2: isi `routes/auth.php`/`dashboard.php`, buat auth, layout, dashboard, middleware role.
- Phase 3: isi `routes/master.php`, CRUD pelanggan/kendaraan, gunakan `Vehicle`/`Customer`.
- Phase 4: isi `routes/rental.php`, CRUD rental/payment, gunakan `RentalService` dan `DocumentCodeGenerator`.
- Jangan menduplikasi logika harga/availability di controller; gunakan service/domain.
- Risiko: route file masih kosong; UI belum dapat diakses sampai Phase 2.

## 11. Checklist

- [x] Scope selesai (merged).
- [x] Test ditambahkan dan lulus.
- [x] Pint lulus.
- [x] Tidak ada secret di file yang diubah.
- [x] Dokumentasi status/traceability/contribution log diperbarui.
- [x] Commit lokal Phase 1 dibuat.
- [x] Branch Phase 1 di-push.
- [x] Screenshot tidak diperlukan (tidak ada UI).
- [x] PR dibuat dan direview (PR #1).
- [x] Merge selesai (`1b5caee`).

## 12. Catatan Kontribusi

| Anggota | Kontribusi | Commit/PR |
|---|---|---|
| Benny Anderson (422024008) | Konfigurasi, migration, model, domain, service, factory, seeder, test, dokumentasi Phase 1 | `8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`, `22fb7c5`; PR #1 |
| Steven Credentia Ivanemaga Zega (422024020) | Review PR #1 dan merge ke `main` | `1b5caee` |

> Rekap kontribusi final digabung di `docs/CONTRIBUTION_LOG.md` pada Phase 5 dan mengikuti author
> commit di Git.
