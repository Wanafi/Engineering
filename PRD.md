# AI DEVELOPMENT GUIDE — ENGINEERING MANAGEMENT SYSTEM

> Panduan utama untuk AI coding assistant yang mengembangkan project ini.
> Baca file ini sebelum melakukan perubahan kode.

---

## 1. TUJUAN SISTEM

Project ini adalah **Engineering Management System** untuk membantu memonitor dan mengelola operasional Department Engineering.

Sistem dirancang untuk menangani:

- Struktur organisasi Engineering
- Division
- Unit / equipment
- Checklist maintenance
- Schedule
- Work Order
- Jadwal Kerja
- Purchasing Request
- Pengambilan Barang
- Berita Acara Kerusakan
- Berita Acara Penyelesaian Kerja
- Form Izin/Cuti/Tukar Off/Off MOD
- Rekapan Pengeluaran
- Maintenance
- Report
- CAPEX
- OPEX
- Approval
- Dashboard
- Notification / automation
- User & permission

Prioritas utama:

1. Stabilitas
2. Data integrity
3. Relasi database yang jelas
4. UX yang sederhana
5. Responsive / mobile friendly
6. Mudah dikembangkan
7. Tidak over-engineering

---

# 2. TECH STACK

Gunakan stack yang sudah ada di project.

Target utama:

- Laravel
- Filament v5
- Livewire sesuai kebutuhan project
- MySQL
- Filament Shield untuk authorization

**PENTING:**

Jangan mengasumsikan versi package lain.

Sebelum menggunakan API/framework tertentu, periksa:

- `composer.json`
- `package.json`
- existing implementation
- konfigurasi project

Gunakan API yang sesuai dengan versi yang benar-benar terinstall.

---

# 3. ATURAN UTAMA AI

## 3.1 Jangan langsung coding

Sebelum mengubah kode:

1. Scan struktur project.
2. Cari Model yang sudah ada.
3. Cari Migration.
4. Cari Resource.
5. Cari relationship.
6. Cari enum / constant.
7. Cari policy / permission.
8. Cari penggunaan class yang akan diubah.
9. Periksa package version.
10. Baru tentukan implementasi.

Jangan membuat file hanya karena "kemungkinan dibutuhkan".

---

## 3.2 Jangan merusak existing system

Existing code adalah source of truth.

Jika sebuah fitur sudah tersedia:

- gunakan
- extend
- refactor jika memang diperlukan

Jangan mengganti architecture tanpa alasan kuat.

Sebelum menghapus atau mengganti kode, cari seluruh referensinya.

---

## 3.3 Hindari duplicate

Sebelum membuat:

- Model
- Migration
- Resource
- Page
- Widget
- Enum
- Service
- Policy
- Component

pastikan file/class tersebut belum ada.

Cari menggunakan nama class, model, table, dan route.

---

## 3.4 Jangan over-engineering

Gunakan solusi paling sederhana yang memenuhi requirement.

Contoh:

Jika hanya membutuhkan status Unit, jangan membuat tabel `unit_statuses` kecuali memang ada kebutuhan relasional.

Jika sebuah data hanya atribut sederhana, gunakan column biasa.

Tambahkan tabel baru hanya jika memang mempunyai lifecycle, relationship, atau kebutuhan query yang jelas.

---

# 4. ARCHITECTURE DATABASE

Struktur utama organisasi:

```text
Department
    |
    └── Division
            |
            └── Unit
```

Relationship:

```text
Division 1 ---- N Unit
```

## IMPORTANT

**Unit hanya terhubung langsung dengan Division.**

Jangan membuat:

```text
Unit -> Department
```

jika tidak ada requirement yang jelas.

Department dapat diketahui melalui Division apabila relationship tersebut memang tersedia.

---

# 5. DIVISION

Division merupakan bagian dari Department Engineering.

Contoh:

```text
Escalator & Lift
HVAC
Electrical
Plumbing
Civil
```

Relationship:

```text
Department
    |
    └── Division
```

Division dapat mempunyai banyak Unit.

```text
Division 1 ---- N Unit
```

---

# 6. UNIT

Unit adalah salah satu master data utama.

Contoh:

```text
Escalator 01
Escalator 02
Lift 01
AHU 01
Chiller 01
```

Struktur minimal yang dapat dipertimbangkan:

```text
id
division_id
name
code
location
description
status
created_at
updated_at
```

**Jangan menambahkan field tanpa kebutuhan.**

Relationship:

```php
Unit belongsTo Division
Division hasMany Units
```

Unit harus:

- memiliki Division
- dapat dicari
- dapat difilter berdasarkan Division
- memiliki status yang konsisten
- mempunyai validation yang baik

Jika `code` dibuat unique, pastikan unique tersebut tidak mengganggu proses edit/update.

