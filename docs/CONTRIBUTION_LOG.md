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
| 2026-11-25 | Benny Anderson (422024008) | Phase 1 | `phase/01-foundation` | `8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`, `22fb7c5` | `app/*`, `config/rental.php`, `database/*`, `routes/*`, `tests/*`, `docs/progress/phase-01-foundation.md` | Konfigurasi, migration 5 tabel + role, model/relasi/cast/scope, domain OOP, `RentalService`, factory/seeder, route file kosong, test fondasi | `php artisan migrate:fresh --seed --force` berhasil; `php artisan test` 25 passed (57 assertions); Pint passed; data demo 2/8/8/12/12 | `pending` |

## 3. Contoh Pengisian (Hapus/abaikan saat produksi)

Baris berikut hanya contoh format, **bukan kontribusi nyata**:

| Tanggal | Anggota | Fase | Branch | Commit/PR | Area/File | Deskripsi | Verifikasi | Reviewer |
|---|---|---|---|---|---|---|---|---|
| YYYY-MM-DD | Anggota X | Phase N | `phase/0N-nama` | `abc1234` / PR #N | `path/file.php` | Deskripsi singkat | `php artisan test` (N passed) | Anggota Y |

## 4. Rekap per Anggota

| Anggota | Fase Utama | Jumlah Baris Log | Status |
|---|---|---:|---|
| Benny Anderson (422024008) | Phase 0, Phase 1 | 2 | Phase 0 selesai; Phase 1 committed dan di-push, menunggu PR |
| Steven Credentia Ivanemaga Zega (422024020) | Phase 2 | 0 | Belum mulai |
| Jason (422024004) | Phase 3 | 0 | Belum mulai |
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
