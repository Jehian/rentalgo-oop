# Architecture Decision Records (ADR) — RentalGo

Dokumen ini mencatat keputusan teknis penting beserta alasannya. Keputusan yang belum disetujui
manusia ditandai `Proposed`; keputusan yang sudah menjadi baseline proyek ditandai `Accepted`.

## Ringkasan Keputusan

| ID | Keputusan | Status |
|---|---|---|
| ADR-001 | Menggunakan Laravel 13 | Accepted |
| ADR-002 | MySQL sebagai database utama, SQLite untuk test | Accepted |
| ADR-003 | Tailwind CSS 4 sebagai framework UI | Accepted |
| ADR-004 | Custom authentication, tanpa Breeze/Jetstream | Accepted |
| ADR-005 | Peran Admin dan Staff; pelanggan bukan user login | Accepted |
| ADR-006 | Satu tabel `vehicles` + domain subclass | Accepted |
| ADR-007 | Lima tabel bisnis | Accepted |
| ADR-008 | Service layer terbatas, tanpa repository pattern | Accepted |
| ADR-009 | Aturan harga kendaraan melalui polymorphism | Accepted |
| ADR-010 | Durasi inklusif dan denda 50% per hari | Accepted |
| ADR-011 | Pembayaran manual 1:N per rental | Accepted |
| ADR-012 | Enam fitur tambahan | Accepted |
| ADR-013 | Soft delete data master; rental tidak dihapus | Accepted |
| ADR-014 | Dokumentasi Phase 0 langsung di `main` | Accepted (keputusan pemilik proyek) |
| ADR-015 | Implementasi memakai branch + PR | Accepted |
| ADR-016 | UML memakai Mermaid di Markdown | Accepted |
| ADR-017 | Docker tidak digunakan | Accepted (keputusan pemilik proyek) |
| ADR-018 | Dokumentasi/UI Indonesia; kode Inggris | Accepted |
| ADR-019 | PHPUnit, bukan Pest | Accepted |
| ADR-020 | Conventional Commits dan merge commit | Accepted |
| ADR-021 | Bonus REST API/PDF/commit direncanakan | Accepted (rencana) |
| ADR-022 | Gambar kendaraan di public disk | Accepted |
| ADR-023 | Chart.js ditambahkan pada Phase 2 | Accepted |
| ADR-024 | Kolom role memakai migration terpisah | Accepted |
| ADR-025 | Nama/NIM anggota sudah diisi | Accepted |

---

## ADR-001 — Laravel 13

**Konteks:** UAS mengizinkan native PHP OOP, CodeIgniter, atau Laravel fitur dasar. Skeleton Laravel
13.44.1 sudah terpasang.

**Keputusan:** Gunakan Laravel 13 dengan fitur dasar (routing, Eloquent, Blade, middleware,
validation).

**Alternatif:** Native PHP OOP (lebih banyak kode infrastruktur), CodeIgniter (tidak terpasang).

**Konsekuensi:** Pengembangan lebih cepat; dosen perlu memahami bahwa konsep OOP tetap ditunjukkan
melalui class domain, bukan hanya fitur framework.

**Status:** Accepted.

## ADR-002 — MySQL Utama, SQLite untuk Test

**Konteks:** UAS menyebut koneksi database MySQL. Skeleton memakai SQLite. MySQL 9.1 dan MariaDB
11.5 tersedia melalui WAMP; MySQL 9.1 dipakai pada port 3306 dengan database `rentalgo`.

**Keputusan:** MySQL menjadi database utama untuk pengembangan integrasi dan demo. SQLite in-memory
dipakai untuk automated test agar cepat dan portabel.

**Alternatif:** SQLite untuk semuanya (risiko tidak memenuhi ekspektasi UAS); MySQL untuk test juga
(lebih lambat, konfigurasi tambahan).

**Konsekuensi:** Schema harus kompatibel dengan keduanya; hindari SQL khusus MySQL di aplikasi.
Phase 1 perlu membuat database `rentalgo` dan mengatur `.env` lokal.

**Status:** Accepted; kredensial lokal sudah diisi di `.env`.

## ADR-003 — Tailwind CSS 4

**Konteks:** UAS mengizinkan Bootstrap, Tailwind, atau admin template. Tailwind 4 dan Vite 8 sudah
terpasang di skeleton.

**Keputusan:** Gunakan Tailwind CSS 4 tanpa menambah framework UI lain.

**Alternatif:** Bootstrap (familiar, tetapi menambah dependency dan styling campuran).

**Konsekuensi:** Layout dan komponen dibuat manual; konsistensi dijaga melalui komponen Blade.

**Status:** Accepted.

## ADR-004 — Custom Authentication

