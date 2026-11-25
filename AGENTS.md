# AGENTS.md — Aturan Kerja RentalGo

Dokumen ini wajib dibaca oleh setiap AI agent dan anggota tim sebelum mengubah repository.

## 1. Konteks Proyek

RentalGo adalah aplikasi web rental kendaraan untuk UAS mata kuliah Pemrograman Berorientasi Objek.
Fokus penilaian adalah implementasi OOP yang dapat dipahami, MVC sederhana, CRUD, autentikasi,
relasi database, validasi, dokumentasi, dan presentasi. Proyek **bukan** aplikasi enterprise.

Stack: Laravel 13, PHP 8.3+, MySQL (utama), SQLite (test), Blade, Tailwind CSS 4, Vite 8, PHPUnit 12.
Docker tidak digunakan.

## 2. Sumber Kebenaran

Urutan prioritas:

1. Dokumen UAS asli: `docs/reference/UAS_Cynthia Hayat_SI2WPO084 – Pemrograman Berorientasi Objek (1).doc`
   — jangan diubah.
2. `docs/UAS_REQUIREMENTS.md` — ekstraksi requirement.
3. `PRD.md` — kebutuhan produk.
4. `docs/UAS_TRACEABILITY.md` — status/lokasi requirement.
5. `docs/ARCHITECTURE.md` dan `docs/DATABASE_SCHEMA.md`.
6. `docs/DECISIONS.md` — keputusan teknis.
7. `docs/PHASE_PLAN.md` — scope fase dan kepemilikan file.
8. `docs/PROJECT_STATUS.md` — status terbaru.
9. Prompt tugas dari manusia.

Jika terjadi konflik, dokumen UAS menang. Jangan mengarang requirement baru; catat sebagai
`Recommended`/`Proposed` dan minta konfirmasi.

## 3. Wajib Dibaca Sebelum Bekerja

- `docs/AI_HANDOFF.md`
- `docs/PHASE_PLAN.md` (fase yang dikerjakan)
- `docs/PROJECT_STATUS.md`
- Dokumen teknis terkait modul
- Laporan fase sebelumnya di `docs/progress/`

## 4. Aturan yang Tidak Boleh Dilanggar

1. **Jangan bekerja langsung di `main` untuk implementasi.** Phase 0 dokumentasi adalah pengecualian
   yang disetujui pemilik proyek.
2. **Jangan mengubah dokumen UAS** di `docs/reference/`.
3. **Jangan mengedit file milik anggota lain** tanpa koordinasi; lihat matriks kepemilikan di
   `docs/PHASE_PLAN.md`.
4. **Jangan menambah dependency** (`composer require`, `npm install <paket>`) tanpa persetujuan.
5. **Jangan commit** `.env`, kredensial, token, `vendor/`, `node_modules/`, atau build artifact.
6. **Jangan mengklaim fitur selesai** tanpa implementasi dan verifikasi nyata.
7. **Jangan menyalin kode** dari internet/proyek lain tanpa izin dan atribusi; ini proyek akademik.
8. **Jangan mengubah schema database** di luar fase yang ditentukan; migration dimiliki Phase 1.
9. **Jangan memakai Docker** atau menambahkan Dockerfile/compose.
10. **Jangan menghapus riwayat transaksi** secara permanen; gunakan aturan status yang berlaku.

## 5. Status Repository Saat Ini

- Phase 0: selesai dan di-commit di `main` (`510a309`).
- Phase 1: committed dan di-push di branch `phase/01-foundation`; belum PR/merge.
- Fondasi tersedia: konfigurasi, migration, model, domain OOP, service, factory, seeder, test.
- UI, autentikasi, controller, dan CRUD belum ada; dikerjakan Phase 2–4.

Jangan menganggap kode Phase 1 sudah tersedia di `main` sebelum branch `phase/01-foundation` digabung.

## 6. Branching

| Fase | Branch |
|---|---|
| Phase 0 (dokumentasi) | `main` — pengecualian |
| Phase 1 | `phase/01-foundation` |
| Phase 2 | `phase/02-auth-dashboard` |
| Phase 3 | `phase/03-master-data` |
| Phase 4 | `phase/04-rental-payment` |
| Phase 5 | `phase/05-integration` |

Ketentuan:

- Buat branch dari `main` terbaru setelah fase prasyarat digabung.
- Satu branch untuk satu fase.
- Jangan force-push branch yang sedang direview.
- Rebase/merge `main` terbaru sebelum membuka PR.
- Hapus branch setelah merge.

