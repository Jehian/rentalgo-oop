# Traceability Matrix UAS → RentalGo

Dokumen ini memetakan setiap requirement eksplisit UAS ke requirement produk, lokasi implementasi
yang direncanakan, fase pengerjaan, dan metode verifikasi.

> **Penting:** Status `Planned` berarti belum diimplementasikan. Jangan mengubah status menjadi
> `Verified` tanpa bukti implementasi dan pengujian. Dokumen ini diperbarui pada setiap phase report.

## 1. Legenda Status

| Status | Arti |
|---|---|
| `Documented` | Sudah dijelaskan di dokumen Phase 0; belum tentu diimplementasikan |
| `Planned` | Direncanakan pada fase yang tercantum; belum dikerjakan |
| `In Progress` | Sedang dikerjakan pada branch fase |
| `Implemented` | Kode sudah ada dan lolos test lokal; belum diverifikasi reviewer |
| `Verified` | Sudah diverifikasi reviewer dengan bukti (test/manual/SQL/screenshot) |
| `Optional` | Hanya dikerjakan jika waktu memungkinkan |
| `Not Pursued` | Secara sadar tidak dikerjakan |

## 2. Requirement Umum dan OOP

| ID | Requirement | PRD | Lokasi Implementasi (Rencana) | Fase | Verifikasi | Status |
|---|---|---|---|---|---|---|
| UAS-GEN-01 | Aplikasi web PHP OOP untuk masalah nyata | §1, §3 | Seluruh aplikasi | 1–5 | Demo + code review | Planned |
| UAS-GEN-02 | Tema Sistem Rental Kendaraan | §1 | Modul pelanggan, kendaraan, rental, pembayaran | 1–4 | Review fitur | Planned |
| UAS-GEN-03 | Fokus OOP, modular, CRUD, auth, MVC | §3.2, §5 | `app/Domain`, `app/Models`, `app/Http`, `app/Services` | 1–4 | Code review + demo | Planned |
| UAS-GEN-04 | Framework yang diizinkan (Laravel fitur dasar) | §10 | Laravel 13, Blade, Eloquent | 1–5 | Review stack | Planned |
| UAS-OOP-01 | Minimal 8 class | §12.1 | `app/Domain/Vehicles/*`, `app/Models/*`, `app/Services/*`, `app/Support/*`, `app/Exceptions/*` | 1–4 | Hitung class + class diagram | Planned |
| UAS-OOP-02 | Encapsulation | §12.1 | Private/protected property + getter di domain; `#[Fillable]`, `#[Hidden]` di model; dependency private di service | 1 | Code review + unit test | Planned |
| UAS-OOP-03 | Minimal 2 inheritance | §12.1 | `Car extends Vehicle`, `Motorcycle extends Vehicle` | 1 | Unit test + class diagram | Planned |
| UAS-OOP-04 | Polymorphism / method overriding | §12.1 | `calculateRentalCost()` di-override; `RentalService` menerima tipe abstrak | 1 | Unit test polimorfik | Planned |
| UAS-OOP-05 | Abstraction (interface/abstract class) | §12.1 | `App\Contracts\Rentable`; `App\Domain\Vehicles\Vehicle` (abstract) | 1 | Code review + class diagram | Planned |
| UAS-OOP-06 | Constructor | §12.1 | Constructor domain class + constructor injection service/controller | 1–2 | Code review | Planned |
| UAS-OOP-07 | Minimal 1 static method | §12.1 | `VehicleFactory::fromModel()`, `DocumentCodeGenerator::rentalCode()`/`paymentCode()` | 1 | Unit test | Planned |
| UAS-OOP-08 | Namespace (disarankan) | §12.1 | PSR-4 `App\...`, `Database\...` | 1 | `composer dump-autoload` + review | Planned |
| UAS-OOP-09 | Exception handling minimal try-catch | §12.1 | `VehicleNotAvailableException`, `UnsupportedVehicleTypeException`; try-catch di factory/service | 1 | Unit test + review | Planned |
| UAS-ARC-01 | MVC sederhana | §12.1 | `routes/`, `app/Http/Controllers`, `app/Models`, `resources/views` | 2–4 | Review struktur + demo | Planned |
| UAS-ARC-02 | Modular programming | §12.1 | Route per modul, folder controller/request/view per modul | 2–4 | Review struktur | Planned |
| UAS-ARC-03 | Reusable component | §12.1 | Blade components, Form Requests, service, enum label | 2–4 | Review komponen | Planned |