**Konteks:** UAS hanya mewajibkan login, logout, dan session sederhana. Breeze menghasilkan banyak
file yang dapat mengaburkan pemahaman konsep.

**Keputusan:** Buat `AuthController` sederhana dengan `Auth::attempt()`, session regenerate,
invalidate, dan middleware `auth`.

**Alternatif:** Laravel Breeze (cepat, tetapi banyak boilerplate); Jetstream (terlalu besar).

**Konsekuensi:** Lebih sedikit file dan lebih mudah dijelaskan; perlu menulis feature test sendiri.

**Status:** Accepted.

## ADR-005 — Peran Admin dan Staff

**Konteks:** UAS tidak mewajibkan role, tetapi aplikasi rental realistis membutuhkan pembedaan hak
akses. Pelanggan tidak perlu login.

**Keputusan:** Dua role: `admin` (akses penuh) dan `staff` (operasional, tidak boleh hapus master).
Tidak ada registrasi publik atau portal pelanggan.

**Alternatif:** Satu role untuk semua user (lebih sederhana, tetapi dashboard dan keamanan kurang
menarik); portal pelanggan (menambah scope besar).

**Konsekuensi:** Perlu middleware `EnsureUserIsAdmin` pada aksi hapus. Akun dibuat via seeder.

**Status:** Accepted.

## ADR-006 — Satu Tabel Vehicles + Domain Subclass

**Konteks:** Ingin menunjukkan inheritance dan polymorphism tanpa mempersulit CRUD. Alternatifnya
tabel terpisah `cars`/`motorcycles` dengan relasi polimorfik, atau single-table inheritance Eloquent.

**Keputusan:** Satu tabel `vehicles` dengan kolom `type`; class domain `Car` dan `Motorcycle`
merupakan subclass dari abstract `Vehicle`. Eloquent model tetap `App\Models\Vehicle`.

**Alternatif:** Tabel terpisah + morphTo (kompleks untuk mahasiswa); STI Eloquent dengan
`newFromBuilder` (rapuh dan sulit dijelaskan).

**Konsekuensi:** Domain dan persistence terpisah; perlu `VehicleFactory` untuk memetakan model ke
domain object. Kolom khusus mobil/motor nullable.

**Status:** Accepted.

## ADR-007 — Lima Tabel Bisnis

**Konteks:** UAS mewajibkan minimal 4 tabel dengan relasi.

**Keputusan:** Gunakan `users`, `customers`, `vehicles`, `rentals`, `payments`.

**Alternatif:** Empat tabel dengan menggabungkan pembayaran ke rental (mengurangi kemampuan riwayat
pembayaran); menambah tabel kategori (tidak perlu untuk scope).

**Konsekuensi:** Relasi 1:N jelas; dashboard dapat menghitung pendapatan dari pembayaran.

**Status:** Accepted.

## ADR-008 — Service Layer Terbatas

**Konteks:** Bisnis rental terlalu kompleks untuk ditaruh di controller, tetapi repository pattern
generik akan menambah abstraksi yang tidak perlu.

**Keputusan:** Gunakan service hanya untuk use case yang benar-benar mengoordinasikan beberapa
model/aturan: `RentalService`, `PaymentService`, `DashboardService`, `VehicleImageService`.
CRUD sederhana tetap di controller + Form Request + Eloquent.

**Alternatif:** Repository pattern untuk semua model (overengineering); semua logika di controller
(controller gemuk, sulit diuji).

**Konsekuensi:** Controller tetap tipis; service dapat diuji; tidak ada lapisan abstraksi berlebih.

**Status:** Accepted.

## ADR-009 — Pricing Melalui Polymorphism

**Konteks:** Mobil dan motor memiliki aturan harga berbeda. Ini kesempatan alami untuk method
overriding.

**Keputusan:** `Vehicle::calculateRentalCost()` abstract; `Car` menambahkan asuransi 10%; `Motorcycle`
memberi diskon 10% untuk sewa >= 7 hari. Nilai persentase disimpan di `config/rental.php`.

**Alternatif:** Satu method dengan `if type == car` (tidak menunjukkan polymorphism); strategy
pattern terpisah (lebih banyak class, belum perlu).

**Konsekuensi:** Perilaku polimorfik mudah diuji; aturan baru dapat ditambah sebagai subclass.

**Status:** Accepted.

## ADR-010 — Durasi Inklusif dan Denda

**Konteks:** Perlu definisi durasi yang konsisten untuk perhitungan dan tampilan.

**Keputusan:** Durasi = selisih hari + 1. Denda = 50% tarif harian per hari keterlambatan setelah
`end_date`.

**Alternatif:** Durasi eksklusif (lebih membingungkan untuk rental harian); denda 100% (lebih berat,
tidak perlu untuk demo).

