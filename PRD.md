# Product Requirements Document (PRD) — RentalGo

| Item | Keterangan |
|---|---|
| Nama Produk | RentalGo |
| Jenis Produk | Aplikasi web sistem informasi rental kendaraan |
| Mata Kuliah | SI2WPO084 — Pemrograman Berorientasi Objek |
| Program Studi | Sistem Informasi |
| Dosen | Cynthia Hayat |
| Semester | 2025/2026 |
| Repository | https://github.com/Jehian/rentalgo-oop |
| Branch dokumentasi | `main` (Phase 0); implementasi Phase 1 di `phase/01-foundation` |
| Versi Dokumen | 1.1 — Phase 0 + catatan Phase 1 |
| Status | Perencanaan selesai; fondasi Phase 1 committed lokal, belum merge |
| Terakhir Diperbarui | 25 November 2026 |

> Dokumen ini adalah sumber kebutuhan produk. Sumber kebenaran utama tetap dokumen UAS resmi di
> `docs/reference/`. Setiap keputusan teknis yang tidak diatur UAS dicatat di `docs/DECISIONS.md`.

---

## 1. Ringkasan Proyek

RentalGo adalah aplikasi web sederhana untuk mengelola operasional usaha rental kendaraan.
Aplikasi menangani data pelanggan, data kendaraan, transaksi rental, pengembalian kendaraan,
dan pencatatan pembayaran. Aplikasi dibangun dengan Laravel dan PHP OOP serta menerapkan konsep
MVC, enkapsulasi, inheritance, polymorphism, abstraction, constructor, static method, namespace,
dan exception handling yang menjadi fokus penilaian UAS.

Proyek ini adalah proyek akademik kelompok beranggotakan empat orang. Fokus utamanya adalah
pemahaman dan penjelasan kode OOP, bukan kompleksitas arsitektur enterprise.

---

## 2. Latar Belakang dan Problem Statement

Usaha rental kendaraan skala kecil umumnya masih mencatat penyewaan secara manual atau
menggunakan spreadsheet. Masalah yang muncul:

- Status kendaraan (tersedia, disewa, perawatan) sulit dipantau secara real-time.
- Risiko kendaraan yang sama disewakan dua kali pada tanggal yang beririsan.
- Riwayat pelanggan, transaksi, dan pembayaran tersebar dan sulit ditelusuri.
- Perhitungan biaya sewa, denda keterlambatan, dan pendapatan bulanan rawan salah hitung.
- Pemilik usaha tidak memiliki ringkasan operasional yang cepat dibaca.

RentalGo menyelesaikan masalah tersebut dalam lingkup aplikasi web sederhana: satu database
terpusat, alur rental yang jelas, validasi input, dan dashboard ringkas.

---

## 3. Tujuan

### 3.1 Tujuan Produk

1. Menyediakan satu tempat untuk mengelola pelanggan, kendaraan, rental, dan pembayaran.
2. Mencegah double booking kendaraan pada rentang tanggal yang beririsan.
3. Menghitung biaya rental dan denda keterlambatan secara otomatis dan konsisten.
4. Menyediakan dashboard ringkas untuk memantau ketersediaan kendaraan dan pendapatan.
5. Menyediakan riwayat transaksi yang dapat ditelusuri.

### 3.2 Tujuan Pembelajaran

1. Menerapkan konsep OOP dasar–menengah pada PHP.
2. Membangun aplikasi web terstruktur dengan MVC sederhana.
3. Menghubungkan aplikasi dengan database MySQL.
4. Mengimplementasikan CRUD, relasi database, autentikasi sederhana, dan validasi form.
5. Menyusun dokumentasi, UML, pengujian, dan presentasi proyek.

---

## 4. Pengguna dan Peran

| Peran | Deskripsi | Hak Akses Utama |
|---|---|---|
| Admin | Pemilik/pengelola usaha | Seluruh fitur, termasuk hapus data master, melihat dashboard, dan mengelola transaksi |
| Staff | Petugas operasional | Login, dashboard, kelola pelanggan dan kendaraan (tanpa hapus permanen), buat rental, proses pengembalian, catat pembayaran |
| Pelanggan | Orang yang menyewa kendaraan | **Bukan pengguna aplikasi.** Data pelanggan dikelola oleh Admin/Staff; tidak ada portal pelanggan |

Catatan:

