# Phase Report Template

> Salin template ini menjadi `docs/progress/phase-XX-nama.md`. Isi hanya fakta yang sudah
> diverifikasi. Jangan mengklaim fitur selesai tanpa bukti.

| Item | Keterangan |
|---|---|
| Fase | Phase XX — Nama Fase |
| Pelaksana commit | Anggota X (`email Git`), Anggota Y (`email Git`) |
| Reviewer / Merge | Anggota Z |
| Branch | `phase/XX-nama` |
| Pull Request | #XX / link |
| Tanggal mulai | YYYY-MM-DD |
| Tanggal selesai | YYYY-MM-DD |
| Status | Draft / In Review / Merged |
| Reviewer | Anggota Y |

## 1. Ringkasan

Jelaskan dalam 2–4 kalimat: apa yang dikerjakan, mengapa, dan hasil utamanya.

## 2. Tujuan vs Hasil

| Tujuan | Hasil | Bukti |
|---|---|---|
| ... | Tercapai / Sebagian / Tidak | File/test/link |

## 3. Scope yang Diselesaikan

- [ ] Item 1
- [ ] Item 2

## 4. File yang Dibuat/Diubah

| File | Jenis | Keterangan |
|---|---|---|
| `path/file` | Baru/Diubah | ... |

## 5. Perubahan Database

| Tabel/Kolom | Perubahan | Migration |
|---|---|---|
| ... | ... | `..._create_xxx_table.php` |

Jika tidak ada perubahan database, tulis: **Tidak ada.**

## 6. Pemetaan OOP dan UAS

| Requirement | Implementasi | File | Verifikasi |
|---|---|---|---|
| UAS-OOP-XX | ... | `path` | Test/review |
| FR-XXX | ... | `path` | Test/manual |

## 7. Pengujian dan Verifikasi

### 7.1 Perintah

```bash
php artisan test
vendor/bin/pint --test
```

### 7.2 Hasil

Tempel ringkasan hasil nyata (jumlah test, status, error jika ada).

```text
Tests: XX passed
```

### 7.3 Manual Check

| Skenario | Langkah | Hasil |
|---|---|---|
| ... | ... | Lulus/Gagal |

## 8. Screenshot (Jika Ada UI)

| Halaman | Desktop | Mobile |
|---|---|---|
| ... | `docs/assets/...png` | `docs/assets/...png` |

## 9. Masalah, Deviasi, dan Keterbatasan

- Masalah yang ditemukan:
- Deviasi dari rencana:
- Keterbatasan yang diketahui:

## 10. Handoff ke Fase Berikutnya

- Apa yang sudah stabil:
- Apa yang perlu dilanjutkan:
- File/kontrak yang harus dipakai:
- Peringatan/risiko:

## 11. Checklist

- [ ] Scope selesai.
- [ ] Test ditambahkan dan lulus.
- [ ] Pint lulus.
- [ ] Tidak ada secret.
- [ ] Dokumentasi status/traceability/contribution log diperbarui.
- [ ] Screenshot disertakan (jika UI).
- [ ] PR direview.
- [ ] Merge selesai.

## 12. Catatan Kontribusi

| Task | Kontributor (Author Email / Pelaksana) | Commit/PR | Bukti |
|---|---|---|---|
| ... | Nama (`email@domain`) | ... | ... |

> Kontribusi tidak dikunci per phase secara kaku; pencatatan mengikuti histori commit pada email
> Git masing-masing anggota dan direkonsiliasi di `docs/CONTRIBUTION_LOG.md` pada Phase 5.
