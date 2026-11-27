# Contribution Log — RentalGo

Dokumen ini mencatat kontribusi **nyata** setiap anggota berdasarkan **histori commit (email Git)**
serta aktivitas review/PR dan deliverable akhir. Pembagian kerja **tidak** berpatokan pada phase
secara kaku: siapa saja boleh mengerjakan task pada phase mana saja selama histori commit tercatat
pada **email Git masing-masing anggota**. Log final direkonsiliasi dengan `git log` pada Phase 5.

## 1. Cara Mengisi

- Dasar pencatatan implementasi dan dokumentasi adalah **author email pada commit Git** (`git log`).
- Setiap anggota bebas berkontribusi di phase mana pun memakai identitas `user.name` dan `user.email`
  Git miliknya sendiri.
- Reviewer PR dan tugas akhir non-kode (presentasi, rehearsal, pengecekan manual) tetap dicatat
  meskipun tidak menghasilkan commit terpisah.
- Isi commit/PR setelah tersedia; gunakan `pending` untuk pekerjaan lokal yang belum di-commit.
- Verifikasi diisi perintah/hasil nyata, bukan asumsi.
- Jangan mengklaim pekerjaan atau commit milik anggota lain; koreksi dengan baris baru atau catatan.

## 2. Format Baris

| Tanggal | Fase | Task | Kontributor (Author Email / Pelaksana) | Commit/PR | File/Bukti | Verifikasi | Reviewer |
|---|---|---|---|---|---|---|---|

## 3. Catatan Kontribusi

| Tanggal | Fase | Task | Kontributor (Author Email / Pelaksana) | Commit/PR | File/Bukti | Verifikasi | Reviewer |
|---|---|---|---|---|---|---|---|
| 2026-11-25 | Phase 0 | Inisialisasi Laravel, requirements, PRD, traceability, arsitektur, schema, phase plan, template, workflow | Benny Anderson (`benanderson2nds@gmail.com`) | `517673f`, `510a309` | `PRD.md`, `AGENTS.md`, `README.md`, `docs/*` | 66/66 ID traceable; 2 test passed; Pint passed; MySQL terverifikasi | — |
| 2026-11-25 | Phase 1 | Fondasi: config, migration, model, domain OOP, service, factory, seeder, test, laporan Phase 1 | Benny Anderson (`benanderson2nds@gmail.com`) | `8510ea6`, `72490c1`, `c9ac1b7`, `c6c4dc8`, `eea47e9`, `22fb7c5`, `6391b28`; merge PR #1 (`1b5caee`) | `app/*`, `config/rental.php`, `database/*`, `routes/*`, `tests/*`, phase report | 25 passed (57 assertions); Pint passed; `migrate:fresh --seed` berhasil | Steven Credentia Ivanemaga Zega (`422024020`) |
| 2026-11-25 | Phase 1 review | Review PR #1 | Steven Credentia Ivanemaga Zega (`422024020`) | PR #1 (`1b5caee`) | PR Phase 1 | PR #1 merged | — |
| 2026-11-25 | Phase 2 | Auth operator & pelanggan, layout, dashboard, portal pelanggan, test, dokumentasi Phase 2 | Steven Credentia Ivanemaga Zega (`steven.422024020@civitas.ukrida.ac.id`) | `da691a8`, `963c356`, `f68f1e9`, `d670468`, `ee5a754`, `5257642` | `app/Http/*`, `app/Services/*`, `config/auth.php`, `resources/views/*`, `routes/*`, `tests/*`, `docs/*` | 47 passed (153 assertions); Pint passed; `npm run build` passed | Benny Anderson (`422024008`) |
| 2026-11-25 | Phase 2 merge | Review dan merge PR #2 ke `main` | Benny Anderson (`benanderson2nds@gmail.com`) | PR #2 (`c4847c2`) | PR Phase 2 | Merge commit `c4847c2` | — |
| 2026-11-26 | Phase 3 | Implementasi CRUD pelanggan & kendaraan, validasi Form Request, upload gambar, test, screenshot, dan laporan Phase 3 | Steven (`steven.422024001@civitas.ukrida.ac.id`) | `8336f17`, `be149f3`, `80af0a8`, `25f0193`; merge PR #3 (`9a738fb`) | `app/Http/Controllers/*`, `app/Http/Requests/*`, `app/Services/VehicleImageService.php`, `resources/views/customers/*`, `resources/views/vehicles/*`, `routes/master.php`, `tests/Feature/*`, `docs/*` | 74 passed (293 assertions); Pint passed; build passed; smoke test + screenshot | Benny Anderson (`benanderson2nds@gmail.com`) |
| 2026-11-26 | Phase 4 | Implementasi transaksi rental & CRUD pembayaran (`PaymentService`, `RentalController`, `PaymentController`, 5 Form Request, 8 view, routes, sidebar, JS, test, screenshot, dan laporan Phase 4) | Jason (`jason.422024004@civitas.ukrida.ac.id`) | `448993f` (implementasi), `9a6073d` (test), `ce5ae7d` (dokumentasi); PR #4 | `app/Services/PaymentService.php`, `app/Services/RentalService.php`, `app/Models/Rental.php`, `app/Http/Controllers/RentalController.php`, `app/Http/Controllers/PaymentController.php`, `app/Http/Requests/*`, `resources/views/rentals/*`, `resources/views/payments/*`, `resources/views/layouts/app.blade.php`, `resources/js/app.js`, `routes/rental.php`, `tests/Feature/Rentals/*`, `tests/Feature/Payments/*`, `docs/assets/phase-04/*`, `docs/*` | 96 passed (499 assertions); Pint passed; build passed; MySQL `migrate:fresh --seed`; browser smoke test; 11 screenshot | merged (PR #4, `9bcdf98`); catatan review di repo belum ada |