- Tidak ada registrasi publik. Akun Admin dan Staff disediakan melalui seeder.
- Pembatasan hak akses Admin/Staff adalah keputusan proyek, bukan kebutuhan eksplisit UAS.
- UAS hanya mewajibkan login, logout, dan session user sederhana.

---

## 5. Fitur Inti

1. **Autentikasi sederhana** — login, logout, session, proteksi route.
2. **Dashboard** — ringkasan jumlah kendaraan, pelanggan, rental aktif, pendapatan, dan grafik.
3. **Manajemen pelanggan** — CRUD, pencarian, pagination, sorting, detail riwayat rental.
4. **Manajemen kendaraan** — CRUD, pencarian, filter tipe/status, pagination, sorting, upload gambar.
5. **Transaksi rental** — buat rental, cek ketersediaan, hitung biaya, perpanjangan/perubahan selama aktif, pengembalian, pembatalan, denda keterlambatan.
6. **Pembayaran** — catat pembayaran, ubah status, edit/hapus selama pending, detail.
7. **Validasi dan error handling** — validasi server-side dan pesan error yang ramah.
8. **UI responsif** — layout konsisten dengan Tailwind CSS dan navigasi yang jelas.

---

## 6. Functional Requirements

Prioritas: **M** = wajib untuk UAS, **S** = pendukung, **O** = opsional/bonus.

### 6.1 Autentikasi dan Otorisasi

| ID | Requirement | Prioritas |
|---|---|---|
| FR-AUTH-01 | Pengguna dapat login menggunakan email dan password yang valid. | M |
| FR-AUTH-02 | Pengguna dapat logout dan session diakhiri. | M |
| FR-AUTH-03 | Seluruh halaman operasional dilindungi middleware `auth`; pengguna yang belum login diarahkan ke halaman login. | M |
| FR-AUTH-04 | Sistem membedakan hak akses Admin dan Staff pada aksi tertentu (terutama hapus data). | S |

### 6.2 Dashboard

| ID | Requirement | Prioritas |
|---|---|---|
| FR-DASH-01 | Dashboard menampilkan ringkasan jumlah pelanggan, total kendaraan, kendaraan tersedia, kendaraan disewa, rental aktif, dan pendapatan bulan berjalan. | M |
| FR-DASH-02 | Dashboard menampilkan grafik pendapatan enam bulan terakhir dan/atau komposisi status kendaraan, serta daftar rental terbaru. | M (extra feature) |

### 6.3 Manajemen Pelanggan

| ID | Requirement | Prioritas |
|---|---|---|
| FR-CUST-01 | Menampilkan daftar pelanggan dengan pencarian, pagination, dan sorting. | M |
| FR-CUST-02 | Menambah pelanggan dengan validasi data wajib dan unik. | M |
| FR-CUST-03 | Mengubah data pelanggan. | M |
| FR-CUST-04 | Menghapus pelanggan (soft delete) jika tidak memiliki rental aktif. | M |
| FR-CUST-05 | Menampilkan detail pelanggan beserta riwayat rental. | M |

### 6.4 Manajemen Kendaraan

| ID | Requirement | Prioritas |
|---|---|---|
| FR-VEH-01 | Menampilkan daftar kendaraan dengan pencarian, filter tipe/status, pagination, dan sorting. | M |
| FR-VEH-02 | Menambah kendaraan dengan validasi, termasuk upload gambar (opsional). | M |
| FR-VEH-03 | Mengubah data kendaraan, termasuk mengganti gambar. | M |
| FR-VEH-04 | Menghapus kendaraan (soft delete) jika tidak memiliki rental aktif. | M |
| FR-VEH-05 | Menampilkan detail kendaraan beserta riwayat rental. | M |

### 6.5 Transaksi Rental

| ID | Requirement | Prioritas |
|---|---|---|
| FR-RENT-01 | Membuat rental dengan memilih pelanggan, kendaraan tersedia, tanggal mulai, dan tanggal selesai; sistem memvalidasi ketersediaan dan menghitung biaya. | M |
| FR-RENT-02 | Menampilkan daftar rental dengan pencarian, filter status/tanggal, dan pagination. | M |
| FR-RENT-03 | Menampilkan detail rental: pelanggan, kendaraan, durasi, biaya, denda, status, dan pembayaran. | M |
| FR-RENT-04 | Mengubah catatan/tanggal rental yang masih aktif dengan validasi ulang ketersediaan dan perhitungan biaya. | M |
| FR-RENT-05 | Memproses pengembalian kendaraan, menghitung denda keterlambatan, menandai rental selesai, dan membebaskan kendaraan. | M |
| FR-RENT-06 | Membatalkan rental aktif, membebaskan kendaraan, dan menandai pembayaran pending sebagai dibatalkan. | M |

