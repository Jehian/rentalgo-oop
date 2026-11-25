# Phase 2 — Authentication, Layout & Dashboard

| Item | Keterangan |
|---|---|
| Fase | Phase 2 — Authentication, Layout & Dashboard |
| Pemilik | Steven Credentia Ivanemaga Zega (422024020) |
| Branch | `phase/02-auth-dashboard` |
| Pull Request | Belum dibuat |
| Tanggal mulai | 2026-11-25 |
| Tanggal selesai | 2026-11-25 (implementasi lokal) |
| Status | Implemented — menunggu PR/review |
| Reviewer | `pending` |

## 1. Ringkasan

Phase 2 menambahkan autentikasi custom operator (`web` guard di tabel `users`) dan pelanggan
terpisah (`customer` guard di tabel `customers`), middleware role `admin`, layout operator dan layout
portal publik responsif yang sederhana, komponen Blade reusable, dashboard Admin/Staff dengan Chart.js,
serta portal pelanggan (katalog kendaraan, detail & estimasi tarif, booking mandiri melalui
`RentalService`, riwayat/detail/pembatalan pesanan, dan pengelolaan profil). Seluruh verifikasi test
suite, Pint, dan build Vite lulus.

## 2. Tujuan vs Hasil

| Tujuan | Hasil | Bukti |
|---|---|---|
| Custom login/logout/session | Tercapai | `AuthController`, `LoginRequest`, `routes/auth.php` |
| Middleware `auth` dan `EnsureUserIsAdmin` | Tercapai | `app/Http/Middleware/EnsureUserIsAdmin.php`, alias `admin` di `bootstrap/app.php` |
| Layout utama responsif | Tercapai | `resources/views/layouts/app.blade.php`, `guest.blade.php` |
| Komponen Blade reusable | Tercapai | `resources/views/components/*` |
| Dashboard ringkasan dan grafik | Tercapai | `DashboardController`, `DashboardService`, `views/dashboard/index.blade.php` |
| Chart.js via npm | Tercapai | `package.json` (`chart.js` 4.6.2), `resources/js/app.js` |
| Feature test auth dan dashboard | Tercapai | `tests/Feature/Auth/AuthTest.php`, `tests/Feature/Dashboard/DashboardTest.php` |
| Isi `routes/auth.php` dan `dashboard.php` | Tercapai | Route login, logout, dashboard |
| PR/review/merge | Belum | Branch belum di-push/PR |

## 3. Scope yang Diselesaikan

- [x] Login dengan email/password untuk operator (`web` guard) dan pelanggan (`customer` guard) secara terpisah, lengkap dengan validasi server-side dan rate limit 5 percobaan.
- [x] Registrasi publik pelanggan (`/customer/register`) yang membuat record `Customer` langsung tanpa menyentuh tabel `users`.
- [x] Logout terpisah (`/logout` untuk operator, `/customer/logout` untuk pelanggan) dengan session invalidate dan regenerate token.
- [x] Session regenerate setelah login berhasil.
- [x] Middleware `admin` (`EnsureUserIsAdmin`) serta isolasi guard `auth:web` vs `auth:customer`.
- [x] Layout utama operator (`app.blade.php`), layout guest sederhana, dan layout publik/pelanggan (`public.blade.php`).
- [x] Komponen `x-alert`, `x-button`, `x-card`, `x-input`, `x-modal`, `x-pagination`, `x-status-badge`.
- [x] Portal pelanggan publik: katalog kendaraan (`/`), search, filter tipe/status, sorting tarif, detail & estimasi biaya live, booking mandiri via `RentalService`, riwayat/detail pesanan, pembatalan rental aktif pribadi, dan edit profil.
- [x] Dashboard: 6 kartu ringkasan, grafik pendapatan 6 bulan, komposisi status kendaraan, 5 rental terbaru.
- [x] Chart.js melalui npm dan inisialisasi chart dari JSON yang disiapkan controller.
- [x] Halaman 403 berbahasa Indonesia.
- [x] Feature test auth, registrasi, isolasi guard, dashboard, dan portal pelanggan (47 test lulus, 153 assertions).
- [ ] PR, review anggota lain, dan merge ke `main`.

