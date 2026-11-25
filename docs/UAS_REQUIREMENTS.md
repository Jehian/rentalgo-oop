# Ekstraksi Requirement UAS

Dokumen ini mengekstrak seluruh requirement eksplisit dari naskah UAS resmi. Dokumen UAS adalah
sumber kebenaran utama. Requirement yang **tidak** tertulis di UAS tidak boleh diperlakukan sebagai
kewajiban; keputusan tambahan proyek dicatat di `docs/DECISIONS.md`.

## 1. Sumber Dokumen

| Item | Keterangan |
|---|---|
| Nama file | `docs/reference/UAS_Cynthia Hayat_SI2WPO084 – Pemrograman Berorientasi Objek (1).doc` |
| Format | Microsoft Word 97–2003 (`.doc`) |
| Mata kuliah | SI2WPO084 — Pemrograman Berorientasi Objek |
| Dosen | Cynthia Hayat |
| Program Studi | Sistem Informasi |
| Semester | Ganjil/Genap 2025/2026 |
| Sifat ujian | Project |
| Metode ekstraksi | Teks dokumen dibaca langsung menggunakan Microsoft Word; struktur dan angka dicek manual |

Catatan ekstraksi:

- Dokumen asli tidak diubah.
- Beberapa karakter bullet pada dokumen asli rusak saat ekstraksi; makna requirement tidak berubah.
- Dokumen UAS tidak menetapkan skema database, nama class, framework frontend spesifik, atau pembagian
  fase kelompok. Hal-hal tersebut adalah keputusan proyek.

## 2. Requirement Umum dan Tema

| ID | Requirement Eksplisit | Klasifikasi |
|---|---|---|
| UAS-GEN-01 | Membuat aplikasi web berbasis Object-Oriented Programming (OOP) menggunakan PHP untuk menyelesaikan masalah nyata pada bidang pendidikan, bisnis, kesehatan, layanan publik, atau manajemen data. | Eksplisit |
| UAS-GEN-02 | Tema yang dipilih: **Sistem Rental Kendaraan**. | Eksplisit (pilihan tema) |
| UAS-GEN-03 | Fokus proyek: pemahaman konsep dasar–menengah OOP, struktur program modular, CRUD database, authentication sederhana, MVC sederhana, dan pengelolaan data berbasis web. | Eksplisit |
| UAS-GEN-04 | Framework yang diperbolehkan: Native PHP OOP, CodeIgniter, atau Laravel (fitur dasar). | Eksplisit |

## 3. Requirement OOP

| ID | Requirement Eksplisit | Batasan |
|---|---|---|
| UAS-OOP-01 | Class & Object | Minimal **8 class** |
| UAS-OOP-02 | Encapsulation | Wajib |
| UAS-OOP-03 | Inheritance | Minimal **2 inheritance** |
| UAS-OOP-04 | Polymorphism | Method overriding |
| UAS-OOP-05 | Abstraction | Interface dan/atau abstract class |
| UAS-OOP-06 | Constructor | Wajib |
| UAS-OOP-07 | Static method | Minimal **1** |
| UAS-OOP-08 | Namespace | Disarankan |
| UAS-OOP-09 | Exception handling | Minimal **try-catch** |

## 4. Requirement Struktur Sistem

| ID | Requirement Eksplisit |
|---|---|
| UAS-ARC-01 | Menggunakan pola MVC sederhana. |
| UAS-ARC-02 | Modular programming. |
| UAS-ARC-03 | Reusable component. |

## 5. Requirement Fitur Minimum

