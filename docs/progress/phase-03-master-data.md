# Phase 3 — Master Data CRUD (Customers & Vehicles)

| Item | Keterangan |
|---|---|
| Fase | Phase 3 — Master Data CRUD (Customers & Vehicles) |
| Pelaksana commit | Steven (422024001) — `steven.422024001@civitas.ukrida.ac.id` |
| Branch | `phase/03-master-data` (pushed ke `origin`) |
| Pull Request | PR dibuat di GitHub (3 commit dari Steven); menunggu review/merge |
| Tanggal mulai | 2026-11-25 |
| Tanggal selesai | 2026-11-26 (committed & pushed) |
| Status | PR dibuat — menunggu review/merge |
| Reviewer | `pending` (Benny review/merge) |

## 1. Ringkasan

Phase 3 menambahkan CRUD pelanggan dan kendaraan untuk Admin/Staff, meliputi daftar dengan pencarian,
sorting, filter, pagination, form create/edit dengan Form Request berbahasa Indonesia, halaman detail
beserta riwayat rental, upload/ganti gambar kendaraan melalui `VehicleImageService`, serta soft delete
yang hanya dapat dilakukan Admin dan ditolak jika masih ada rental aktif. Tidak ada perubahan schema
database dan tidak ada dependency baru. Seluruh test, Pint, dan build Vite lulus; UI diperiksa melalui
smoke test HTTP dan screenshot desktop/mobile.

## 2. Tujuan vs Hasil

| Tujuan | Hasil | Bukti |
|---|---|---|
| CRUD pelanggan + detail riwayat rental | Tercapai | `CustomerController`, `views/customers/*`, `CustomerCrudTest` |
| CRUD kendaraan + detail riwayat rental | Tercapai | `VehicleController`, `views/vehicles/*`, `VehicleCrudTest` |
| Search, sorting, filter, pagination | Tercapai | `CustomerController@index`, `VehicleController@index`, feature test |
| Upload/ganti gambar kendaraan | Tercapai | `VehicleImageService`, `StoreVehicleRequest`, `UpdateVehicleRequest` |
| Validasi server-side dan pesan Indonesia | Tercapai | 4 Form Request + error dekat field |
| Soft delete hanya Admin dan ditolak saat rental aktif | Tercapai | Route middleware `admin`, guard di controller, feature test |
| Detail menampilkan riwayat rental | Tercapai | `customers/show`, `vehicles/show`, feature test |
| UI responsif dan konsisten layout Phase 2 | Tercapai | Screenshot desktop/mobile `docs/assets/phase-03/` |
| PR, review, merge | Sebagian | Branch sudah di-push dan PR dibuka di GitHub; menunggu review/merge |

## 3. Scope yang Diselesaikan

- [x] Daftar pelanggan dengan pencarian nama/email/telepon/NIK/SIM, sorting, dan pagination.
- [x] Tambah dan edit pelanggan dengan validasi wajib/unik dan pesan Bahasa Indonesia.
- [x] Detail pelanggan dengan ringkasan jumlah rental dan riwayat rental.
- [x] Soft delete pelanggan oleh Admin, ditolak jika memiliki rental aktif.
- [x] Daftar kendaraan dengan pencarian nomor polisi/merek/model, filter tipe/status, sorting, dan pagination.
- [x] Tambah dan edit kendaraan dengan field kondisional mobil/motor.
- [x] Upload/ganti gambar kendaraan (JPG/JPEG/PNG/WebP, maksimal 2 MB) dan hapus gambar lama saat replace/delete.
- [x] Status kendaraan manual dibatasi `available`/`maintenance`; `rented` dikelola alur rental Phase 4.
- [x] Tipe dan status kendaraan dikunci saat kendaraan memiliki rental aktif.
- [x] Detail kendaraan dengan spesifikasi, foto, dan riwayat rental.
- [x] Soft delete kendaraan oleh Admin, ditolak jika memiliki rental aktif.
- [x] Menu sidebar Pelanggan dan Kendaraan diaktifkan tanpa mengubah desain layout Phase 2.
- [x] Feature test pelanggan dan kendaraan (27 test pada folder Phase 3).
- [x] Commit dan push branch `phase/03-master-data` (`8336f17`, `be149f3`, `80af0a8`).
- [ ] PR di GitHub direview dan di-merge ke `main`.

## 4. File yang Dibuat/Diubah