## 4. File yang Dibuat/Diubah

| File | Jenis | Keterangan |
|---|---|---|
| `app/Http/Controllers/Auth/AuthController.php` | Baru | `showLogin`, `login`, `logout` |
| `app/Http/Controllers/PortalController.php` | Baru | Katalog publik (`/`) dan detail kendaraan (`/catalog/{vehicle}`) |
| `app/Http/Controllers/Customer/CustomerAuthController.php` | Baru | Login, registrasi, dan logout pelanggan (`customer` guard) |
| `app/Http/Controllers/Customer/CustomerRentalController.php` | Baru | Booking mandiri, daftar, detail, dan pembatalan rental pelanggan |
| `app/Http/Controllers/Customer/CustomerProfileController.php` | Baru | Lihat dan perbarui profil pelanggan |
| `app/Http/Requests/Auth/LoginRequest.php` | Baru | Validasi, `Auth::attempt`, rate limit operator |
| `app/Http/Requests/Customer/CustomerLoginRequest.php` | Baru | Validasi dan rate limit login pelanggan (`customer` guard) |
| `app/Http/Requests/Customer/CustomerRegisterRequest.php` | Baru | Validasi registrasi pelanggan |
| `app/Http/Requests/Customer/CustomerBookingRequest.php` | Baru | Validasi booking mandiri pelanggan |
| `app/Http/Requests/Customer/UpdateCustomerProfileRequest.php` | Baru | Validasi update profil pelanggan |
| `app/Http/Middleware/EnsureUserIsAdmin.php` | Baru | Proteksi aksi Admin |
| `app/Models/Customer.php` | Diubah | Extend `Authenticatable`, cast `password` hashed, fillable auth |
| `app/Services/RentalService.php` | Diubah | Parameter `?User $user = null` dan `?string $notes = null` pada `createRental()` |
| `config/auth.php` | Diubah | Guard & provider `customer` terpisah dari `users` |
| `database/migrations/2026_11_25_000006_add_auth_columns_to_customers_and_nullable_user_id_on_rentals.php` | Baru | Kolom `password`/`remember_token` pada `customers` dan `rentals.user_id` nullable |
| `database/factories/CustomerFactory.php`, `database/seeders/CustomerSeeder.php` | Diubah | Password default & akun demo `customer@rentalgo.test` |
| `app/Http/Controllers/DashboardController.php` | Baru | Controller tipis dashboard |
| `app/Services/DashboardService.php` | Baru | Ringkasan, pendapatan bulanan, komposisi status, rental terbaru |
| `bootstrap/app.php` | Diubah | Alias `admin`, `redirectGuestsTo`, dan `redirectUsersTo` terpisah untuk guard `web` vs `customer` |
| `routes/web.php`, `routes/auth.php`, `routes/customer.php`, `routes/dashboard.php` | Diubah/Baru | Route auth operator, auth & portal pelanggan, dan dashboard |
| `resources/views/layouts/app.blade.php`, `guest.blade.php`, `public.blade.php` | Baru | Layout operator, guest sederhana, dan portal publik/pelanggan |
| `resources/views/components/*.blade.php` | Baru | 7 komponen reusable |
| `resources/views/auth/login.blade.php` | Baru | Form login operator (Admin/Staff) |
| `resources/views/customer/auth/*`, `resources/views/portal/*`, `resources/views/customer/*` | Baru | Login/registrasi pelanggan, katalog kendaraan, detail & estimasi sewa, pesanan saya, profil |
| `resources/views/dashboard/index.blade.php` | Baru | Kartu, chart, tabel rental terbaru |
| `resources/views/errors/403.blade.php` | Baru | Halaman akses ditolak |
| `resources/js/app.js` | Diubah | Sidebar mobile, menu publik, modal, estimator booking, Chart.js |
| `package.json`, `package-lock.json` | Diubah | Dependency `chart.js` 4.6.2 |
| `tests/TestCase.php`, `tests/Feature/ExampleTest.php`, `FoundationSchemaTest.php` | Diubah | `withoutVite()`, `RefreshDatabase`, cek kolom `user_id` |
| `tests/Feature/Auth/AuthTest.php`, `tests/Feature/Dashboard/DashboardTest.php`, `tests/Feature/Customer/CustomerPortalTest.php` | Baru | Feature test auth, registrasi, role, dashboard, dan portal pelanggan |
| `docs/assets/phase-02/*.png` | Baru | Screenshot login dan dashboard desktop/mobile |
| `docs/progress/phase-02-auth-dashboard.md` | Baru | Laporan fase ini |
| `PRD.md`, `AGENTS.md`, `README.md`, `docs/*` | Diubah | Pembaruan scope portal pelanggan (ADR-028), skema, arsitektur, status, traceability, contribution log |