## 7. Commit dan Pull Request

Format commit (Conventional Commits):

```text
feat(auth): add login and logout
fix(rental): prevent overlapping active rentals
docs(phase-1): add foundation report
test(domain): cover car insurance pricing
refactor(vehicle): simplify factory mapping
chore(config): add rental config
```

Aturan:

- Commit kecil, satu tujuan, pesan jelas.
- Setiap anggota commit dengan identitas Git sendiri.
- PR wajib untuk fase implementasi; merge memakai merge commit agar histori individu terlihat.
- PR harus menyertakan: ringkasan, file yang diubah, cara verifikasi, screenshot UI (jika ada),
  phase report, dan status dokumentasi.
- Minimal satu anggota lain mereview.
- **AI agent tidak boleh menjalankan `git commit`, `git push`, atau membuat PR kecuali manusia
  secara eksplisit meminta.** Manusia pemilik tugas yang mengotorisasi commit/submission.

## 8. Konvensi Kode

### 8.1 PHP dan Laravel

- Ikuti PSR-12 dan Laravel Pint.
- Gunakan `declare(strict_types=1)`? **Tidak wajib**; ikuti gaya file yang ada.
- Gunakan typed property, parameter, dan return type bila memungkinkan.
- Gunakan constructor property promotion untuk dependency injection.
- Nama class `PascalCase`; method/variabel `camelCase`; tabel/kolom `snake_case`.
- Namespace mengikuti PSR-4 (`App\...`).
- Hindari helper global baru; gunakan service/support class.
- Jangan menambah komentar yang hanya mengulang kode. Gunakan nama jelas; docblock hanya untuk
  kontrak method yang kompleks.
- Semua teks UI berbahasa Indonesia; identifier kode berbahasa Inggris.

### 8.2 OOP

- Gunakan `App\Contracts\Rentable` dan abstract `App\Domain\Vehicles\Vehicle` untuk kontrak kendaraan.
- `Car` dan `Motorcycle` wajib meng-override `calculateRentalCost()`.
- Jangan memaksakan single-table Eloquent inheritance; gunakan `VehicleFactory`.
- Enkapsulasi: property domain private/protected + getter; jangan expose state yang bisa diubah
  sembarangan.
- Exception domain: `VehicleNotAvailableException`, `UnsupportedVehicleTypeException`.
- Static method hanya untuk operasi murni/factory (`VehicleFactory`, `DocumentCodeGenerator`).
- Jangan menambah design pattern baru tanpa alasan kuat dan dokumentasi di `DECISIONS.md`.

### 8.3 Eloquent

- Model di `App\Models`.
- Gunakan atribut Laravel 13 (`#[Fillable]`, `#[Hidden]`) sesuai skeleton; jangan mencampur gaya lama
  tanpa alasan.
- Definisikan `casts()` untuk enum, tanggal, dan decimal.
- Definisikan relasi eksplisit dengan return type.
- Gunakan scope untuk query berulang (`available`, `ofType`).
- Hindari query di Blade; siapkan data di controller/service.
- Gunakan eager loading untuk relasi yang ditampilkan di daftar.

### 8.4 Controller, Request, Service

- Controller tipis: validasi di Form Request, bisnis di service, query sederhana di model.
- Form Request: `StoreXxxRequest`, `UpdateXxxRequest`; pesan error Bahasa Indonesia.
- Service: constructor injection; jangan akses `request()`/session di service.
- Jangan menduplikasi logika availability/pricing di controller.
- Gunakan database transaction untuk operasi multi-tabel (rental/payment).

### 8.5 Blade dan Tailwind

- Layout: `resources/views/layouts/app.blade.php`.
- Komponen reusable: `x-alert`, `x-button`, `x-card`, `x-input`, `x-modal`, `x-pagination`,
  `x-status-badge`.
- Gunakan `{{ }}` untuk escaping; `{!! !!}` hanya untuk HTML tepercaya.
- Form wajib `@csrf`; method spoofing `@method('PUT'/'DELETE')` bila perlu.
- Tampilkan error validasi dekat field.
- Tailwind utility-first; hindari CSS global yang tidak perlu.
- Pastikan responsif di mobile dan desktop.

## 9. Aturan Arsitektur