---

# 7. ASSET / EQUIPMENT

Sebelum membuat Asset, AI wajib menentukan apakah Unit sudah berfungsi sebagai equipment utama.

Contoh:

```text
Division: Escalator & Lift
    |
    ├── Unit: Escalator 01
    └── Unit: Escalator 02
```

Jika Unit sudah merepresentasikan equipment utama, jangan membuat Asset yang hanya menduplikasi Unit.

Jika Asset memang diperlukan:

```text
Division
    |
    └── Unit
          |
          └── Asset
```

Gunakan relationship yang jelas dan hindari duplikasi data.

---

# 8. CHECKLIST

Checklist digunakan untuk pemeriksaan maintenance.

Konsep:

```text
Checklist
    |
    └── Checklist Questions
            |
            └── Answers
```

Tipe pertanyaan yang dapat digunakan:

```text
condition
yes_no
text
number
photo
```

Checklist dapat dikaitkan dengan:

- Division
- Unit
- User / Technician
- Schedule

Jika struktur checklist sudah tersedia di project, pertahankan architecture yang ada.

---

# 9. SCHEDULE

Schedule digunakan untuk mengatur pekerjaan maintenance.

Konsep:

```text
Schedule
    |
    ├── Division
    ├── Unit
    ├── Assigned User
    ├── Date
    ├── Time
    └── Status
```

Status dapat berupa:

```text
scheduled
in_progress
completed
cancelled
overdue
```

Namun gunakan status existing jika project sudah mempunyai standard sendiri.

Schedule nantinya dapat digunakan untuk:

- Calendar
- Checklist
- Work Order
- Reminder
- Dashboard

---

# 10. WORK ORDER

Work Order digunakan untuk mengelola pekerjaan engineering.

Konsep:

```text
Unit
    |
    └── Work Order
            |
            └── Checklist / Maintenance
```

Field yang dapat dipertimbangkan:

```text
wo_number
unit_id
division_id
reported_by
assigned_to
priority
type
description
scheduled_date
started_at
completed_at
status
notes
```

Tambahkan attachment/photo hanya jika memang dibutuhkan.

---

# 11. APPROVAL

Flow approval yang dapat digunakan:

```text
Technician
    |
    v
Submit
    |
    v
Supervisor Approval
    |
    v
Head Approval
    |
    v
Completed
```

Jika project sudah memiliki:

```text
supervisor_approved_by
head_approved_by
```

gunakan field tersebut.

Jangan membuat field status tambahan hanya untuk menggantikan status yang sebenarnya dapat dihitung dari approval existing.

---

# 12. FILAMENT V5

Project menggunakan **Filament v5**.

AI wajib mengikuti API Filament v5 yang benar.

Jangan copy-paste implementation dari Filament versi lama tanpa validasi.

Perhatikan terutama:

- Forms
- Schemas
- Tables
- Actions
- Filters
- Resources
- Pages
- Widgets
- Navigation

Jika menemukan error type mismatch seperti:

```text
Form expected
Schema returned
```

atau namespace Filament lama, periksa API versi project sebelum memperbaiki.

---

# 13. FILAMENT RESOURCE STANDARD

Setiap Resource sebaiknya mempunyai:

```text
Resource
├── Form / Schema
├── Table
├── Relations jika diperlukan
├── Pages
└── Authorization
```

## Form

Gunakan grouping yang jelas:

```text
General Information
Relationship
Status
Additional Information
```

Gunakan:

- Section
- Grid
- Tabs
- Select
- TextInput
- Textarea
- DatePicker
- DateTimePicker
- Toggle
- FileUpload

hanya jika relevan.

---

## Table

Gunakan:

- searchable
- sortable
- filters
- badge
- relationship columns
- appropriate actions

Jangan menampilkan terlalu banyak column.

Prioritaskan informasi yang paling penting.

---

# 14. RESPONSIVE UI

UI harus nyaman digunakan:

- Desktop
- Laptop
- Tablet
- Mobile

Gunakan layout yang responsif.

Hindari:

- tabel terlalu lebar
- form satu kolom panjang tanpa grouping
- terlalu banyak informasi dalam satu card
- action yang sulit ditemukan

Prioritas UX:

```text
Simple > Fancy
Clear > Crowded
Useful > Decorative
```

---

# 15. NAVIGATION

Gunakan navigation group yang logis.

Struktur yang direkomendasikan:

```text
MASTER DATA
├── Division
├── Unit
└── Asset

OPERATIONS
├── Schedule
├── Checklist
└── Work Order

MAINTENANCE
├── Maintenance
└── Reports

FINANCE
├── CAPEX
└── OPEX

SYSTEM
├── Users
├── Roles
└── Activity Log
```