## 3. Requirement Fitur

| ID | Requirement | PRD | Lokasi Implementasi (Rencana) | Fase | Verifikasi | Status |
|---|---|---|---|---|---|---|
| UAS-FEA-01 | Login | FR-AUTH-01 | `AuthController@store`, `routes/auth.php`, `views/auth/login` | 2 | Feature test login | Planned |
| UAS-FEA-02 | Logout | FR-AUTH-02 | `AuthController@destroy`, route POST logout | 2 | Feature test logout | Planned |
| UAS-FEA-03 | Session user | FR-AUTH-03 | Middleware `auth`, session regenerate/invalidate | 2 | Feature test proteksi route | Planned |
| UAS-FEA-04 | Dashboard ringkas/statistik | FR-DASH-01, FR-DASH-02 | `DashboardController`, `DashboardService`, `views/dashboard` | 2 | Feature test + manual | Planned |
| UAS-FEA-05 | CRUD: tambah data (min. 3 entitas) | FR-CUST-02, FR-VEH-02, FR-PAY-01 | Controller + Form Request + view untuk pelanggan, kendaraan, pembayaran | 3–4 | Feature test create | Planned |
| UAS-FEA-06 | CRUD: edit data (min. 3 entitas) | FR-CUST-03, FR-VEH-03, FR-PAY-03 | Controller + Form Request + view | 3–4 | Feature test update | Planned |
| UAS-FEA-07 | CRUD: hapus data (min. 3 entitas) | FR-CUST-04, FR-VEH-04, FR-PAY-04 | Soft delete pelanggan/kendaraan; delete payment pending | 3–4 | Feature test delete + aturan | Planned |
| UAS-FEA-08 | CRUD: detail data (min. 3 entitas) | FR-CUST-05, FR-VEH-05, FR-PAY-05 | Controller show + view detail | 3–4 | Feature test show | Planned |
| UAS-FEA-09 | Minimal 2 fitur tambahan | §6.8 | Search, pagination, sorting, filter, upload gambar, chart | 2–4 | Manual checklist + test | Planned |
| UAS-FEA-10 | Daftar fitur tambahan yang dipilih | §6.8 | Lihat pemetaan fitur di PRD §6.8 | 2–4 | Review fitur | Planned |
| UAS-UI-01 | Bootstrap/Tailwind/admin template | §10 | Tailwind CSS 4 + Blade layout | 2–4 | Review UI | Planned |
| UAS-UI-02 | Responsive layout | NFR-01 | `resources/views/layouts`, Tailwind breakpoints | 2–4 | Manual desktop/tablet/mobile | Planned |
| UAS-UI-03 | Form validation sederhana | FR-VAL-01, NFR-03 | Form Request + error Blade | 2–4 | Feature test validasi | Planned |
| UAS-UML-01 | Use case diagram | §11, ARCHITECTURE §7 | `docs/ARCHITECTURE.md` | 0 | Review dokumen | Documented |
| UAS-UML-02 | Class diagram | §11, ARCHITECTURE §6 | `docs/ARCHITECTURE.md` | 0 | Review dokumen | Documented |
| UAS-UML-03 | ERD sederhana | §11, DATABASE_SCHEMA §4 | `docs/DATABASE_SCHEMA.md` | 0 | Review dokumen | Documented |

## 4. Deliverables