### 6.6 Pembayaran

| ID | Requirement | Prioritas |
|---|---|---|
| FR-PAY-01 | Mencatat pembayaran untuk rental dengan kode unik, nominal, metode, dan status. | M |
| FR-PAY-02 | Menampilkan daftar pembayaran dengan filter status/metode dan pagination. | M |
| FR-PAY-03 | Mengubah pembayaran yang masih berstatus pending. | M |
| FR-PAY-04 | Menghapus pembayaran yang masih berstatus pending. | M |
| FR-PAY-05 | Menampilkan detail pembayaran. | M |
| FR-PAY-06 | Menandai pembayaran pending menjadi paid atau failed. | M |

### 6.7 Validasi, Error Handling, dan Keamanan

| ID | Requirement | Prioritas |
|---|---|---|
| FR-VAL-01 | Semua form menggunakan validasi server-side dan menampilkan pesan error per field. | M |
| FR-ERR-01 | Kesalahan domain (kendaraan tidak tersedia, tipe tidak dikenal, pelanggaran relasi) ditangani dengan pesan yang jelas. | M |
| FR-SEC-01 | Aplikasi menerapkan CSRF protection, password hashing, mass-assignment protection, dan otorisasi route. | M |
| FR-UI-01 | Layout responsif, konsisten, dan dapat digunakan pada desktop maupun perangkat mobile. | M |
| FR-REP-01 | Ringkasan pendapatan berasal dari total pembayaran berstatus paid. | M |

### 6.8 Fitur Tambahan (UAS)

UAS mewajibkan minimal **dua** fitur tambahan. RentalGo menargetkan enam:

| Fitur | Implementasi | Prioritas |
|---|---|---|
| Search | Pelanggan, kendaraan, rental, pembayaran | M (extra) |
| Pagination | Semua daftar data | M (extra) |
| Sorting | Pelanggan, kendaraan, rental | M (extra) |
| Filter | Tipe/status kendaraan, status/tanggal rental, status/metode pembayaran | M (extra) |
| Upload gambar | Foto kendaraan | M (extra) |
| Dashboard chart | Grafik pendapatan/status kendaraan | M (extra) |

### 6.9 Fitur Opsional (Bonus UAS)

| ID | Fitur | Bonus UAS | Status |
|---|---|---|---|
| FR-BONUS-01 | REST API sederhana (read-only kendaraan/rental) | +3 | Direncanakan, Phase 5 |
| FR-BONUS-02 | Export PDF sederhana (invoice/laporan rental) | +2 | Direncanakan, Phase 5 |
| FR-BONUS-03 | GitHub commit aktif dan rapi | +2 | Direncanakan melalui workflow commit |
| FR-BONUS-04 | Real-time notification | +3 | Tidak direncanakan |
| FR-BONUS-05 | Docker deployment | +3 | **Tidak digunakan** (keputusan pemilik proyek) |

---

## 7. Non-Functional Requirements

| ID | Requirement | Kriteria Verifikasi |
|---|---|---|
| NFR-01 | Responsif pada desktop, tablet, dan mobile. | Manual check + screenshot |
| NFR-02 | Tampilan konsisten dan mudah digunakan. | Review UI + checklist komponen |
| NFR-03 | Keamanan dasar: CSRF, password hash, validasi, otorisasi, escaping Blade. | Code review + feature tests |
| NFR-04 | Maintainable: MVC, service layer terbatas, PSR-12, Laravel Pint. | `vendor/bin/pint --test`, review |
| NFR-05 | Testable: unit test domain dan feature test alur utama. | `php artisan test` |
| NFR-06 | Performa wajar: index database, pagination, eager loading untuk relasi. | Review query + NFR check |
| NFR-07 | Kompatibel dengan PHP >= 8.3, MySQL 8+/MariaDB, browser modern. | Uji di environment tim |
| NFR-08 | Dokumentasi lengkap dan UML tersedia. | Review dokumen |
| NFR-09 | Kode proyek minimal 1000 LOC (di luar vendor/generated). | Perintah hitung LOC di Phase 5 |
| NFR-10 | Orisinal, dipahami seluruh anggota, bukan hasil copy-paste. | Code walkthrough + presentasi |
| NFR-11 | UI berbahasa Indonesia, mata uang IDR, timezone Asia/Jakarta. | Manual check + config review |
| NFR-12 | Kolaborasi Git rapi: branch per fase, commit bermakna, PR untuk implementasi. | Riwayat Git + PR |

