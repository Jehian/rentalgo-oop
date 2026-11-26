# Contribution Log — RentalGo

Dokumen ini mencatat kontribusi **nyata** setiap anggota. Jangan mengisi baris untuk pekerjaan yang
belum dikerjakan. Log ini menjadi bukti pendukung penilaian individu (UAS-IND-01 dan UAS-IND-02).

## 1. Cara Mengisi

- Tambahkan satu baris setiap kali menyelesaikan pekerjaan bermakna (fitur, test, dokumentasi,
  review, perbaikan bug).
- Isi commit/PR setelah commit dibuat; gunakan `pending` jika belum di-commit.
- Kolom verifikasi diisi dengan perintah/hasil nyata, bukan asumsi.
- Kolom reviewer diisi nama anggota yang mereview; `pending` jika belum.
- Jangan menghapus baris lama; koreksi dengan baris baru atau catatan.

## 2. Format Baris

| Tanggal | Anggota | Fase | Branch | Commit/PR | Area/File | Deskripsi | Verifikasi | Reviewer |
|---|---|---|---|---|---|---|---|---|
| 2026-11-25 | Benny Anderson (422024008) | Phase 0 | `main` | `commit Phase 0` | `PRD.md`, `AGENTS.md`, `README.md`, `docs/*` | Menyusun requirements, PRD, traceability, arsitektur, schema, phase plan, template, dan workflow | 66/66 ID traceable; `php artisan test` 2 passed; Pint passed; koneksi MySQL `rentalgo` terverifikasi | `pending` |
| 2026-11-25 | Benny Anderson (422024008) | Phase 1 | `phase/01-foundation` | `8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`, `22fb7c5`; PR #1 (merge `1b5caee`) | `app/*`, `config/rental.php`, `database/*`, `routes/*`, `tests/*`, `docs/progress/phase-01-foundation.md` | Konfigurasi, migration 5 tabel + role, model/relasi/cast/scope, domain OOP, `RentalService`, factory/seeder, route file kosong, test fondasi | `php artisan migrate:fresh --seed --force` berhasil; `php artisan test` 25 passed (57 assertions); Pint passed; data demo 2/8/8/12/12 | Steven Credentia Ivanemaga Zega (422024020) |
| 2026-11-25 | Steven Credentia Ivanemaga Zega (422024020) | Phase 1 review | `main` | PR #1 (`1b5caee`) | PR Phase 1 | Mereview fondasi Phase 1 sebelum merge | PR #1 merged ke `main` | — |
| 2026-11-25 | Steven Credentia Ivanemaga Zega (422024020) | Phase 2 | `phase/02-auth-dashboard` | `da691a8`, `963c356`, `f68f1e9`, `d670468`, `ee5a754` | `app/Http/Controllers/*`, `app/Http/Requests/*`, `app/Http/Middleware/*`, `app/Services/*`, `app/Models/Customer.php`, `config/auth.php`, `database/migrations/2026_11_25_000006_*`, `resources/views/*`, `resources/js/app.js`, `routes/*`, `tests/Feature/*`, `docs/*` | Auth operator (`web`) & pelanggan terpisah (`customer` di tabel `customers`), middleware `admin`, layout operator & publik sederhana, 7 komponen Blade, dashboard + Chart.js, portal pelanggan (katalog, detail/estimasi, booking mandiri, riwayat, profil), test, dokumentasi Phase 2, ADR-028 | `php artisan test` 47 passed (153 assertions); Pint passed; `npm run build` passed | `pending` |
| 2026-11-25 | Benny Anderson (422024008) | Phase 2 merge | `main` | PR #2 (merge `c4847c2`) | PR Phase 2 | Menggabungkan Phase 2 ke `main` | `git log`: merge commit `c4847c2` | — |
| 2026-11-25 | Jason (422024004) | Phase 3 | `phase/03-master-data` | `pending` - commit/PR belum dibuat | `app/Http/Controllers/CustomerController.php`, `VehicleController.php`, `app/Http/Requests/*`, `app/Services/VehicleImageService.php`, `resources/views/customers/*`, `resources/views/vehicles/*`, `routes/master.php`, `tests/Feature/Customers/*`, `tests/Feature/Vehicles/*`, `docs/*` | Implementasi lokal CRUD pelanggan/kendaraan, search/filter/sort/pagination, upload/ganti gambar, validasi, soft delete guard, test, screenshot, dan dokumentasi Phase 3 | `php artisan test` 74 passed (293 assertions); Pint passed; `npm run build` passed; smoke test HTTP + screenshot | `pending` |

> Catatan: baris Phase 2 awalnya dicatat `pending`; PR #2 kemudian digabung ke `main` melalui
> `c4847c2`. Baris Phase 3 mencatat implementasi lokal pemilik fase dan belum final sampai commit/PR
> dibuat dengan identitas kontributor yang sesuai.

## 3. Contoh Pengisian (Hapus/abaikan saat produksi)

Baris berikut hanya contoh format, **bukan kontribusi nyata**:

| Tanggal | Anggota | Fase | Branch | Commit/PR | Area/File | Deskripsi | Verifikasi | Reviewer |
|---|---|---|---|---|---|---|---|---|
| YYYY-MM-DD | Anggota X | Phase N | `phase/0N-nama` | `abc1234` / PR #N | `path/file.php` | Deskripsi singkat | `php artisan test` (N passed) | Anggota Y |

## 4. Rekap per Anggota

| Anggota | Fase Utama | Jumlah Baris Log | Status |
|---|---|---:|---|
| Benny Anderson (422024008) | Phase 0, Phase 1, Phase 2 merge | 3 | Phase 0 selesai; Phase 1 merged (PR #1); Phase 2 merged (PR #2) |
| Steven Credentia Ivanemaga Zega (422024020) | Phase 1 review, Phase 2 | 2 | Review PR #1; Phase 2 merged (PR #2) |
| Jason (422024004) | Phase 3 | 1 | Phase 3 implemented lokal; commit/PR pending |
| Steven (422024001) | Phase 4 | 0 | Belum mulai |

Rekap ini diperbarui pada setiap phase report.

## 5. Aturan Kontribusi

1. Satu anggota tidak boleh mengklaim pekerjaan anggota lain.
2. Commit harus atas nama kontributor sebenarnya.
3. Review PR juga dihitung sebagai kontribusi; catat di log.
4. Dokumentasi, test, dan perbaikan bug dihitung; pekerjaan yang direncanakan tetapi tidak
   dikerjakan tidak dihitung.
5. Jika kontribusi tidak seimbang, catat apa adanya; jangan memanipulasi log.
6. Log ini harus konsisten dengan riwayat Git dan phase report.

## 6. Kaitan dengan Penilaian UAS

| Faktor UAS | Bukti di Repository |
|---|---|
| UAS-IND-01 Kontribusi coding | Baris log + file/PR |
| UAS-IND-02 Commit GitHub | Commit author + riwayat branch |
| UAS-IND-03 Presentasi individu | Bagian slide/demo (dicatat Phase 5) |
| UAS-IND-04 Pemahaman kode | Code walkthrough internal (dicatat Phase 5) |