**Konsekuensi:** Aturan harus dijelaskan di UI dan dokumentasi; unit test wajib mencakup batas hari.

**Status:** Accepted.

## ADR-011 — Pembayaran Manual 1:N

**Konteks:** Tidak ada payment gateway. Pembayaran dapat dicicil atau dicatat bertahap.

**Keputusan:** Satu rental memiliki banyak pembayaran. Status pembayaran `pending`, `paid`, `failed`,
`cancelled`. Pembayaran paid tidak dapat diubah/dihapus.

**Alternatif:** Satu pembayaran per rental (lebih sederhana, tetapi tidak mendukung cicilan dan
kurang fleksibel).

**Konsekuensi:** Perlu validasi total pembayaran agar tidak melebihi tagihan; riwayat pembayaran
lebih kaya.

**Status:** Accepted.

## ADR-012 — Enam Fitur Tambahan

**Konteks:** UAS mewajibkan minimal dua fitur tambahan.

**Keputusan:** Search, pagination, sorting, filter, upload gambar kendaraan, dan dashboard chart.

**Alternatif:** Memilih dua saja (risiko kehilangan poin fungsionalitas); export PDF (dipindah ke
bonus).

**Konsekuensi:** Beban kerja lebih besar, tetapi tersebar di Phase 2–4 dan memperkuat nilai
fungsionalitas/UI.

**Status:** Accepted.

## ADR-013 — Soft Delete dan Larangan Hapus Rental

**Konteks:** Riwayat transaksi harus tetap ada untuk laporan dan audit sederhana.

**Keputusan:** Pelanggan dan kendaraan memakai soft delete; rental tidak dihapus (gunakan cancel);
pembayaran hanya boleh dihapus saat pending.

**Alternatif:** Hard delete semuanya (risiko kehilangan riwayat dan FK error).

**Konsekuensi:** UAS "hapus data" tetap terpenuhi melalui soft delete dan delete payment pending;
perlu menjelaskan definisi delete di laporan.

**Status:** Accepted.

## ADR-014 — Dokumentasi Phase 0 di Main

**Konteks:** Workflow awal mewajibkan branch per fase. Pemilik proyek meminta penyederhanaan karena
Phase 0 hanya berisi dokumen.

**Keputusan:** Dokumentasi Phase 0 dikerjakan langsung di `main` tanpa PR. Ini adalah pengecualian
khusus dokumentasi.

**Alternatif:** Branch `phase/00-planning` + PR (lebih ketat, tetapi dianggap berlebihan).

**Konsekuensi:** Riwayat `main` berisi commit dokumentasi; implementasi tetap wajib branch + PR.
Aturan ini dicatat di `AGENTS.md` dan `docs/PHASE_PLAN.md`.

**Status:** Accepted (keputusan pemilik proyek).

## ADR-015 — Branch + PR untuk Implementasi

**Konteks:** Tiga anggota lain akan bekerja paralel dan dinilai dari kontribusi/commit.

**Keputusan:** Setiap fase implementasi memakai branch `phase/XX-nama` dan PR ke `main`. PR minimal
direview satu anggota lain, test lulus, dan dokumen status diperbarui.

**Alternatif:** Semua langsung ke main (risiko konflik dan sulit menilai kontribusi).

**Konsekuensi:** Perlu disiplin Git; merge commit mempertahankan histori individual.

**Status:** Accepted.

## ADR-016 — UML dengan Mermaid

**Konteks:** Diagram perlu mudah diubah dan dirender di GitHub.

**Keputusan:** Use case, class diagram, ERD, dan sequence diagram ditulis dengan Mermaid di Markdown.
PNG dapat diekspor untuk slide jika diperlukan.

**Alternatif:** Draw.io/PlantUML/gambar manual (lebih banyak file biner, sulit di-diff).

**Konsekuensi:** Perlu memastikan sintaks Mermaid valid; diagram dapat dilihat langsung di GitHub.

**Status:** Accepted.

## ADR-017 — Docker Tidak Digunakan

**Konteks:** Docker adalah bonus opsional +3, tetapi menambah kompleksitas environment dan tidak
diminta dosen.

**Keputusan:** Tidak membuat Dockerfile, docker-compose, atau dokumentasi container.

**Alternatif:** Docker untuk deployment bonus.

**Konsekuensi:** Kehilangan maksimal 3 poin bonus; fokus pada fitur wajib dan kualitas OOP.

**Status:** Accepted (keputusan pemilik proyek).

## ADR-018 — Bahasa Dokumentasi dan Kode

**Konteks:** UAS, dosen, dan anggota tim menggunakan bahasa Indonesia; identifier kode lebih lazim
dalam bahasa Inggris.