---

## 8. Business Rules dan Alur Rental

### 8.1 Business Rules

| ID | Aturan |
|---|---|
| BR-01 | Hanya Admin dan Staff yang dapat login. Tidak ada registrasi publik. |
| BR-02 | Admin memiliki akses penuh. Staff tidak dapat menghapus data master secara permanen. |
| BR-03 | Nomor polisi kendaraan, email pelanggan (jika diisi), NIK, dan nomor SIM harus unik. |
| BR-04 | Kendaraan hanya dapat disewakan jika statusnya `available` dan tidak ada rental `active` dengan tanggal beririsan. |
| BR-05 | Durasi rental dihitung inklusif: `selisih hari + 1`. Rental satu hari menghasilkan 1 hari. |
| BR-06 | Tanggal selesai tidak boleh lebih awal dari tanggal mulai. |
| BR-07 | Biaya mobil = tarif harian × durasi + asuransi 10% dari biaya dasar. Biaya motor = tarif harian × durasi, dengan diskon 10% jika durasi >= 7 hari. |
| BR-08 | Tarif harian disimpan sebagai snapshot di transaksi rental agar perubahan tarif kendaraan tidak mengubah transaksi lama. |
| BR-09 | Denda keterlambatan = 50% tarif harian × jumlah hari terlambat. Hari terlambat dihitung setelah tanggal selesai. |
| BR-10 | Status kendaraan: `available`, `rented`, `maintenance`. Kendaraan maintenance tidak dapat disewakan. |
| BR-11 | Status rental: `active`, `completed`, `cancelled`. Hanya rental active yang dapat diubah, dikembalikan, atau dibatalkan. |
| BR-12 | Status pembayaran: `pending`, `paid`, `failed`, `cancelled`. Pembayaran hanya dapat diedit/dihapus saat `pending`. |
| BR-13 | Total pembayaran paid tidak boleh melebihi total tagihan (total rental + denda). |
| BR-14 | Pelanggan/kendaraan dengan rental aktif tidak dapat dihapus. Data master menggunakan soft delete. |
| BR-15 | Transaksi rental tidak dihapus permanen; pembatalan dilakukan melalui status `cancelled` untuk menjaga riwayat. |
| BR-16 | Pendapatan dashboard dihitung dari pembayaran berstatus `paid`. |
| BR-17 | Semua nominal menggunakan Rupiah (IDR) dan dua digit desimal di database. |
| BR-18 | Waktu sistem menggunakan timezone Asia/Jakarta. |

### 8.2 Alur Rental (Workflow)

1. Staff membuka form rental dan memilih pelanggan.
2. Staff memilih kendaraan berstatus `available` dan rentang tanggal.
3. Sistem memvalidasi tanggal dan memastikan tidak ada rental aktif yang beririsan.
4. Sistem menghitung durasi dan biaya melalui aturan pricing kendaraan.
5. Sistem membuat transaksi rental dan mengubah status kendaraan menjadi `rented` dalam satu database transaction.
6. Staff mencatat pembayaran (pending atau paid).
7. Saat kendaraan dikembalikan, Staff memproses pengembalian:
   - Sistem menghitung denda jika melewati tanggal selesai.
   - Status rental menjadi `completed`.
   - Status kendaraan kembali `available`.
8. Jika rental dibatalkan sebelum berjalan, status rental menjadi `cancelled`, kendaraan kembali `available`, dan pembayaran pending menjadi `cancelled`.
9. Admin/Staff dapat melihat riwayat dan detail transaksi.

### 8.3 Aturan CRUD untuk Pemenuhan UAS

UAS mewajibkan minimal tiga entitas dengan tambah, edit, hapus, dan detail. RentalGo memenuhi:

| Entitas | Create | Edit | Delete | Detail | Catatan |
|---|---|---|---|---|---|
| Pelanggan | Ya | Ya | Soft delete | Ya | Tidak boleh ada rental aktif |
| Kendaraan | Ya | Ya | Soft delete | Ya | Tidak boleh ada rental aktif |
| Pembayaran | Ya | Ya (pending) | Ya (pending) | Ya | Paid/failed/cancelled terkunci |
| Rental | Ya | Ya (active) | Tidak (gunakan cancel) | Ya | Riwayat transaksi dipertahankan |