- Layer: route → middleware → controller → Form Request → service → domain/model → database.
- Domain tidak boleh bergantung pada HTTP/request.
- Service tidak boleh mengembalikan response HTTP.
- Model tidak menyimpan logika presentasi.
- Route per modul: `auth.php`, `dashboard.php`, `master.php`, `rental.php`; `web.php` hanya include.
- Jangan menambah repository pattern generik, DDD aggregate, CQRS, event sourcing, atau SPA.

## 10. Database

- Schema final ada di `docs/DATABASE_SCHEMA.md`.
- Lima tabel bisnis: `users`, `customers`, `vehicles`, `rentals`, `payments`.
- Uang `DECIMAL(12,2)`; jangan `FLOAT`.
- Foreign key dan index wajib sesuai dokumen.
- Migration hanya diedit Anggota 1/Phase 1; fase lain mengajukan perubahan.
- Jangan mengubah data produksi; gunakan `migrate:fresh --seed` di lokal.
- Soft delete pelanggan/kendaraan; rental memakai status; payment pending boleh dihapus.
- MySQL untuk demo; SQLite untuk test; hindari SQL khusus vendor.

## 11. Testing

- Setiap fase wajib menambah test relevan.
- Unit test: domain pricing, factory, code generator, aturan murni.
- Feature test: auth, CRUD, rental lifecycle, payment, validasi.
- Gunakan `RefreshDatabase` dan factory; jangan bergantung pada data seed untuk unit test.
- Jalankan `php artisan test` sebelum PR.
- Jangan menghapus/melemahkan test untuk membuat build lulus.
- Test harus bermakna; bukan sekadar mengejar coverage.

## 12. Keamanan

- Password di-hash; jangan pernah menyimpan plaintext.
- Gunakan middleware `auth` dan role.
- Validasi semua input di server.
- Gunakan CSRF token pada form.
- Escape output Blade.
- Validasi upload (MIME, ukuran, ekstensi).
- Jangan membocorkan detail exception ke user; log detail di server.
- Jangan commit secret; gunakan `.env.example` dengan placeholder.
- Jangan menonaktifkan proteksi mass assignment tanpa alasan.

## 13. Dokumentasi Wajib Diperbarui

Setiap fase selesai:

- `docs/progress/phase-XX-nama.md` — laporan fase dari template.
- `docs/PROJECT_STATUS.md` — status dan metrik.
- `docs/UAS_TRACEABILITY.md` — status requirement yang dikerjakan.
- `docs/CONTRIBUTION_LOG.md` — kontribusi nyata.
- `README.md` — jika setup/fitur berubah.
- `docs/DECISIONS.md` — jika ada keputusan teknis baru.

Jangan menandai `Verified` tanpa bukti. Jangan membuat laporan fase fiktif.

## 14. Perintah Penting

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
php artisan migrate:fresh --seed
php artisan storage:link
npm run dev
npm run build
php artisan serve
php artisan test
vendor/bin/pint
vendor/bin/pint --test
```

MySQL:

```powershell
mysql -u root -p -e "CREATE DATABASE rentalgo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## 15. Definition of Done

- [ ] Scope fase selesai, tidak melebar.
- [ ] Test relevan ditambahkan dan lulus.
- [ ] `php artisan test` lulus.
- [ ] `vendor/bin/pint --test` lulus.
- [ ] Tidak ada error kritis.
- [ ] UI responsif (jika ada UI).
- [ ] Phase report, status, traceability, contribution log diperbarui.
- [ ] PR direview minimal satu anggota lain.
- [ ] Merge ke `main` selesai.

## 16. Perilaku AI Agent

- Mulai dengan membaca dokumen dan memeriksa `git status`.
- Nyatakan asumsi jika ada hal ambigu.
- Jangan mengubah file di luar scope tanpa izin.
- Jangan menambah dependency tanpa persetujuan.
- Jalankan test/verifikasi sebelum melaporkan selesai.
- Laporkan file yang dibuat/diubah dan hasil test.
- Jika menemukan bug di luar scope, catat; jangan perbaiki diam-diam.
- Jangan melakukan commit/push/PR tanpa instruksi eksplisit manusia.
- Jangan menghapus pekerjaan orang lain.
- Akhiri dengan ringkasan jujur: apa yang selesai, apa yang belum, dan risiko.

## 17. Di Luar Scope

- Customer portal/registrasi publik.
- Payment gateway.
- Email/SMS nyata.
- Multi-cabang/multi-tenant.
- GPS/real-time notification.
- Aplikasi mobile.
- Akuntansi formal.
- Docker.
- Enterprise patterns (microservice, CQRS, event sourcing).