**Keputusan:** Dokumentasi dan label UI berbahasa Indonesia; nama class, method, variabel, route, dan
commit message berbahasa Inggris.

**Alternatif:** Semua bahasa Indonesia (identifier tidak idiomatis); semua bahasa Inggris (dokumen
kurang sesuai konteks UAS).

**Konsekuensi:** Konsisten dan mudah dipahami; perlu menjaga istilah teknis tetap jelas.

**Status:** Accepted.

## ADR-019 — PHPUnit

**Konteks:** Skeleton sudah memakai PHPUnit 12; Pest memerlukan dependency tambahan.

**Keputusan:** Gunakan PHPUnit untuk unit dan feature test.

**Alternatif:** Pest (sintaks lebih ringkas, tetapi dependency dan gaya baru).

**Konsekuensi:** Sesuai default Laravel; test dapat dijalankan dengan `php artisan test`.

**Status:** Accepted.

## ADR-020 — Conventional Commits dan Merge Commit

**Konteks:** UAS memberi bonus untuk commit GitHub yang aktif dan rapi; kontribusi individu dinilai
dari commit.

**Keputusan:** Commit memakai Conventional Commits; PR digabung dengan merge commit (bukan squash)
agar commit tiap anggota tetap terlihat.

**Alternatif:** Squash merge (main lebih rapi, tetapi histori individu hilang); commit bebas (sulit
dibaca).

**Konsekuensi:** `main` memiliki merge commit; setiap anggota harus menjaga commit tetap bermakna.

**Status:** Accepted.

## ADR-021 — Bonus REST API, PDF, dan Commit

**Konteks:** Bonus maksimal 10 poin; Docker dan real-time notification tidak direncanakan. Pemilik
proyek menyetujui REST API dan export PDF dikerjakan.

**Keputusan:** Kerjakan REST API read-only (+3), export PDF invoice/laporan (+2), dan commit rapi
(+2) pada Phase 5 setelah fitur wajib stabil.

**Alternatif:** Mengejar semua bonus (risiko fitur wajib terbengkalai).

**Konsekuensi:** Bonus direncanakan, tetapi prioritas tetap fitur wajib dan presentasi. Jika waktu
tidak cukup, status dikembalikan menjadi `Optional` dengan catatan jujur.

**Status:** Accepted (rencana).

## ADR-022 — Penyimpanan Gambar Kendaraan

**Konteks:** Fitur upload gambar membutuhkan lokasi penyimpanan yang dapat diakses browser.

**Keputusan:** Simpan di `storage/app/public/vehicles` dan buat symlink `public/storage`. Path
disimpan di `vehicles.image_path`.

**Alternatif:** Simpan di public langsung (tidak disarankan); base64 di database (boros).

**Konsekuensi:** Perlu `php artisan storage:link`; hapus gambar lama saat replace/delete.

**Status:** Accepted.

## ADR-023 — Chart.js pada Phase 2

**Konteks:** Dashboard membutuhkan grafik sederhana.

**Keputusan:** Tambahkan Chart.js melalui npm pada Phase 2; data disiapkan `DashboardService` dan
dikirim sebagai JSON ke view.

**Alternatif:** Grafik CSS manual (terbatas); library lain (tidak perlu).

**Konsekuensi:** Menambah dependency frontend; tidak dipasang pada Phase 0.

**Status:** Accepted (rencana).

## ADR-024 — Migration Role Terpisah

**Konteks:** Tabel users bawaan Laravel sudah ada. Menambah role dapat dilakukan dengan mengubah
migration awal atau migration baru.

**Keputusan:** Tambahkan migration `add_role_to_users_table` agar histori skeleton tetap utuh dan
aman untuk anggota yang sudah menjalankan migrasi.

**Alternatif:** Ubah migration awal (lebih bersih untuk proyek baru, tetapi mengubah file skeleton).

**Konsekuensi:** Ada satu migration tambahan; `migrate:fresh` tetap menghasilkan schema yang sama.

**Status:** Accepted.

## ADR-025 — Identitas Anggota Tim

**Konteks:** Nama/NIM anggota sudah diberikan oleh pemilik proyek.

**Keputusan:** Gunakan nama/NIM asli pada `README.md`, `docs/PHASE_PLAN.md`, dan
`docs/CONTRIBUTION_LOG.md`:

- Benny Anderson / 422024008
- Steven Credentia Ivanemaga Zega / 422024020
- Jason / 422024004
- Steven / 422024001

**Alternatif:** Tetap memakai label `Anggota 1`–`Anggota 4` (menyulitkan penilaian individu).

**Konsekuensi:** Dokumen dan laporan harus konsisten dengan identitas ini.

**Status:** Accepted.