| ID | Requirement Eksplisit | Batasan |
|---|---|---|
| UAS-FEA-01 | Authentication: login. | Wajib |
| UAS-FEA-02 | Authentication: logout. | Wajib |
| UAS-FEA-03 | Authentication: session user. | Wajib |
| UAS-FEA-04 | Dashboard berisi informasi ringkas data/statistik sederhana. | Wajib |
| UAS-FEA-05 | CRUD data: tambah data. | Minimal **3 entitas data** |
| UAS-FEA-06 | CRUD data: edit data. | Minimal **3 entitas data** |
| UAS-FEA-07 | CRUD data: hapus data. | Minimal **3 entitas data** |
| UAS-FEA-08 | CRUD data: detail data. | Minimal **3 entitas data** |
| UAS-FEA-09 | Fitur tambahan: minimal pilih **2** dari daftar. | Wajib 2 |
| UAS-FEA-10 | Daftar fitur tambahan yang boleh dipilih: search data, pagination, upload gambar/file, export PDF sederhana, sorting data, filter data, email simulation, dashboard chart sederhana. | Pilihan |

## 6. Requirement Frontend

| ID | Requirement Eksplisit |
|---|---|
| UAS-UI-01 | Frontend boleh menggunakan Bootstrap, Tailwind CSS, atau admin template sederhana. |
| UAS-UI-02 | Layout responsif. |
| UAS-UI-03 | Form validation sederhana. |

## 7. Requirement UML

| ID | Requirement Eksplisit |
|---|---|
| UAS-UML-01 | Use Case Diagram. |
| UAS-UML-02 | Class Diagram. |
| UAS-UML-03 | ERD sederhana. |

## 8. Deliverables

| ID | Requirement Eksplisit | Detail |
|---|---|---|
| UAS-DEL-01 | Source code lengkap. | Wajib |
| UAS-DEL-02 | Database SQL (jika ada). | Wajib untuk proyek berbasis database |
| UAS-DEL-03 | README project. | Wajib |
| UAS-DEL-04 | Dokumentasi proyek minimal berisi: Pendahuluan, Analisis kebutuhan, Desain sistem, Implementasi OOP, Database design, Screenshot aplikasi, Pengujian, Kesimpulan. | Wajib |
| UAS-DEL-05 | Presentasi: demo aplikasi dan penjelasan fitur. | Wajib |

## 9. Requirement Intermediate

| ID | Requirement Eksplisit | Batasan |
|---|---|---|
| UAS-INT-01 | Line of Code | Minimal **1000 LOC** |
| UAS-INT-02 | Jumlah class | Minimal **8 class** |
| UAS-INT-03 | Jumlah tabel database | Minimal **4 tabel** |
| UAS-INT-04 | Authentication sederhana. | Wajib |
| UAS-INT-05 | CRUD lengkap. | Wajib |
| UAS-INT-06 | Relasi database. | Wajib |
| UAS-INT-07 | Validasi form. | Wajib |

## 10. Kriteria Penilaian

| ID | Aspek | Bobot | Rincian Rubrik |
|---|---|---|---|
| UAS-ASM-01 | Implementasi OOP | 30% | Class & object, encapsulation, inheritance, polymorphism, abstract/interface |
| UAS-ASM-02 | Fungsionalitas sistem | 20% | Login & session, responsive layout, CRUD, search/filter/dll, validasi & error handling |
| UAS-ASM-03 | UI/UX | 10% | Konsistensi tampilan, kemudahan penggunaan |
| UAS-ASM-04 | Dokumentasi | 15% | Laporan lengkap, UML, penjelasan implementasi |
| UAS-ASM-05 | Presentasi | 15% | Demo sistem, penguasaan materi |
| UAS-ASM-06 | Kontribusi individu | 10% | Penilaian individu dalam kelompok |

## 11. Bonus (Opsional)

| ID | Fitur | Bonus | Catatan |
|---|---|---|---|
| UAS-BON-01 | REST API sederhana | +3 | Opsional |
| UAS-BON-02 | Export PDF/Excel | +2 | Opsional |
| UAS-BON-03 | Real-time notification | +3 | Opsional |
| UAS-BON-04 | Docker deployment | +3 | Opsional; **tidak digunakan pada proyek ini** |
| UAS-BON-05 | GitHub commit aktif & rapi | +2 | Opsional |
| UAS-BON-06 | Total bonus maksimal | 10 poin | Batas maksimal |