## 5. Perubahan Database

Menambahkan migration `database/migrations/2026_11_25_000006_add_auth_columns_to_customers_and_nullable_user_id_on_rentals.php`
untuk menambah kolom `password` (nullable) dan `remember_token` pada tabel `customers` serta
menjadikan `rentals.user_id` nullable agar ID akun `users` (Admin/Staff) dan `customers` (Pelanggan)
terpisah sepenuhnya. Keputusan dicatat di ADR-028.

## 6. Pemetaan OOP dan UAS

| Requirement | Implementasi | File | Verifikasi |
|---|---|---|---|
| FR-AUTH-01 | Login email/password | `AuthController@login`, `LoginRequest`, `views/auth/login` | `AuthTest` login valid/invalid |
| FR-AUTH-02 | Logout dan session berakhir | `AuthController@logout` | `AuthTest` logout |
| FR-AUTH-03 | Proteksi halaman operasional | Middleware `auth`, session regenerate/invalidate | `AuthTest` guest redirect |
| FR-AUTH-04 | Pembeda Admin/Staff | `EnsureUserIsAdmin`, alias `admin` | `AuthTest` staff 403, admin 200 |
| FR-DASH-01 | Ringkasan dashboard | `DashboardService@summary` | `DashboardTest` nilai ringkasan |
| FR-DASH-02 | Grafik dan rental terbaru | `DashboardService@monthlyRevenue`, `vehicleStatusComposition`, `latestRentals`; Chart.js | `DashboardTest` + screenshot |
| UAS-FEA-01–04 | Login, logout, session, dashboard | Modul auth/dashboard | 13 feature test baru |
| UAS-INT-04 | Authentication sederhana | Modul auth Phase 2 | `AuthTest` |
| UAS-ARC-03 | Reusable component | 7 komponen Blade | Code review + screenshot |
| UAS-UI-01–02 | Tailwind dan layout responsif | `layouts/app`, `layouts/guest` | Screenshot desktop/mobile |
| UAS-UI-03 | Validasi form | `LoginRequest` + error dekat field | `AuthTest` validasi |
| UAS-OOP-06 | Constructor injection | `DashboardController`, `DashboardService`, middleware | Code review |

## 7. Pengujian dan Verifikasi

### 7.1 Perintah

```bash
php artisan test
vendor/bin/pint --test
npm run build
php artisan migrate:fresh --seed --force
```

### 7.2 Hasil

```text
Tests: 47 passed (153 assertions)
Pint: passed
Vite build: passed
- CSS 77.10 kB (gzip 14.59 kB)
- JS 209.82 kB (gzip 72.05 kB, termasuk Chart.js)
```

### 7.3 Manual Check

| Skenario | Langkah | Hasil |
|---|---|---|
| Login page | `GET /login` | 200, form tampil |
| Login invalid | POST password salah | Kembali ke login, pesan error tampil |
| Login valid | POST `admin@rentalgo.test` / `password` | Redirect `/dashboard`, sesi aktif |
| Dashboard | Buka `/dashboard` | 200, kartu, chart, dan rental terbaru tampil |
| Logout | POST `/logout` | Redirect `/login`, pesan "Anda berhasil keluar." |
| Proteksi route | `GET /dashboard` setelah logout | Redirect `/login` |
| Responsif | Screenshot 1440px dan 390px | Sidebar/off-canvas dan kartu menyesuaikan |
| Role middleware | Staff mengakses route `admin` | 403 (feature test) |