| File | Jenis | Keterangan |
|---|---|---|
| `app/Http/Controllers/CustomerController.php` | Baru | Index, create, store, show, edit, update, destroy pelanggan |
| `app/Http/Controllers/VehicleController.php` | Baru | CRUD kendaraan, filter, upload/ganti gambar, lock rental aktif |
| `app/Http/Requests/StoreCustomerRequest.php` | Baru | Validasi tambah pelanggan |
| `app/Http/Requests/UpdateCustomerRequest.php` | Baru | Validasi edit pelanggan dengan unique ignore ID sendiri |
| `app/Http/Requests/StoreVehicleRequest.php` | Baru | Validasi kendaraan + field kondisional + gambar |
| `app/Http/Requests/UpdateVehicleRequest.php` | Baru | Validasi edit kendaraan + lock tipe/status saat rental aktif |
| `app/Services/VehicleImageService.php` | Baru | Simpan/hapus gambar pada disk dan folder dari `config/rental.php` |
| `routes/master.php` | Diubah | Route resource pelanggan/kendaraan, hapus khusus Admin |
| `resources/views/customers/_form.blade.php` | Baru | Form pelanggan reusable create/edit |
| `resources/views/customers/index.blade.php` | Baru | Daftar, pencarian, sorting, pagination, modal hapus |
| `resources/views/customers/create.blade.php` | Baru | Halaman tambah pelanggan |
| `resources/views/customers/edit.blade.php` | Baru | Halaman edit pelanggan |
| `resources/views/customers/show.blade.php` | Baru | Detail pelanggan + riwayat rental |
| `resources/views/vehicles/_form.blade.php` | Baru | Form kendaraan reusable, field kondisional, preview gambar |
| `resources/views/vehicles/index.blade.php` | Baru | Daftar, filter, sorting, pagination, modal hapus |
| `resources/views/vehicles/create.blade.php` | Baru | Halaman tambah kendaraan |
| `resources/views/vehicles/edit.blade.php` | Baru | Halaman edit kendaraan |
| `resources/views/vehicles/show.blade.php` | Baru | Detail kendaraan + foto + riwayat rental |
| `resources/views/layouts/app.blade.php` | Diubah minimal | Mengaktifkan link Pelanggan/Kendaraan; desain tidak diubah |
| `database/factories/VehicleFactory.php` | Diubah | Normalisasi transmisi ke `manual`/`automatic` |
| `tests/Feature/Customers/CustomerCrudTest.php` | Baru | 12 feature test pelanggan |
| `tests/Feature/Vehicles/VehicleCrudTest.php` | Baru | 15 feature test kendaraan |
| `docs/assets/phase-03/customers-desktop.png`, `customers-mobile.png` | Baru | Screenshot daftar pelanggan |
| `docs/assets/phase-03/vehicles-desktop.png`, `vehicles-mobile.png` | Baru | Screenshot daftar kendaraan |
| `docs/progress/phase-03-master-data.md` | Baru | Laporan fase ini |
| `docs/PROJECT_STATUS.md`, `docs/UAS_TRACEABILITY.md`, `docs/CONTRIBUTION_LOG.md`, `docs/PHASE_PLAN.md`, `docs/AI_HANDOFF.md`, `docs/DECISIONS.md`, `README.md` | Diubah | Sinkronisasi status, traceability, kontribusi, keputusan, dan dokumentasi |

## 5. Perubahan Database

**Tidak ada.** Schema Phase 1 dipakai apa adanya. Data lokal pada kolom `vehicles.transmission`
dinormalisasi dari `Manual`/`Matic` menjadi `manual`/`automatic` agar sesuai
`docs/DATABASE_SCHEMA.md`; tidak ada migration baru.

## 6. Pemetaan OOP dan UAS