## 12. Pengurangan Nilai

| ID | Pelanggaran | Pengurangan |
|---|---|---|
| UAS-PEN-01 | Tidak menggunakan OOP | -20 |
| UAS-PEN-02 | CRUD tidak lengkap | -15 |
| UAS-PEN-03 | Tidak ada dokumentasi | -10 |
| UAS-PEN-04 | Tidak presentasi | -15 |
| UAS-PEN-05 | Terindikasi plagiarisme | Nilai 0 |
| UAS-PEN-06 | Tidak dapat menjalankan sistem saat demo | -20 |

## 13. Penilaian Individu

| ID | Faktor Penilaian |
|---|---|
| UAS-IND-01 | Kontribusi coding. |
| UAS-IND-02 | Commit GitHub. |
| UAS-IND-03 | Presentasi individu. |
| UAS-IND-04 | Pemahaman kode. |

Contoh penyesuaian dari dokumen UAS:

- Anggota aktif penuh: 100% nilai kelompok.
- Kontribusi sedang: 85–90%.
- Kontribusi minim: 60–75%.

## 14. Requirement yang Tidak Ditetapkan UAS

Hal-hal berikut **tidak** diwajibkan oleh dokumen UAS dan merupakan keputusan proyek:

- Nama aplikasi (RentalGo), skema warna, dan desain UI spesifik.
- Framework frontend tertentu (UAS hanya mengizinkan Bootstrap/Tailwind/admin template).
- Skema tabel dan nama kolom.
- Peran Admin/Staff dan pembatasan akses detail.
- Aturan harga, asuransi, diskon, dan denda.
- Struktur folder atau pola service layer.
- Pembagian fase dan kepemilikan file per anggota.
- Penggunaan Git branch/PR (UAS hanya memberi bonus untuk commit GitHub yang aktif dan rapi).
- Penggunaan Docker (bonus opsional, tidak dipakai).
- REST API dan export PDF (bonus opsional).

## 15. Requirement yang Masih Perlu Konfirmasi

| No | Hal Tidak Jelas | Dampak | Default Proyek |
|---|---|---|---|
| 1 | Apakah UML harus berupa gambar atau boleh Mermaid di Markdown? | Format deliverable | Mermaid + opsi export PNG |
| 2 | Apakah MySQL wajib saat demo, atau SQLite diperbolehkan? | Konfigurasi database | MySQL utama; SQLite untuk test |
| 3 | Apakah dosen mengizinkan Laravel dengan custom auth (tanpa Breeze)? | Implementasi auth | Custom auth sederhana |
| 4 | Apakah akun pelanggan perlu login? | Scope auth/UI | Ya — role `customer` dan portal pelanggan ditambahkan |
| 5 | Bonus mana yang ingin dikerjakan? | Scope Phase 5 | REST API + PDF + commit rapi (direncanakan) |
| 6 | Nama/NIM anggota dan pembagian peran final? | Dokumentasi & penilaian individu | Sudah dikonfirmasi; lihat `README.md` dan `docs/PHASE_PLAN.md` |
| 7 | Kredensial MySQL lokal? | Setup Phase 1 | MySQL 9.1, `root`, password lokal sudah diisi, port 3306, database `rentalgo` |
| 8 | Format dan tanggal presentasi? | Phase 5 | Ditentukan kemudian |

## 16. Kesimpulan Ekstraksi

- Requirement eksplisit UAS seluruhnya tercakup dalam dokumen ini dengan ID yang dapat dilacak.
- Tidak ada requirement tambahan yang diklaim sebagai kewajiban UAS.
- Setiap requirement eksplisit memiliki baris traceability di `docs/UAS_TRACEABILITY.md`.
- Status implementasi tidak boleh dianggap selesai sampai diverifikasi pada fase terkait.