Jangan menambahkan navigation untuk Resource yang belum tersedia.

Urutan navigation harus konsisten.

---

# 16. FILAMENT SHIELD

Authorization menggunakan **Filament Shield**.

Role yang digunakan / direncanakan:

```text
Admin
Supervisor
Teknisi
Viewer
Asst. Head of Engineering
```

Jangan membuat sistem role custom jika kebutuhan sudah dapat dipenuhi oleh Shield.

Setiap Resource baru harus mempertimbangkan:

- view
- viewAny
- create
- update
- delete

sesuai kebutuhan role.

---

# 17. DATA INTEGRITY

Perhatikan foreign key.

Contoh:

```text
units.division_id
        |
        v
divisions.id
```

Pastikan migration dijalankan dalam urutan yang benar.

Sebelum membuat foreign key:

1. Pastikan tabel parent sudah ada.
2. Pastikan nama tabel benar.
3. Pastikan tipe column sama.
4. Pastikan nullable sesuai kebutuhan.
5. Tentukan behavior delete dengan sengaja.

Jangan asal menggunakan:

```text
cascade
set null
restrict
```

Pilih berdasarkan business rule.

---

# 18. MIGRATION RULE

Migration harus:

- atomic
- jelas
- mengikuti naming convention
- tidak duplicate
- memiliki foreign key yang benar

Sebelum migration baru:

```bash
php artisan migrate:status
```

Periksa migration existing.

Jangan membuat migration baru jika migration yang diperlukan sudah ada dan belum dijalankan, kecuali memang diperlukan oleh workflow project.

---

# 19. MODEL RULE

Model harus mempunyai:

- `$fillable` / guarded strategy yang konsisten
- relationship
- casts jika diperlukan
- scopes jika memang diperlukan

Contoh:

```php
public function division()
{
    return $this->belongsTo(Division::class);
}
```

Gunakan relationship Eloquent daripada query manual jika relationship memang tersedia.

---

# 20. VALIDATION

Validation harus mempertimbangkan:

- required
- nullable
- unique
- exists
- format
- minimum / maximum
- update scenario

Jangan hanya memvalidasi pada frontend.

Database constraint tetap penting.

---

# 21. DASHBOARD

Dashboard utama nantinya dapat menampilkan:

```text
Total Division
Total Unit
Total Asset

Schedule Today
Schedule This Month

Checklist Pending
Checklist Completed

Work Order Open
Work Order In Progress
Work Order Completed
Work Order Overdue

Maintenance Summary
```

Dashboard harus membaca data real dari database.

Jangan menggunakan dummy data setelah data production tersedia.

---

# 22. NOTIFICATION & AUTOMATION

Automation dapat digunakan untuk:

- reminder H-1
- overdue schedule
- checklist pending
- approval pending
- work order overdue

Namun automation jangan dibuat sebelum core workflow stabil.

Urutan:

```text
Database
    ↓
CRUD
    ↓
Relationship
    ↓
Workflow
    ↓
Approval
    ↓
Automation
```

---

# 23. DEVELOPMENT ROADMAP

Gunakan roadmap berikut sebagai baseline:

```text
[x] Department
[x] Division
[x] Unit
[ ] Checklist
[ ] Schedule
[ ] Work Order
[ ] Approval
[ ] Maintenance
[ ] Reports
[ ] CAPEX
[ ] OPEX
[ ] Dashboard
[ ] Notification
[ ] Automation
```

Status roadmap harus mengikuti kondisi project sebenarnya.

Jangan menganggap `[x]` berarti implementasinya sempurna.

Audit kode terlebih dahulu.

---

# 24. PRIORITAS DEVELOPMENT

Jika tidak ada instruksi khusus, gunakan urutan:

```text
1. Master Data
2. Unit
3. Asset / Equipment
4. Checklist
5. Schedule
6. Work Order
7. Approval
8. Maintenance
9. Reports
10. CAPEX / OPEX
11. Dashboard
12. Notification
13. Automation
```

Selesaikan satu modul sampai stabil sebelum pindah ke modul berikutnya.

---

# 25. DEBUGGING

Jika terjadi error:

### Langkah 1

Baca error lengkap.

### Langkah 2

Cari file dan line yang bermasalah.

### Langkah 3

Cari class / method yang terkait.

### Langkah 4

Periksa versi dependency.

### Langkah 5

Cari apakah ada implementation lama yang masih digunakan.

### Langkah 6

Perbaiki root cause.

Jangan menambal error dengan workaround yang membuat architecture semakin buruk.

---

# 26. COMMAND VERIFICATION

Setelah perubahan, gunakan command yang relevan:

```bash
php artisan optimize:clear
php artisan migrate:status
php artisan route:list
php artisan about
```