| Requirement | Implementasi | File | Verifikasi |
|---|---|---|---|
| FR-CUST-01 | Daftar pelanggan + search/sort/pagination | `CustomerController@index`, `customers/index` | `CustomerCrudTest` search/sort/pagination |
| FR-CUST-02 | Tambah pelanggan + validasi unik | `StoreCustomerRequest`, `CustomerController@store` | `CustomerCrudTest` create/validasi |
| FR-CUST-03 | Edit pelanggan | `UpdateCustomerRequest`, `CustomerController@update` | `CustomerCrudTest` update/unik |
| FR-CUST-04 | Soft delete + aturan rental aktif | `CustomerController@destroy` + middleware `admin` | `CustomerCrudTest` delete/403/aktif |
| FR-CUST-05 | Detail + riwayat rental | `CustomerController@show`, `customers/show` | `CustomerCrudTest` detail |
| FR-VEH-01 | Daftar kendaraan + filter/search/sort/pagination | `VehicleController@index`, `vehicles/index` | `VehicleCrudTest` filter/search/sort/pagination |
| FR-VEH-02 | Tambah kendaraan + upload gambar | `StoreVehicleRequest`, `VehicleImageService` | `VehicleCrudTest` create + gambar |
| FR-VEH-03 | Edit kendaraan + ganti gambar | `UpdateVehicleRequest`, `VehicleController@update` | `VehicleCrudTest` replace/keep image |
| FR-VEH-04 | Soft delete + aturan rental aktif | `VehicleController@destroy` + middleware `admin` | `VehicleCrudTest` delete/403/aktif |
| FR-VEH-05 | Detail + riwayat rental | `VehicleController@show`, `vehicles/show` | `VehicleCrudTest` detail |
| FR-VAL-01 | Validasi server-side dan pesan per field | 4 Form Request | Feature test validasi + tampilan error |
| UAS-FEA-05–08 | CRUD tambah/edit/hapus/detail | Pelanggan + kendaraan selesai; pembayaran Phase 4 | Sebagian — 2 dari 3 entitas |
| UAS-FEA-09–10 | Fitur tambahan | Search, pagination, sorting, filter, upload gambar | Feature test + screenshot |
| UAS-UI-01–03 | Tailwind, responsif, validasi form | Layout Phase 2 + view Phase 3 | Screenshot + feature test |
| UAS-ARC-01–02 | MVC dan modular | Controller/request/service/view per modul, `routes/master.php` | Review struktur |
| UAS-INT-05, INT-07 | CRUD lengkap dan validasi | Pelanggan/kendaraan selesai; pembayaran Phase 4 | Sebagian |
| ADR-005, ADR-013, ADR-022 | Role hapus, soft delete, image storage | Middleware `admin`, `SoftDeletes`, `VehicleImageService` | Feature test |
| ADR-029 | Keputusan Phase 3 | Lihat `docs/DECISIONS.md` | Dokumen |

## 7. Pengujian dan Verifikasi

### 7.1 Perintah

```powershell
php artisan test
vendor\bin\pint --test
npm run build
php artisan route:list --except-vendor
```

### 7.2 Hasil

```text
Tests: 74 passed (293 assertions)
Pint: passed
Vite build: passed
- CSS 56.57 kB (gzip 11.90 kB)
- JS 209.82 kB (gzip 72.05 kB)
```

Test Phase 3 berjumlah 27 test (12 pelanggan + 15 kendaraan), termasuk guest redirect, role Admin/Staff,
validasi wajib/unik, unik terhadap data soft-deleted, search/filter/sort/pagination, create/edit,
upload/ganti gambar, detail riwayat rental, dan aturan hapus.

### 7.3 Manual Check

| Skenario | Langkah | Hasil |
|---|---|---|
| Login Admin | POST `/login` dengan `admin@rentalgo.test` | Sesi aktif; halaman berikutnya 200 |
| Daftar pelanggan | GET `/customers` | 200; pencarian, sorting, tombol tambah, aksi tampil |
| Tambah pelanggan | GET `/customers/create` | 200; form tampil |
| Detail pelanggan | GET `/customers/{id}` | 200; kartu ringkasan dan riwayat rental tampil |
| Edit pelanggan | GET `/customers/{id}/edit` | 200; form terisi data pelanggan |
| Daftar kendaraan | GET `/vehicles` | 200; filter tipe/status, sorting, aksi tampil |
| Tambah kendaraan | GET `/vehicles/create` | 200; form dan field kondisional tampil |
| Detail kendaraan | GET `/vehicles/{id}` | 200; foto/spesifikasi dan riwayat rental tampil |
| Edit kendaraan | GET `/vehicles/{id}/edit` | 200; form terisi data kendaraan |
| Responsif | Screenshot 1440px dan 390px | Layout, sidebar/off-canvas, filter, dan tabel menyesuaikan; tabel lebar memakai horizontal scroll |

## 8. Screenshot

| Halaman | Desktop | Mobile |
|---|---|---|
| Daftar pelanggan | `docs/assets/phase-03/customers-desktop.png` | `docs/assets/phase-03/customers-mobile.png` |
| Daftar kendaraan | `docs/assets/phase-03/vehicles-desktop.png` | `docs/assets/phase-03/vehicles-mobile.png` |

Screenshot diambil dari aplikasi lokal dengan data seeder MySQL melalui Chrome headless setelah login
Admin.

## 9. Masalah, Deviasi, dan Keterbatasan