| ID | Requirement | PRD | Lokasi | Fase | Verifikasi | Status |
|---|---|---|---|---|---|---|
| UAS-DEL-01 | Source code lengkap | §11 | Repository GitHub `rentalgo-oop` | 1–5 | Review repository + demo | Planned |
| UAS-DEL-02 | Database SQL | §11 | `database/schema/rentalgo.sql` + migrations | 5 | Import ulang SQL + `SHOW TABLES` | Planned |
| UAS-DEL-03 | README project | §11 | `README.md` | 0 | Review dokumen | Documented |
| UAS-DEL-04 | Dokumentasi proyek 8 bagian | §11 | `PRD.md`, `docs/*`, laporan akhir Phase 5, screenshot | 0–5 | Checklist dokumentasi | Documented / Planned |
| UAS-DEL-05 | Presentasi demo + fitur | §11 | Slide dan demo Phase 5 | 5 | Rehearsal presentasi | Planned |

Pemetaan dokumentasi UAS:

| Bagian Dokumentasi UAS | Lokasi RentalGo |
|---|---|
| Pendahuluan | `PRD.md` §1–§3 |
| Analisis kebutuhan | `PRD.md` §4–§8; `docs/UAS_REQUIREMENTS.md` |
| Desain sistem | `docs/ARCHITECTURE.md`; `docs/DATABASE_SCHEMA.md`; UML |
| Implementasi OOP | `docs/ARCHITECTURE.md` §5–§6; phase reports |
| Database design | `docs/DATABASE_SCHEMA.md` |
| Screenshot aplikasi | Laporan akhir Phase 5 |
| Pengujian | Test suite + phase reports + laporan akhir Phase 5 |
| Kesimpulan | Laporan akhir Phase 5 |

## 5. Requirement Intermediate

| ID | Requirement | PRD | Implementasi (Rencana) | Fase | Verifikasi | Status |
|---|---|---|---|---|---|---|
| UAS-INT-01 | Minimal 1000 LOC | NFR-09 | Kode proyek di `app/`, `database/`, `routes/`, `resources/views/` | 1–5 | Hitung LOC di Phase 5 | Planned |
| UAS-INT-02 | Minimal 8 class | UAS-OOP-01 | Sama dengan UAS-OOP-01 | 1 | Hitung class | Planned |
| UAS-INT-03 | Minimal 4 tabel | §8, DATABASE_SCHEMA | 5 tabel: users, customers, vehicles, rentals, payments | 1 | Migrasi + `SHOW TABLES` | Planned |
| UAS-INT-04 | Authentication sederhana | FR-AUTH-01–03 | Modul auth Phase 2 | 2 | Feature test | Planned |
| UAS-INT-05 | CRUD lengkap | FR-CUST/VEH/PAY | Modul pelanggan, kendaraan, pembayaran | 3–4 | Feature test + manual | Planned |
| UAS-INT-06 | Relasi database | DATABASE_SCHEMA §5 | Foreign key dan relasi Eloquent | 1 | Migrasi + test relasi | Planned |
| UAS-INT-07 | Validasi form | FR-VAL-01 | Form Request di semua modul | 2–4 | Feature test validasi | Planned |

## 6. Kriteria Penilaian

| ID | Aspek | Bobot | Fokus RentalGo | Verifikasi | Status |
|---|---|---|---|---|---|
| UAS-ASM-01 | Implementasi OOP | 30% | Domain vehicle hierarchy, service, enum, exception | Code review + test + class diagram | Planned |
| UAS-ASM-02 | Fungsionalitas | 20% | Auth, CRUD, rental, payment, search/filter, validation | Feature test + demo | Planned |
| UAS-ASM-03 | UI/UX | 10% | Tailwind, konsistensi, responsive | Screenshot + review | Planned |
| UAS-ASM-04 | Dokumentasi | 15% | PRD, docs, UML, laporan akhir | Checklist dokumen | Documented / Planned |
| UAS-ASM-05 | Presentasi | 15% | Demo + penjelasan | Rehearsal | Planned |
| UAS-ASM-06 | Kontribusi individu | 10% | Contribution log + commit history | Review log + Git | Planned |

## 7. Bonus Opsional