---

## 9. Scope dan Batasan

### 9.1 In Scope

- Aplikasi web Laravel dengan Blade + Tailwind CSS.
- Autentikasi sederhana Admin/Staff.
- Dashboard ringkasan dan grafik.
- CRUD pelanggan, kendaraan, dan pembayaran; lifecycle rental.
- Pencarian, pagination, sorting, filter, upload gambar kendaraan.
- Validasi form dan error handling.
- Database MySQL dengan minimal lima tabel dan relasi.
- Unit/feature test untuk alur utama.
- Dokumentasi, UML, SQL dump, dan presentasi.
- Bonus yang direncanakan: REST API sederhana, export PDF, commit history rapi.

### 9.2 Out of Scope

- Customer self-service portal atau registrasi publik.
- Payment gateway online, integrasi bank, atau pembayaran otomatis.
- Email/SMS nyata (simulasi email tidak dipilih).
- Multi-cabang, multi-gudang, atau multi-tenant.
- Manajemen user lengkap (CRUD user) — akun disediakan via seeder.
- GPS tracking, telematics, atau notifikasi real-time.
- Aplikasi mobile native.
- Akuntansi lengkap, pajak, laporan keuangan formal.
- Audit trail kompleks di luar timestamp dan status.
- Docker dan containerization (keputusan pemilik proyek).
- Fitur enterprise seperti message queue, microservice, CQRS, event sourcing.

---

## 10. Technology Stack

| Komponen | Teknologi | Keterangan |
|---|---|---|
| Bahasa | PHP 8.4 (minimum 8.3) | Runtime saat ini 8.4.27 |
| Framework | Laravel 13.44.1 | Fitur dasar, sesuai izin UAS |
| Database utama | MySQL 8+/9.x (MariaDB 11.x kompatibel) | UAS menyebut MySQL; SQLite hanya untuk test/lokal |
| Database test | SQLite in-memory | Konfigurasi PHPUnit |
| Templating | Blade | Layout dan komponen reusable |
| Styling | Tailwind CSS 4 + Vite 8 | Sudah terpasang di skeleton |
| Testing | PHPUnit 12 | Unit dan feature test |
| Code style | Laravel Pint | PSR-12 |
| Version control | Git + GitHub | Branch per fase, PR untuk implementasi |
| Grafik | Chart.js (Phase 2) | Dependency ditambahkan saat dibutuhkan |
| PDF | Dompdf (Phase 5) | Bonus yang direncanakan |

Tidak digunakan: Bootstrap, framework JavaScript SPA, Breeze/Jetstream, Docker, Pest.

---

## 11. Expected Deliverables

| Deliverable UAS | Implementasi RentalGo | Target |
|---|---|---|
| Source code lengkap | Repository GitHub RentalGo | Phase 1–5 |
| Database SQL | `database/schema/rentalgo.sql` (dump MySQL) + migrations | Phase 5 |
| README | `README.md` proyek | Phase 0 |
| Dokumentasi proyek | `PRD.md`, `docs/ARCHITECTURE.md`, `docs/DATABASE_SCHEMA.md`, laporan akhir, screenshot, pengujian, kesimpulan | Phase 0–5 |
| UML | Use case & class diagram di `docs/ARCHITECTURE.md`; ERD di `docs/DATABASE_SCHEMA.md` | Phase 0 |
| Presentasi | Slide + demo aplikasi | Phase 5 |
| Laporan fase | `docs/progress/phase-XX-*.md` | Setiap fase |
| Kontribusi individu | `docs/CONTRIBUTION_LOG.md` + riwayat commit | Berkelanjutan |

---

## 12. Acceptance Criteria

### 12.1 Kriteria Proyek (UAS)

- [ ] Minimal 8 class OOP, dengan enkapsulasi, minimal 2 inheritance, polymorphism/overriding, abstract class/interface, constructor, minimal 1 static method, namespace, dan try-catch.
- [ ] Minimal 1000 LOC kode proyek (di luar vendor dan generated files).
- [ ] Minimal 4 tabel database dengan relasi; RentalGo menargetkan 5 tabel.
- [ ] Login, logout, dan session berjalan.
- [ ] Minimal 3 entitas dengan CRUD lengkap (create, edit, delete, detail).
- [ ] Minimal 2 fitur tambahan; RentalGo menargetkan 6.
- [ ] Form validation berjalan pada semua form utama.
- [ ] Layout responsif dan konsisten.
- [ ] Use case diagram, class diagram, dan ERD tersedia.
- [ ] Dokumentasi lengkap, screenshot, testing, dan kesimpulan.
- [ ] Presentasi dan demo aplikasi berjalan.
- [ ] Aplikasi berjalan pada MySQL saat demo.
- [ ] Tidak ada plagiarisme; seluruh anggota memahami kode.

