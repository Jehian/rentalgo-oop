# Traceability Matrix UAS → RentalGo

Dokumen ini memetakan setiap requirement eksplisit UAS ke requirement produk, lokasi implementasi
yang direncanakan, fase pengerjaan, dan metode verifikasi.

> **Penting:** Status `Planned` berarti belum diimplementasikan. Jangan mengubah status menjadi
> `Verified` tanpa bukti implementasi dan pengujian. Dokumen ini diperbarui pada setiap phase report.
>
> **Pembaruan Phase 2 (2026-11-25):** Phase 1 sudah merged (PR #1, `1b5caee`) dan Phase 2 sudah
> merged (PR #2, `c4847c2`). Requirement login, logout, session, role, layout, dashboard, dan portal
> pelanggan berstatus `Implemented` karena sudah lulus test lokal dan review/merge Phase 2.
>
> **Pembaruan Phase 3 (2026-11-26):** CRUD pelanggan dan kendaraan, search/filter/sort/pagination,
> validasi Form Request, upload gambar, dan detail riwayat rental sudah diimplementasikan, di-commit
> oleh Steven (`8336f17`–`80af0a8`), di-push ke `origin/phase/03-master-data`, dan PR-nya menunggu
> review/merge. Status `Verified` menunggu review PR Phase 3. CRUD pembayaran dan lifecycle rental
> tetap Phase 4, sehingga requirement CRUD minimal 3 entitas masih `In Progress`.

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
| UAS-GEN-01 | Aplikasi web PHP OOP untuk masalah nyata | §1, §3 | Seluruh aplikasi | 1–5 | Demo + code review | In Progress |
| UAS-GEN-02 | Tema Sistem Rental Kendaraan | §1 | Modul pelanggan, kendaraan, rental, pembayaran | 1–4 | Review fitur | In Progress |
| UAS-GEN-03 | Fokus OOP, modular, CRUD, auth, MVC | §3.2, §5 | `app/Domain`, `app/Models`, `app/Http`, `app/Services` | 1–4 | Code review + demo | In Progress |
| UAS-GEN-04 | Framework yang diizinkan (Laravel fitur dasar) | §10 | Laravel 13, Blade, Eloquent | 1–5 | Review stack | Implemented |
| UAS-OOP-01 | Minimal 8 class | §12.1 | `app/Domain/Vehicles/*`, `app/Models/*`, `app/Services/*`, `app/Support/*`, `app/Exceptions/*` | 1–4 | Hitung class + class diagram | Implemented |
| UAS-OOP-02 | Encapsulation | §12.1 | Private/protected property + getter di domain; `#[Fillable]`, `#[Hidden]` di model; dependency private di service | 1 | Code review + unit test | Implemented |
| UAS-OOP-03 | Minimal 2 inheritance | §12.1 | `Car extends Vehicle`, `Motorcycle extends Vehicle` | 1 | Unit test + class diagram | Implemented |
| UAS-OOP-04 | Polymorphism / method overriding | §12.1 | `calculateRentalCost()` di-override; `RentalService` menerima tipe abstrak | 1 | Unit test polimorfik | Implemented |
| UAS-OOP-05 | Abstraction (interface/abstract class) | §12.1 | `App\Contracts\Rentable`; `App\Domain\Vehicles\Vehicle` (abstract) | 1 | Code review + class diagram | Implemented |
| UAS-OOP-06 | Constructor | §12.1 | Constructor domain class + constructor injection service/controller | 1–2 | Code review | Implemented |
| UAS-OOP-07 | Minimal 1 static method | §12.1 | `VehicleFactory::fromModel()`, `DocumentCodeGenerator::rentalCode()`/`paymentCode()` | 1 | Unit test | Implemented |
| UAS-OOP-08 | Namespace (disarankan) | §12.1 | PSR-4 `App\...`, `Database\...` | 1 | `composer dump-autoload` + review | Implemented |
| UAS-OOP-09 | Exception handling minimal try-catch | §12.1 | `VehicleNotAvailableException`, `UnsupportedVehicleTypeException`; try-catch di factory/service | 1 | Unit test + review | Implemented |
| UAS-ARC-01 | MVC sederhana | §12.1 | `routes/`, `app/Http/Controllers`, `app/Models`, `resources/views` | 2–4 | Review struktur + demo | In Progress |
| UAS-ARC-02 | Modular programming | §12.1 | Route per modul, folder controller/request/view per modul | 2–4 | Review struktur | In Progress |
| UAS-ARC-03 | Reusable component | §12.1 | 7 komponen Blade di `resources/views/components` | 2–4 | Review komponen + screenshot | Implemented |

## 3. Requirement Fitur

| ID | Requirement | PRD | Lokasi Implementasi (Rencana) | Fase | Verifikasi | Status |
|---|---|---|---|---|---|---|
| UAS-FEA-01 | Login | FR-AUTH-01 | `AuthController@login` (`web`), `CustomerAuthController` (`customer`), `routes/auth.php`, `routes/customer.php` | 2 | `AuthTest` login/registrasi/guard | Implemented |
| UAS-FEA-02 | Logout | FR-AUTH-02 | `AuthController@logout`, `CustomerAuthController@logout` | 2 | `AuthTest` logout | Implemented |
| UAS-FEA-03 | Session user | FR-AUTH-03 | Guard terpisah `auth:web` dan `auth:customer`, session regenerate/invalidate | 2 | `AuthTest` & `CustomerPortalTest` isolasi guard | Implemented |
| UAS-FEA-04 | Dashboard ringkas/statistik | FR-DASH-01, FR-DASH-02 | `DashboardController`, `DashboardService`, `views/dashboard`, `PortalController`, `views/portal` | 2 | `DashboardTest`, `CustomerPortalTest`, screenshot | Implemented |
| UAS-FEA-05 | CRUD: tambah data (min. 3 entitas) | FR-CUST-02, FR-VEH-02, FR-PAY-01 | `CustomerController@store`, `VehicleController@store`, Form Request, view pelanggan/kendaraan; pembayaran Phase 4 | 3–4 | `CustomerCrudTest`, `VehicleCrudTest`; pembayaran pending | In Progress |
| UAS-FEA-06 | CRUD: edit data (min. 3 entitas) | FR-CUST-03, FR-VEH-03, FR-PAY-03 | `CustomerController@update`, `VehicleController@update`, Form Request, view; pembayaran Phase 4 | 3–4 | `CustomerCrudTest`, `VehicleCrudTest`; pembayaran pending | In Progress |
| UAS-FEA-07 | CRUD: hapus data (min. 3 entitas) | FR-CUST-04, FR-VEH-04, FR-PAY-04 | Soft delete pelanggan/kendaraan via route Admin; delete payment pending Phase 4 | 3–4 | `CustomerCrudTest`, `VehicleCrudTest`; pembayaran pending | In Progress |
| UAS-FEA-08 | CRUD: detail data (min. 3 entitas) | FR-CUST-05, FR-VEH-05, FR-PAY-05 | `CustomerController@show`, `VehicleController@show`, view detail + riwayat; payment detail Phase 4 | 3–4 | `CustomerCrudTest`, `VehicleCrudTest`; pembayaran pending | In Progress |
| UAS-FEA-09 | Minimal 2 fitur tambahan | §6.8 | Chart dashboard, search, pagination, sorting, filter, upload gambar | 2–4 | Feature test + screenshot Phase 3 | Implemented |
| UAS-FEA-10 | Daftar fitur tambahan yang dipilih | §6.8 | Lihat pemetaan fitur di PRD §6.8 | 2–4 | Review fitur + phase report | Implemented |
| UAS-UI-01 | Bootstrap/Tailwind/admin template | §10 | Tailwind CSS 4 + layout Blade responsif | 2–4 | Review UI + screenshot | Implemented |
| UAS-UI-02 | Responsive layout | NFR-01 | `resources/views/layouts`, Tailwind breakpoints | 2–4 | Screenshot desktop/mobile | Implemented |
| UAS-UI-03 | Form validation sederhana | FR-VAL-01, NFR-03 | Auth, pelanggan, kendaraan memakai Form Request + error dekat field; rental/pembayaran menyusul | 2–4 | `AuthTest`, `CustomerCrudTest`, `VehicleCrudTest` | In Progress |
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
| UAS-INT-02 | Minimal 8 class | UAS-OOP-01 | Sama dengan UAS-OOP-01 | 1 | Hitung class | Implemented |
| UAS-INT-03 | Minimal 4 tabel | §8, DATABASE_SCHEMA | 5 tabel: users, customers, vehicles, rentals, payments | 1 | Migrasi + `SHOW TABLES` | Implemented |
| UAS-INT-04 | Authentication sederhana | FR-AUTH-01–03 | Modul auth Phase 2 | 2 | 9 feature test auth | Implemented |
| UAS-INT-05 | CRUD lengkap | FR-CUST/VEH/PAY | Modul pelanggan dan kendaraan selesai lokal; pembayaran Phase 4 | 3–4 | Feature test + manual; pembayaran pending | In Progress |
| UAS-INT-06 | Relasi database | DATABASE_SCHEMA §5 | Foreign key dan relasi Eloquent | 1 | Migrasi + test relasi | Implemented |
| UAS-INT-07 | Validasi form | FR-VAL-01 | Form Request auth, pelanggan, dan kendaraan; rental/pembayaran menyusul | 2–4 | `AuthTest`, `CustomerCrudTest`, `VehicleCrudTest` | In Progress |

## 6. Kriteria Penilaian

| ID | Aspek | Bobot | Fokus RentalGo | Verifikasi | Status |
|---|---|---|---|---|---|
| UAS-ASM-01 | Implementasi OOP | 30% | Domain vehicle hierarchy, service, enum, exception | Code review + test + class diagram | Planned |
| UAS-ASM-02 | Fungsionalitas | 20% | Auth, CRUD, rental, payment, search/filter, validation | Feature test + demo | In Progress |
| UAS-ASM-03 | UI/UX | 10% | Tailwind, konsistensi, responsive | Screenshot + review | In Progress |
| UAS-ASM-04 | Dokumentasi | 15% | PRD, docs, UML, laporan akhir | Checklist dokumen | Documented / Planned |
| UAS-ASM-05 | Presentasi | 15% | Demo + penjelasan | Rehearsal | Planned |
| UAS-ASM-06 | Kontribusi individu | 10% | Contribution log berbasis task + commit history | Review log + Git | In Progress |

## 7. Bonus Opsional

| ID | Fitur | Bonus | Rencana | Verifikasi | Status |
|---|---|---|---|---|---|
| UAS-BON-01 | REST API sederhana | +3 | Endpoint read-only kendaraan/rental di Phase 5 | Feature test API | Planned |
| UAS-BON-02 | Export PDF/Excel | +2 | Export PDF invoice/laporan di Phase 5 | Manual buka PDF | Planned |
| UAS-BON-03 | Real-time notification | +3 | Tidak direncanakan | — | Not Pursued |
| UAS-BON-04 | Docker deployment | +3 | Tidak digunakan (keputusan pemilik proyek) | — | Not Pursued |
| UAS-BON-05 | GitHub commit aktif & rapi | +2 | Conventional Commits, branch per fase, PR | Riwayat Git | In Progress |
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
| UAS-IND-01 | Kontribusi coding | `docs/CONTRIBUTION_LOG.md` berbasis histori commit (email Git masing-masing di phase mana saja), PR, phase report | Review log + PR | In Progress |
| UAS-IND-02 | Commit GitHub | Commit bermakna per anggota memakai email Git masing-masing; merge commit mempertahankan histori | `git log --format="%an <%ae>"` | In Progress |
| UAS-IND-03 | Presentasi individu | Pembagian bagian presentasi | Rehearsal | Planned |
| UAS-IND-04 | Pemahaman kode | Code walkthrough tiap anggota | Sesi tanya-jawab internal | Planned |

## 10. Ringkasan Cakupan

| Kategori | Jumlah ID | Documented | Documented / Planned | Planned | In Progress | Implemented | Optional | Not Pursued |
|---|---:|---:|---:|---:|---:|---:|---:|---:|
| Umum + OOP + Arsitektur | 16 | 0 | 0 | 0 | 5 | 11 | 0 | 0 |
| Fitur + UI + UML | 16 | 3 | 0 | 0 | 5 | 8 | 0 | 0 |
| Deliverables | 5 | 1 | 1 | 3 | 0 | 0 | 0 | 0 |
| Intermediate | 7 | 0 | 0 | 1 | 2 | 4 | 0 | 0 |
| Penilaian | 6 | 0 | 1 | 2 | 3 | 0 | 0 | 0 |
| Bonus | 6 | 0 | 0 | 3 | 1 | 0 | 0 | 2 |
| Pengurangan nilai | 6 | 0 | 1 | 5 | 0 | 0 | 0 | 0 |
| Penilaian individu | 4 | 0 | 0 | 2 | 2 | 0 | 0 | 0 |
| **Total** | **66** | **4** | **3** | **16** | **18** | **23** | **0** | **2** |

Catatan: beberapa baris dokumentasi berstatus `Documented / Planned` karena dokumen sudah ada,
tetapi laporan akhir dan screenshot baru dibuat pada Phase 5. UAS-ASM-06 dan UAS-IND-01 menjadi
`In Progress` karena log kontribusi berbasis task/commit mulai dipelihara; finalisasi di Phase 5.

## 11. Aturan Pemeliharaan Traceability

1. Setiap phase report wajib memperbarui status ID yang dikerjakan.
2. Status `Verified` hanya boleh diberikan setelah test/manual check dan review anggota lain.
3. Jika requirement berubah, ubah `docs/UAS_REQUIREMENTS.md`, PRD, dan dokumen ini dalam PR yang sama.
4. Jangan menghapus ID; gunakan status `Not Pursued` dengan alasan.
5. Verifikasi LOC, jumlah class, dan jumlah tabel dilakukan ulang di Phase 5 sebelum presentasi.