> Catatan: tidak ada penguncian anggota pada phase tertentu. Commit Phase 3 tercatat di email
> `steven.422024001@civitas.ukrida.ac.id`, sedangkan commit Phase 4 tercatat di email Jason
> (`jason.422024004@civitas.ukrida.ac.id`). Anggota lain bebas mengerjakan task pada phase mana saja
> dengan commit memakai email Git masing-masing.

## 4. Rekap per Anggota (Berdasarkan `git log` Saat Ini)

| Anggota | Email Git | Commit Terhitung (`git log`) | Aktivitas Non-Commit | Status |
|---|---|---:|---|---|
| Benny Anderson (422024008) | `benanderson2nds@gmail.com` | 11 commit (init, Phase 0, Phase 1, merge PR #1 & #2) | Review PR #2 | Merged di `main` |
| Steven Credentia Ivanemaga Zega (422024020) | `steven.422024020@civitas.ukrida.ac.id` | 6 commit (Phase 2) | Review PR #1 | Merged di `main` |
| Steven (422024001) | `steven.422024001@civitas.ukrida.ac.id` | 5 commit (`8336f17`, `be149f3`, `80af0a8`, `9a07789`, `25f0193`) | — | Merged di `main` (PR #3 `9a738fb`) |
| Jason (422024004) | `jason.422024004@civitas.ukrida.ac.id` | 3 commit (`448993f`, `9a6073d`, `ce5ae7d`) | Smoke test MySQL, browser, dan 11 screenshot | merged ke `main` (PR #4) |

Rekap final diperbarui pada Phase 5 setelah semua deliverable selesai dan direkonsiliasi dengan
`git log --all --format="%h %an <%ae> %s"`.

## 5. Aturan Kontribusi

1. Tidak ada pembagian phase secara kaku per anggota; siapa saja boleh berkontribusi pada phase mana pun.
2. Kontribusi kode dan dokumentasi dinilai dari **histori commit pada email Git masing-masing anggota**.
3. Setiap anggota wajib memastikan `git config user.name` dan `git config user.email` memakai identitasnya
   sendiri sebelum melakukan commit.
4. Review PR, uji manual, screenshot, SQL dump, laporan akhir, dan presentasi tetap dicatat sebagai bukti
   pendukung.
5. Finalisasi log dilakukan di Phase 5 melalui rekonsiliasi dengan `git log`, PR, dan reviewer.
6. Jangan memanipulasi histori atau mengklaim commit milik anggota lain.

## 6. Kaitan dengan Penilaian UAS

| Faktor UAS | Bukti di Repository |
|---|---|
| UAS-IND-01 Kontribusi coding | Baris task + author commit + file/PR |
| UAS-IND-02 Commit GitHub | Commit author + riwayat branch + PR |
| UAS-IND-03 Presentasi individu | Bagian slide/demo (dicatat Phase 5) |
| UAS-IND-04 Pemahaman kode | Code walkthrough internal (dicatat Phase 5) |