### 12.2 Kriteria per Modul

| Modul | Kriteria Diterima |
|---|---|
| Auth | Login/logout/session; route terlindungi; pesan error login jelas |
| Dashboard | Angka ringkasan benar; grafik tampil; data sesuai database |
| Pelanggan | CRUD + validasi + search/pagination/sort + detail riwayat |
| Kendaraan | CRUD + validasi + filter + upload gambar + detail riwayat |
| Rental | Availability check; biaya benar; return + denda; cancel; detail |
| Pembayaran | Catat/edit/hapus pending; status paid/failed; total tidak overpay |
| UI/UX | Responsif; navigasi jelas; validasi tampil dekat field |
| Testing | Unit test domain lulus; feature test alur utama lulus |
| Dokumentasi | Semua requirement UAS terpetakan dan terverifikasi |

---

## 13. Assumptions dan Open Questions

### 13.1 Assumptions

1. Tim beranggotakan empat orang; satu anggota mengerjakan Phase 0 dan Phase 1.
2. Anggota lain dapat menggunakan AI agent dan mengikuti `docs/AI_HANDOFF.md`.
3. Environment pengembangan menggunakan WAMP/Laragon/XAMPP atau MySQL lokal.
4. UAS memperbolehkan Laravel dengan fitur dasar dan Tailwind CSS.
5. Diagram Mermaid di Markdown diterima sebagai UML; gambar PNG dapat diekspor saat presentasi.
6. Bahasa dokumentasi dan UI adalah Indonesia; identifier kode dan commit message menggunakan bahasa Inggris.
7. Tidak ada kebutuhan pembayaran online; pembayaran dicatat manual.
8. Data awal (admin, staff, pelanggan, kendaraan) disediakan melalui seeder.

### 13.2 Open Questions (perlu konfirmasi manusia)

| No | Pertanyaan | Default Sementara |
|---|---|---|
| OQ-01 | Nama dan NIM anggota tim? | Selesai: lihat `README.md` dan `docs/PHASE_PLAN.md` |
| OQ-02 | Kredensial MySQL lokal (user/password/port)? | Selesai: MySQL 9.1, `root`, port 3306, database `rentalgo` |
| OQ-03 | Bonus yang ingin dikerjakan? | Selesai: REST API + PDF + commit rapi (direncanakan) |
| OQ-04 | Apakah dosen menerima UML berbentuk Mermaid? | Ya; PNG disiapkan jika diminta |
| OQ-05 | Tanggal presentasi dan format slide? | Ditentukan Phase 5 |
| OQ-06 | Apakah akun customer perlu login? | Tidak |

---

## 14. Glossary

| Istilah | Arti |
|---|---|
| Rental | Transaksi penyewaan kendaraan |
| Pelanggan | Orang yang menyewa kendaraan (data master) |
| Kendaraan | Mobil atau motor yang disewakan |
| Tersedia | Status kendaraan yang siap disewakan |
| Disewa | Status kendaraan yang sedang dalam transaksi active |
| Maintenance | Status kendaraan yang sedang diperbaiki/tidak disewakan |
| Denda | Biaya tambahan karena pengembalian melewati tanggal selesai |
| Snapshot tarif | Tarif harian yang disalin ke transaksi saat rental dibuat |
| Soft delete | Menandai data terhapus tanpa menghapus baris database |
| MVC | Model–View–Controller |
| LOC | Line of Code |

---

## 15. Referensi

- Dokumen UAS resmi: `docs/reference/UAS_Cynthia Hayat_SI2WPO084 – Pemrograman Berorientasi Objek (1).doc`
- Ekstraksi requirement: `docs/UAS_REQUIREMENTS.md`
- Traceability: `docs/UAS_TRACEABILITY.md`
- Arsitektur: `docs/ARCHITECTURE.md`
- Skema database: `docs/DATABASE_SCHEMA.md`
- Keputusan teknis: `docs/DECISIONS.md`
- Rencana fase: `docs/PHASE_PLAN.md`