- Pelanggan yang dibuat Admin tidak memiliki password portal; akun portal tetap dibuat melalui
  registrasi mandiri. Keputusan dicatat di ADR-029.
- Status `rented` tidak dapat dipilih manual pada form kendaraan; status tersebut hanya diatur alur
  rental Phase 4. Tipe dan status juga dikunci saat kendaraan memiliki rental aktif.
- Nilai transmisi dinormalisasi ke `manual`/`automatic`; factory Phase 1 diubah dan data MySQL lokal
  diperbarui. Unit test domain lama tetap memakai `Matic` sebagai nilai string bebas, bukan data DB.
- `resources/views/layouts/app.blade.php` hanya diubah untuk mengaktifkan dua link sidebar; polish
  UI/UX menyeluruh tetap ditunda ke Phase 5 sesuai arahan pemilik proyek.
- Soft delete menyisakan nilai unik (`email`, `identity_number`, `license_number`, `plate_number`)
  pada baris terhapus. Validasi sengaja memeriksa seluruh tabel agar tidak terjadi DB unique error saat
  membuat data baru dengan nilai yang sama.
- Gambar kendaraan dihapus saat kendaraan di-soft-delete; belum ada fitur restore, sehingga restore
  data (jika ditambahkan nanti) perlu mempertimbangkan gambar.
- Tabel pada mobile memakai horizontal scroll; belum diubah menjadi kartu karena polish UI ditunda.
- Phase 3 sudah di-commit dan di-push (3 commit di email `steven.422024001@civitas.ukrida.ac.id`) dan
  PR-nya di GitHub menunggu review/merge. Kontribusi dinilai dari histori commit pada email Git
  masing-masing anggota dan difinalkan di Phase 5.

## 10. Handoff ke Fase Berikutnya

- **Stabil:** route `/customers` dan `/vehicles`, Form Request, pola controller tipis, `VehicleImageService`,
  komponen Blade Phase 2, dan aturan soft delete.
- **Phase 4:** gunakan route/model pelanggan dan kendaraan untuk form rental; panggil `RentalService`
  untuk availability/pricing; jangan menduplikasi logika tersebut di controller.
- **Kontrak penting:** hapus master hanya Admin (`admin` middleware); kendaraan dengan rental aktif tidak
  boleh dihapus/diubah tipe/statusnya; gambar disimpan di disk `public` folder `vehicles`.
- **Risiko:** Phase 3 sudah di-push tetapi belum di-merge. Jika Phase 4 dimulai sebelum merge, branch
  Phase 4 harus dibuat dari branch/gabungan yang tepat agar route dan view master tersedia.
- **Jangan:** mengubah migration/model Phase 1 tanpa koordinasi; menambah dependency; menghapus riwayat
  transaksi.

## 11. Checklist

- [x] Scope selesai (committed & pushed).
- [x] Test ditambahkan dan lulus.
- [x] Pint lulus.
- [x] Build Vite lulus.
- [x] Tidak ada secret.
- [x] Dokumentasi status/traceability/contribution log diperbarui.
- [x] Screenshot disertakan.
- [x] Commit dan push branch `phase/03-master-data`.
- [x] PR dibuat di GitHub.
- [ ] PR direview dan merge selesai.

## 12. Catatan Kontribusi

| Task | Kontributor (Author Email / Pelaksana) | Commit/PR | Bukti |
|---|---|---|---|
| Implementasi CRUD pelanggan/kendaraan, validasi, upload gambar, test, screenshot, dan laporan Phase 3 | Steven (422024001) — `steven.422024001@civitas.ukrida.ac.id` | `8336f17`, `be149f3`, `80af0a8` (PR di GitHub) | Branch pushed; 74 test, Pint, build |
| Fondasi Phase 1 yang dipakai Phase 3 | Benny Anderson (422024008) — `benanderson2nds@gmail.com` | PR #1 (`1b5caee`) | Phase 1 merged |
| Layout dan komponen Phase 2 yang dipakai Phase 3 | Steven Credentia Ivanemaga Zega (422024020) — `steven.422024020@civitas.ukrida.ac.id` | PR #2 (`c4847c2`) | Phase 2 merged |
| Review & merge Phase 3 | `pending` (Benny) | — | Menunggu review PR di GitHub |

> Pembagian kerja tidak dikunci per phase secara kaku; kontribusi dicatat berdasarkan histori commit
> pada email Git masing-masing anggota dan direkonsiliasi di `docs/CONTRIBUTION_LOG.md` pada Phase 5.