## 8. Screenshot (Jika Ada UI)

| Halaman | Desktop | Mobile |
|---|---|---|
| Login | `docs/assets/phase-02/login-desktop.png` | `docs/assets/phase-02/login-mobile.png` |
| Dashboard | `docs/assets/phase-02/dashboard-desktop.png` | `docs/assets/phase-02/dashboard-mobile.png` |

Screenshot diambil dari aplikasi yang berjalan dengan data seeder MySQL melalui headless Chrome.

## 9. Masalah, Deviasi, dan Keterbatasan

- Dokumentasi Phase 1 masih berstatus "pushed, belum PR" saat Phase 2 dimulai. Status disinkronkan
  pada fase ini: PR #1 sudah merged (`1b5caee`) dan reviewer adalah Steven Credentia Ivanemaga Zega.
- `package-lock.json` sebelumnya untracked; sekarang ikut dipertahankan karena `npm install chart.js`
  mengubah lockfile. Dependency baru hanya Chart.js sesuai persetujuan.
- Menu Pelanggan, Kendaraan, Rental, dan Pembayaran ditampilkan sebagai "Segera" karena route-nya
  belum dibuat Phase 3/4. Aktivasi link dilakukan saat fase terkait digabung.
- Pendapatan dashboard didefinisikan sebagai jumlah pembayaran berstatus `paid` berdasarkan `paid_at`,
  bukan total tagihan rental. Keputusan dicatat di `docs/DECISIONS.md`.
- Chart.js memakai `chart.js/auto`; belum ada pemisahan bundle khusus dashboard karena scope masih
  kecil.
- Ekstensi `intl` PHP belum aktif; nominal memakai `number_format`.
- Rate limit login 5 percobaan per email+IP, decay 60 detik.

## 10. Handoff ke Fase Berikutnya

- **Stabil:** auth, session, middleware `auth`/`admin`, layout responsif, komponen Blade, dashboard,
  Chart.js, dan pola controller tipis + service.
- **Phase 3:** gunakan `x-card`, `x-input`, `x-button`, `x-alert`, `x-pagination`, `x-status-badge`;
  aktifkan link sidebar Pelanggan/Kendaraan bersama pemilik layout; gunakan model/factory Phase 1.
- **Phase 4:** gunakan layout dan komponen yang sama; `RentalService` tetap satu-satunya tempat logika
  availability/pricing; middleware `admin` untuk aksi hapus.
- **Jangan:** mengubah migration/model/seeder; menambah dependency selain yang disetujui; membuat
  registrasi publik.
- **Risiko:** route Phase 3/4 belum ada sehingga sidebar masih menampilkan "Segera"; dashboard baru
  membaca data, belum ada aksi CRUD.

## 11. Checklist

- [x] Scope selesai (implementasi lokal).
- [x] Test ditambahkan dan lulus.
- [x] Pint lulus.
- [x] Build Vite lulus.
- [x] Tidak ada secret.
- [x] Dokumentasi status/traceability/contribution log diperbarui.
- [x] Screenshot disertakan.
- [ ] PR dibuat dan direview.
- [ ] Merge selesai.

## 12. Catatan Kontribusi

| Anggota | Kontribusi | Commit/PR |
|---|---|---|
| Steven Credentia Ivanemaga Zega (422024020) | Auth operator & pelanggan terpisah, middleware role, layout, komponen, dashboard, Chart.js, portal pelanggan, test, dokumentasi Phase 2 | `da691a8`, `963c356`, `f68f1e9`, `d670468`, `ee5a754` |
| Benny Anderson (422024008) | Fondasi Phase 1 yang dipakai Phase 2 | PR #1 (`1b5caee`) |