| ID | Fitur | Bonus | Rencana | Verifikasi | Status |
|---|---|---|---|---|---|
| UAS-BON-01 | REST API sederhana | +3 | Endpoint read-only kendaraan/rental di Phase 5 | Feature test API | Planned |
| UAS-BON-02 | Export PDF/Excel | +2 | Export PDF invoice/laporan di Phase 5 | Manual buka PDF | Planned |
| UAS-BON-03 | Real-time notification | +3 | Tidak direncanakan | — | Not Pursued |
| UAS-BON-04 | Docker deployment | +3 | Tidak digunakan (keputusan pemilik proyek) | — | Not Pursued |
| UAS-BON-05 | GitHub commit aktif & rapi | +2 | Conventional Commits, branch per fase, PR | Riwayat Git | Planned |
| UAS-BON-06 | Total bonus maksimal 10 | 10 | Maksimal realistis 7 poin tanpa Docker/real-time | Rekap bonus | Planned |

## 8. Pencegahan Pengurangan Nilai

| ID | Risiko | Kontrol | Verifikasi | Status |
|---|---|---|---|---|
| UAS-PEN-01 | Tidak menggunakan OOP | Arsitektur domain OOP wajib; review tiap fase | Code review + test | Planned |
| UAS-PEN-02 | CRUD tidak lengkap | 3 entitas full CRUD + lifecycle rental | Feature test + checklist | Planned |
| UAS-PEN-03 | Tidak ada dokumentasi | PRD + docs + laporan akhir + screenshot | Checklist dokumen | Documented / Planned |
| UAS-PEN-04 | Tidak presentasi | Slide + demo di Phase 5 | Rehearsal | Planned |
| UAS-PEN-05 | Plagiarisme | Kode orisinal, dokumentasi sumber, code walkthrough | Review + presentasi | Planned |
| UAS-PEN-06 | Tidak dapat menjalankan sistem | Uji MySQL + seeder + smoke test sebelum demo | Demo run | Planned |

## 9. Penilaian Individu

| ID | Faktor | Bukti yang Disiapkan | Verifikasi | Status |
|---|---|---|---|---|
| UAS-IND-01 | Kontribusi coding | `docs/CONTRIBUTION_LOG.md`, PR, phase report | Review log + PR | Planned |
| UAS-IND-02 | Commit GitHub | Commit bermakna per anggota; merge commit mempertahankan histori | `git log --author` | Planned |
| UAS-IND-03 | Presentasi individu | Pembagian bagian presentasi | Rehearsal | Planned |
| UAS-IND-04 | Pemahaman kode | Code walkthrough tiap anggota | Sesi tanya-jawab internal | Planned |

## 10. Ringkasan Cakupan

| Kategori | Jumlah ID | Documented | Documented / Planned | Planned | Optional | Not Pursued |
|---|---:|---:|---:|---:|---:|---:|
| Umum + OOP + Arsitektur | 16 | 0 | 0 | 16 | 0 | 0 |
| Fitur + UI + UML | 16 | 3 | 0 | 13 | 0 | 0 |
| Deliverables | 5 | 1 | 1 | 3 | 0 | 0 |
| Intermediate | 7 | 0 | 0 | 7 | 0 | 0 |
| Penilaian | 6 | 0 | 1 | 5 | 0 | 0 |
| Bonus | 6 | 0 | 0 | 4 | 0 | 2 |
| Pengurangan nilai | 6 | 0 | 1 | 5 | 0 | 0 |
| Penilaian individu | 4 | 0 | 0 | 4 | 0 | 0 |
| **Total** | **66** | **4** | **3** | **57** | **0** | **2** |

Catatan: beberapa baris dokumentasi berstatus `Documented / Planned` karena dokumen sudah ada,
tetapi laporan akhir dan screenshot baru dibuat pada Phase 5.

## 11. Aturan Pemeliharaan Traceability

1. Setiap phase report wajib memperbarui status ID yang dikerjakan.
2. Status `Verified` hanya boleh diberikan setelah test/manual check dan review anggota lain.
3. Jika requirement berubah, ubah `docs/UAS_REQUIREMENTS.md`, PRD, dan dokumen ini dalam PR yang sama.
4. Jangan menghapus ID; gunakan status `Not Pursued` dengan alasan.
5. Verifikasi LOC, jumlah class, dan jumlah tabel dilakukan ulang di Phase 5 sebelum presentasi.
