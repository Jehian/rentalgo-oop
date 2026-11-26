# AI Phase Prompt Template

Template ini dipakai untuk memberikan tugas satu fase kepada AI agent. Ganti placeholder
`{{...}}` dan hapus bagian yang tidak relevan.

---

## 1. Peran dan Konteks

Kamu bekerja pada repository **RentalGo**, aplikasi rental kendaraan untuk UAS OOP. Baca dokumen
berikut sebelum mengubah kode:

1. `docs/UAS_REQUIREMENTS.md`
2. `PRD.md`
3. `docs/UAS_TRACEABILITY.md`
4. `docs/ARCHITECTURE.md`
5. `docs/DATABASE_SCHEMA.md`
6. `docs/DECISIONS.md`
7. `docs/PHASE_PLAN.md`
8. `AGENTS.md`
9. `docs/PROJECT_STATUS.md`
10. `docs/progress/` (laporan fase sebelumnya)

Dokumen UAS di `docs/reference/` adalah sumber kebenaran utama dan tidak boleh diubah.

## 2. Status Saat Ini

- Fase sebelumnya: {{PHASE_PREVIOUS}} — {{STATUS}}
- Branch kerja: `{{BRANCH}}`
- Pelaksana: {{CONTRIBUTORS}} (siapa saja boleh mengerjakan selama commit memakai email Git sendiri)
- Dependency yang sudah tersedia: {{DEPENDENCIES}}
- Yang belum tersedia: {{NOT_AVAILABLE}}

## 3. Tujuan Fase

**Phase {{PHASE_NUMBER}} — {{PHASE_NAME}}**

{{GOAL_DESCRIPTION}}

## 4. Scope

### In Scope

- {{ITEM_1}}
- {{ITEM_2}}

### Out of Scope

- {{OUT_1}}
- {{OUT_2}}

## 5. File yang Boleh Diubah

- {{ALLOWED_PATHS}}

## 6. File yang Tidak Boleh Diubah

- `docs/reference/**`
- Migration/model/seeder (kecuali sudah dikoordinasikan agar tidak konflik)
- File modul lain tanpa koordinasi: {{PROTECTED_PATHS}}
- `.env` atau secret

## 7. Requirement dan Acceptance Criteria

| ID | Requirement | Acceptance |
|---|---|---|
| {{UAS_ID}} | {{REQUIREMENT}} | {{ACCEPTANCE}} |

- [ ] {{ACCEPTANCE_1}}
- [ ] {{ACCEPTANCE_2}}
- [ ] Test relevan ditambahkan.
- [ ] `php artisan test` lulus.
- [ ] `vendor/bin/pint --test` lulus.
- [ ] Dokumentasi fase diperbarui.

## 8. Batasan Teknis

- Laravel 13, PHP 8.3+, Blade, Tailwind 4, MySQL/SQLite.
- Ikuti `AGENTS.md`.
- Jangan menambah dependency tanpa persetujuan.
- Jangan memakai Docker.
- Jangan mengubah schema di luar fase ini.
- Jangan menduplikasi logika domain yang sudah ada.

## 9. Verifikasi yang Harus Dijalankan

```bash
php artisan test
vendor/bin/pint --test
```

Manual check:

- {{MANUAL_CHECK_1}}
- {{MANUAL_CHECK_2}}

## 10. Dokumentasi yang Wajib Diperbarui

- [ ] `docs/progress/phase-{{PHASE_NUMBER}}-{{SLUG}}.md` (dari template)
- [ ] `docs/PROJECT_STATUS.md`
- [ ] `docs/UAS_TRACEABILITY.md`
- [ ] `docs/CONTRIBUTION_LOG.md` (catat task/commit; finalisasi di Phase 5)
- [ ] `README.md` jika setup/fitur berubah
- [ ] `docs/DECISIONS.md` jika ada keputusan baru

## 11. Aturan Git

- Jangan bekerja di `main`.
- Jangan commit/push/PR kecuali manusia meminta secara eksplisit.
- Jika diminta commit, gunakan Conventional Commits dan jangan commit secret.
- Commit memakai identitas orang yang menjalankan commit; kontribusi task dicatat di
  `docs/CONTRIBUTION_LOG.md`.
- PR harus menyertakan ringkasan, file, verifikasi, dan screenshot (jika UI).

## 12. Format Laporan Akhir

Laporkan:

1. Ringkasan pekerjaan.
2. File yang dibuat/diubah.
3. Hasil test dan Pint (nyata).
4. Requirement yang terpenuhi dan buktinya.
5. Deviasi/asumsi.
6. Yang belum dikerjakan.
7. Risiko/handoff.

## 13. Jika Ada Ambiguitas

Jangan mengarang requirement. Catat pertanyaan, gunakan default di `docs/DECISIONS.md`, dan tandai
asumsi. Jika keputusan mengubah scope/schema, berhenti dan minta konfirmasi manusia.

---

### Contoh Pengisian Singkat

> Phase 2 — Auth, Layout & Dashboard. In scope: login/logout/session, middleware role, layout
> responsif, dashboard cards + Chart.js. Out of scope: CRUD pelanggan/kendaraan, rental. File:
> `app/Http/Controllers/Auth/*`, `app/Http/Controllers/DashboardController.php`,
> `resources/views/layouts/*`, `resources/views/dashboard/*`, `routes/auth.php`,
> `routes/dashboard.php`. Acceptance: feature test login/logout/proteksi; dashboard menampilkan
> angka dari seeder; layout responsive.
