# Progress Reports

Direktori ini menyimpan laporan penyelesaian setiap fase.

## Aturan

- Nama file: `phase-XX-nama-singkat.md` (contoh: `phase-01-foundation.md`).
- Gunakan template di `docs/templates/PHASE_REPORT_TEMPLATE.md`.
- Isi hanya pekerjaan yang sudah benar-benar dilakukan dan diverifikasi.
- Jangan membuat laporan fiktif atau menyalin laporan fase lain.
- Perbarui `docs/PROJECT_STATUS.md`, `docs/UAS_TRACEABILITY.md`, dan
  `docs/CONTRIBUTION_LOG.md` pada PR yang sama.
- Sertakan hasil test nyata dan screenshot untuk perubahan UI.

## Daftar Laporan

| Fase | File | Status |
|---|---|---|
| Phase 0 | Belum ada (dokumentasi Phase 0 ada di root dan `docs/`) | — |
| Phase 1 | [`phase-01-foundation.md`](phase-01-foundation.md) | Merged (PR #1) |
| Phase 2 | [`phase-02-auth-dashboard.md`](phase-02-auth-dashboard.md) | Merged (PR #2) |
| Phase 3 | [`phase-03-master-data.md`](phase-03-master-data.md) | Merged (PR #3, `9a738fb`) |
| Phase 4 | [`phase-04-rental-payment.md`](phase-04-rental-payment.md) | PR #4 — menunggu review |
| Phase 5 | `phase-05-integration.md` | Belum dibuat |

Catatan: dokumen Phase 0 bukan laporan penyelesaian implementasi; Phase 0 hanya dokumentasi.
Laporan fase implementasi baru dibuat setelah fase tersebut dikerjakan. Kontribusi tidak dikunci
per phase secara kaku: siapa saja boleh berkontribusi pada phase mana saja selama histori commit
tercatat pada email Git masing-masing, lalu direkonsiliasi di `docs/CONTRIBUTION_LOG.md` pada Phase 5.