Jika migration baru dibuat:

```bash
php artisan migrate
```

Gunakan command Filament yang tersedia pada versi project.

Jangan menjalankan command destruktif seperti:

```bash
migrate:fresh
db:wipe
```

tanpa instruksi eksplisit.

---

# 27. TESTING CHECKLIST

Sebelum menyatakan sebuah modul selesai:

```text
[ ] Migration valid
[ ] Model valid
[ ] Relationship valid
[ ] Resource valid
[ ] Form valid
[ ] Table valid
[ ] Filter valid
[ ] Validation valid
[ ] Authorization valid
[ ] Navigation valid
[ ] Route valid
[ ] No duplicate class
[ ] No duplicate migration
[ ] No namespace error
[ ] No deprecated API
[ ] Responsive UI
```

---

# 28. CARA AI BEKERJA

Gunakan workflow:

```text
ANALYZE
   ↓
AUDIT
   ↓
UNDERSTAND
   ↓
PLAN
   ↓
IMPLEMENT
   ↓
VERIFY
   ↓
FIX
   ↓
REPORT
```

## ANALYZE

Pahami requirement.

## AUDIT

Periksa existing project.

## UNDERSTAND

Pahami relationship dan architecture.

## PLAN

Tentukan file yang perlu dibuat/diubah.

## IMPLEMENT

Implementasikan perubahan minimal.

## VERIFY

Periksa syntax, migration, route, relationship, permission.

## FIX

Perbaiki error yang ditemukan.

## REPORT

Laporkan:

```text
Changed:
- ...

Created:
- ...

Modified:
- ...

Database:
- ...

Next:
- ...
```

---

# 29. ATURAN SAAT MENERIMA TASK BARU

Jika user memberikan task:

### Jangan:

```text
langsung membuat 10 file
```

### Lakukan:

```text
1. pahami task
2. audit existing implementation
3. identifikasi dependency
4. tentukan perubahan minimum
5. implementasi
6. verify
```

Jika requirement ambigu dan dapat menyebabkan perubahan database besar, minta klarifikasi.

Jika requirement cukup jelas, jangan bertanya hal yang tidak perlu.

---

# 30. PRIORITAS SOURCE OF TRUTH

Jika terjadi konflik informasi, gunakan prioritas:

```text
1. Existing database
2. Existing code
3. Existing migrations
4. Existing business logic
5. User requirement terbaru
6. Dokumentasi project
7. AI assumption
```

**AI tidak boleh mengalahkan existing business logic hanya berdasarkan asumsi.**

---

# 31. JANGAN MELAKUKAN INI

Jangan:

- membuat duplicate Model
- membuat duplicate Resource
- membuat duplicate migration
- mengganti architecture tanpa alasan
- menggunakan API Filament versi lama
- membuat role system baru
- membuat tabel hanya untuk field sederhana
- menambahkan field tanpa requirement
- menjalankan `migrate:fresh` sembarangan
- menghapus data
- menggunakan dummy data sebagai solusi permanen
- membuat dashboard sebelum workflow inti stabil
- membuat automation sebelum workflow stabil
- memperbaiki error hanya dengan suppress warning
- mengubah banyak modul sekaligus tanpa dependency yang jelas

---

# 32. CURRENT PROJECT CONTEXT

Saat ini project development sudah mencapai:

```text
Department
    ↓
Division
    ↓
Unit
```

**Unit adalah titik development saat ini.**

Sebelum melanjutkan:

1. Audit Department.
2. Audit Division.
3. Audit Unit.
4. Validasi relationship.
5. Validasi migration.
6. Validasi Filament Resource.
7. Tentukan modul berikutnya berdasarkan dependency.

Jika Unit sudah stabil, kandidat modul berikutnya:

```text
Asset / Equipment
```

atau

```text
Checklist
```

Pilih berdasarkan hasil audit.

---

# 33. OUTPUT AI SETIAP SELESAI TASK

Berikan ringkasan:

## Audit

Apa yang ditemukan.

## Changes

File yang dibuat / diubah.

## Database

Migration atau perubahan schema.

## Relationship

Relationship yang ditambahkan / diperbaiki.

## Filament

Resource, form, table, filter, action.

## Permission

Permission / Shield yang terpengaruh.

## Verification

Test / command yang dijalankan.

## Remaining Issues

Jika masih ada masalah, jelaskan secara spesifik.

## Next Recommended Step

Berikan satu langkah berikutnya yang paling logis.

---

# 34. GOLDEN RULE

> **Jangan membangun sistem dari asumsi. Bangun berdasarkan kode, database, requirement, dan business flow yang benar-benar ada.**

Dan:

> **Stabil dulu, baru kompleks.**

Project ini harus berkembang secara bertahap, bukan sekaligus.

