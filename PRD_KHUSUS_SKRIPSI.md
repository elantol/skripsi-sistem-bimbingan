# DOKUMEN PRD KHUSUS (PRODUCT REQUIREMENT DOCUMENT)

**NAMA SISTEM:** SkripsiGuidance  
**INSTITUSI:** Universitas Malikussaleh (UNIMAL) — Jurusan Teknik Informatika  
**VERSI SISTEM:** v1.0.0 (Production-Ready)  
**STATUS SPESIFIKASI:** Approved & Fully Implemented  

---

## DAFTAR ISI PRD
1. [1. EXECUTIVE SUMMARY & ISI RINGKAS PRODUK](#1-executive-summary--isi-ringkas-produk)
2. [2. USER PERSONAS & MATRIKS DUTA PERAN (ROLE DUTY SEPARATION)](#2-user-personas--matriks-duta-peran-role-duty-separation)
3. [3. FUNCTIONAL REQUIREMENTS (FR-01 S/D FR-22)](#3-functional-requirements-fr-01-sd-fr-22)
   - 3.1 Modul Security & Autentikasi Dual-Portal UNIMAL
   - 3.2 Modul Portal Mahasiswa (Tambah File & Tracker Revisi)
   - 3.3 Modul Portal Dosen Pembimbing & Penguji (Pemeriksaan & Rubrik Sidang)
   - 3.4 Modul Portal Administrator (Master Data, C4.5 Engine, & BAP PDF)
   - 3.5 Modul Notifikasi & Otomasi Sistem
4. [4. NON-FUNCTIONAL REQUIREMENTS (NFR-01 S/D NFR-08)](#4-non-functional-requirements-nfr-01-sd-nfr-08)
5. [5. SPESIFIKASI ENGINE MACHINE LEARNING C4.5](#5-spesifikasi-engine-machine-learning-c45)
6. [6. SKEMA TEKNIS DATABASE 20 TABEL](#6-skema-teknis-database-20-tabel)
7. [7. ACCEPTANCE CRITERIA & PANDUAN DEPLOYMENT LOKAL](#7-acceptance-criteria--panduan-deployment-lokal)

---

## 1. EXECUTIVE SUMMARY & ISI RINGKAS PRODUK

### 1.1 Visi Produk
Membuat platform pengelolaan bimbingan skripsi digital modern berstandar enterprise untuk **Universitas Malikussaleh (UNIMAL)** yang mengintegrasikan alur bimbingan naskah, pelacakan revisi berkas terstruktur ($v1, v2, v3$), prediksi kecenderungan kelulusan tepat waktu mahasiswa menggunakan **Algoritma Machine Learning Decision Tree C4.5**, serta digitalisasi sidang skripsi hingga penerbitan Berita Acara Sidang (BAP) PDF.

### 1.2 Masalah Utama & Solusi Perangkat Lunak
- **Masalah 1 (Pencatatan Sporadis)**: Naskah fisik hilang dan catatan revisi tidak terarsip.
  - *Solusi*: Fitur *Versioning* Otomatis ($v1, v2, v3$) dan *Tracker Status Tahapan Skripsi*.
- **Masalah 2 (Keterlambatan Deteksi Mahasiswa Terhambat)**: Program studi tidak memiliki alat ukur prediktif.
  - *Solusi*: Mesin prediktif *Decision Tree C4.5* berbasis 50 sampel dataset dengan akurasi 100% dan visualisasi *Confusion Matrix 3x3*.
- **Masalah 3 (Administrasi Sidang Lambat)**: Pembuatan Berita Acara Sidang manual rentan kesalahan hitung.
  - *Solusi*: Modul Rubrik Penilaian Digital Penguji & *Generator BAP PDF Otomatis*.

---

## 2. USER PERSONAS & MATRIKS DUTA PERAN (ROLE DUTY SEPARATION)

| Persona / Role | Pengguna Target | Utama Tugas & Batasan Hak Akses (*Role Duty Separation*) |
| :--- | :--- | :--- |
| **Mahasiswa** | Mahasiswa Tingkat Akhir Jurusan Teknik Informatika UNIMAL | **Fokus**: Menginput judul skripsi, mengunggah berkas naskah per bab ($v1, v2, v3$), memantau status revisi tahapan (Sudah Di-revisi / Belum Di-revisi / ACC), dan mendaftar sidang. <br>*Batasan*: Dilarang mengubah status revisi, menginput nilai, atau mengakses modul Admin. |
| **Dosen Pembimbing & Penguji** | Dosen Tetap / Pembimbing Skripsi UNIMAL | **Fokus**: Memeriksa naskah mahasiswa bimbingan, mendownload berkas revisi $v1, v2, v3$, menginput catatan revisi, memperbarui status review (`PERLU_REVISI`/`DISETUJUI`), dan menginput nilai rubrik sidang. <br>*Batasan*: Dilarang mengubah skema C4.5 atau mengedit akun pengguna lain. |
| **Administrator** | Operator Prodi / Ketua Jurusan Teknik Informatika UNIMAL | **Fokus**: Mengelola Master Data, penetapan pembimbing, monitoring seluruh bimbingan tingkat jurusan, menguji data C4.5, plotting penguji sidang, dan mencetak Berita Acara Sidang (BAP) PDF. |

---

## 3. FUNCTIONAL REQUIREMENTS (FR-01 S/D FR-22)

### 3.1 Modul Security & Autentikasi Dual-Portal UNIMAL

```mermaid
flowchart TD
    A[Buka Halaman /login] --> B{Sesi Aktif?}
    B -->|Ya| C[Tampilkan Banner Aktif + Tombol Logout 1-Click]
    B -->|Tidak| D[Tampilkan Tampilan Login UNIMAL 3D SVG]
    D --> E[Pilih Portal: Mahasiswa atau Admin/Dosen]
    E --> F[Submit Credentials / Auto-Login Demo]
    F --> G{Otorisasi Middleware EnsureRole}
    G -->|Mahasiswa| H[Redirect /student/dashboard]
    G -->|Dosen| I[Redirect /lecturer/dashboard]
    G -->|Admin| J[Redirect /admin/dashboard]
```

- **FR-01**: Sistem harus menyediakan halaman login bertema **Universitas Malikussaleh (UNIMAL)** dengan grafik 3D SVG, perisai lambang 3D UNIMAL, serta tab selector **[🎓 Portal Mahasiswa UNIMAL]** dan **[👨‍🏫 Portal Admin/Dosen UNIMAL]**.
- **FR-02**: Sistem harus menyediakan tombol **Quick Demo Login Presets** 1x klik untuk pengujian instan: Mahasiswa (`mahasiswa1@skripsi.ac.id`), Dosen (`dosen1@skripsi.ac.id`), dan Admin (`admin@skripsi.ac.id`).
- **FR-03**: Sistem harus mendeteksi sesi login aktif dan menampilkan banner peringatan akun aktif beserta tombol **`[ Keluar / Logout ]`** 1x klik untuk mencegah bentrok akun.
- **FR-04**: Middleware `EnsureRole` wajib memvalidasi role akun pada setiap request URL backend dan menolak secara otomatis (HTTP 403) jika role tidak memiliki hak akses.

---

### 3.2 Modul Portal Mahasiswa (`/student/dashboard`)

- **FR-05 (Input Judul)**: Mahasiswa dapat menginput dan mengedit judul skripsi aktif yang sedang ditempuh.
- **FR-06 (Upload Berkas)**: Mahasiswa dapat mengunggah berkas naskah (.PDF, .DOC, .DOCX maksimal 10MB) per kategori pengajuan: *Proposal, Bab 1, Bab 2, Bab 3, Bab 4, Bab 5, Laporan, atau Revisi*.
- **FR-07 (Document Versioning)**: Setiap unggahan revisi baru otomatis mencatat nomor versi ($v1, v2, v3, \dots$) tanpa menghapus berkas lama.
- **FR-08 (Interactive Revision Tracker)**: Menampilkan widget reaktif 7 tahapan skripsi dengan badge status:
  - 🟢 **DISETUJUI / ACC**: Naskah diterima dan disetujui dosen.
  - 🟡 ⚠️ **PERLU REVISI**: Di-revisi dosen + **Quote Box Catatan Revisi Dosen** + Tombol langsung *Upload Revisi Baru*.
  - 🔵 ⏳ **BELUM DI-REVISI (DIPERIKSA)**: Berkas baru diunggah, dalam antrean dosen.
  - ⚪ **BELUM UNGGAH**: Mahasiswa belum mengunggah file.
- **FR-09 (Pendaftaran Sidang)**: Mahasiswa dapat mengajukan pendaftaran sidang skripsi setelah seluruh tahapan Bab 1-5 disetujui (ACC).

---

### 3.3 Modul Portal Dosen Pembimbing & Penguji (`/lecturer/dashboard`)

- **FR-10 (Monitoring Bimbingan)**: Dosen dapat melihat daftar mahasiswa yang dibimbing (Pembimbing 1 & 2) beserta progres kemajuannya.
- **FR-11 (Workspace Pemeriksaan)**: Dosen dapat membuka pengajuan bimbingan masuk, membaca deskripsi mahasiswa, dan mendownload versi naskah ($v1, v2, v3$).
- **FR-12 (Input Catatan & Status)**: Dosen dapat menginput catatan/feedback revisi dan memperbarui status pengajuan menjadi **`PERLU_REVISI`** atau **`DISETUJUI (ACC)`**.
- **FR-13 (Penjadwalan Tatap Muka)**: Dosen dapat menetapkan tanggal, waktu, dan lokasi bimbingan langsung/online.
- **FR-14 (Rubrik Penilaian Sidang Digital)**: Dosen Penguji 1 & 2 menginput skor penilaian digital (*Presentasi, Substansi, Penguasaan Jawaban*) yang dikalkulasi otomatis oleh sistem.

---

### 3.4 Modul Portal Administrator (`/admin/dashboard`)

- **FR-15 (Master Data Management)**: Admin dapat mengelola CRUD Data Mahasiswa, Data Dosen, Periode Akademik (Ganjil/Genap Aktif), dan Penetapan Pembimbing 1 & 2.
- **FR-16 (Monitoring Seluruh Bimbingan Jurusan)**: Admin dapat memantau seluruh berkas bimbingan di `/admin/guidance` dengan filter tab status `PERLU_REVISI`, `DISETUJUI`, dan `MENUNGGU_REVIEW`.
- **FR-17 (Machine Learning C4.5 Engine)**: Admin dapat mengimpor 50 dataset histori bimbingan, mengeksekusi algoritma C4.5, dan melihat hasil klasifikasi kelulusan (`LANCAR`, `PERLU_PERHATIAN`, `TERLAMBAT`).
- **FR-18 (Visualisasi Analytics C4.5)**: Sistem harus merender visual **Mermaid.js Flowchart SVG Pohon Keputusan C4.5** serta grafik **Chart.js Bar & Doughnut Chart**.
- **FR-19 (Plotting Sidang & Generator BAP PDF)**: Admin dapat melakukan plotting Penguji 1 & 2, mengonfirmasi jadwal sidang, dan mencetak **Berita Acara Sidang Skripsi (BAP) PDF Resmi** terautentikasi.

---

### 3.5 Modul Notifikasi & Otomasi Sistem

- **FR-20 (App Notifications)**: Sistem mengirim notifikasi internal otomatis saat pengajuan dibuat, revisi diunggah, atau status bimbingan diperbarui.
- **FR-21 (Auto Progress Calculation)**: Sistem mengkalkulasi persentase progres kemajuan skripsi (% Auto Progress) secara otomatis berdasarkan rasio tahapan yang disetujui.
- **FR-22 (Secure File Download)**: Mengunduh berkas bimbingan hanya melalui `DocumentController` terotentikasi.

---

## 4. NON-FUNCTIONAL REQUIREMENTS (NFR-01 S/D NFR-08)

| ID NFR | Kategori | Spesifikasi & Standar Kualitas |
| :--- | :--- | :--- |
| **NFR-01** | **Security & Isolation** | Berkas naskah disekat di `storage/app/private/thesis/` (tidak dapat diakses langsung via URL publik web). |
| **NFR-02** | **Password Security** | Password disimpan menggunakan enkripsi hashing Bcrypt. |
| **NFR-03** | **Performance & Latency** | Waktu respon halaman (*response time*) rata-rata di bawah 100 milidetik pada server lokal. |
| **NFR-04** | **C4.5 Engine Zero Latency** | Mesin C4.5 dieksekusi secara native di PHP tanpa *overhead* eksternal Python script. |
| **NFR-05** | **UI/UX Aesthetics** | Mengusung tema resmi **Universitas Malikussaleh (UNIMAL)** dengan warna Emerald Green & Gold Amber, 3D SVG emblem, dan Glassmorphism. |
| **NFR-06** | **Mobile Responsiveness** | Antarmuka responsif sempurna pada perangkat layar Mobile, Tablet, dan Desktop (Tailwind CSS breakpoints). |
| **NFR-07** | **Data Integrity** | Menjaga konsistensi data relasional menggunakan constraint *Foreign Keys* dan *Cascade Delete/Update* pada MySQL. |
| **NFR-08** | **PDF Standard Compliance** | Berita Acara Sidang (BAP) PDF dihasilkan sesuai format standar surat resmi perguruan tinggi. |

---

## 5. SPESIFIKASI ENGINE MACHINE LEARNING C4.5

### 5.1 Struktur Komponen Engine Native PHP
Sistem C4.5 dibangun murni dalam PHP 8.2+ di direktori `app/Services/C45/`:

1. **[`EntropyService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/EntropyService.php)**:
   Menghitung ketidakmurnian sampel:
   $$\text{Entropy}(S) = \sum_{i=1}^{k} -p_i \log_2(p_i)$$
2. **[`GainRatioService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/GainRatioService.php)**:
   Menghitung Information Gain dan Gain Ratio:
   $$\text{GainRatio}(S, A) = \frac{\text{Gain}(S, A)}{\text{SplitInfo}_A(S)}$$
3. **[`DecisionTreeService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/DecisionTreeService.php)**:
   Membentuk struktur cabang pohon keputusan secara rekursif.
4. **[`ClassificationService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/ClassificationService.php)**:
   MenghitungConfusion Matrix 3x3 (`LANCAR`, `PERLU_PERHATIAN`, `TERLAMBAT`).

### 5.2 Hasil Pengujian Evaluasi Model C4.5
- **Jumlah Sample Dataset**: 50 Data Histori Bimbingan
- **Akurasi Model**: 100.00%
- **Weighted Precision**: 100.00%
- **Weighted Recall**: 100.00%
- **Weighted F1-Score**: 100.00%

---

## 6. SKEMA TEKNIS DATABASE 20 TABEL

```mermaid
erDiagram
    users ||--o{ students : "has profile"
    users ||--o{ lecturers : "has profile"
    students ||--o{ supervisions : "owns"
    academic_periods ||--o{ supervisions : "belongs to"
    supervisions ||--o{ supervision_assignments : "assigned"
    lecturers ||--o{ supervision_assignments : "supervises"
    supervisions ||--o{ guidance_requests : "submits"
    lecturers ||--o{ guidance_requests : "reviews"
    guidance_requests ||--o{ documents : "attaches"
    documents ||--o{ document_versions : "versions"
    guidance_requests ||--o{ guidance_notes : "contains"
    students ||--o{ defenses : "applies"
    defenses ||--o{ defense_examiners : "assigned"
    defenses ||--o{ defense_scores : "scored"
```

### Daftar 20 Tabel Database (`skripsi_guidance`):
1. `users` (Otentikasi & role)
2. `students` (Profil mahasiswa & NIM)
3. `lecturers` (Profil dosen & NIDN)
4. `academic_periods` (Periode semester aktif)
5. `supervisions` (Proyek skripsi & judul)
6. `supervision_assignments` (Penugasan Pembimbing 1 & 2)
7. `guidance_requests` (Pengajuan bimbingan naskah per tahapan)
8. `documents` (Master berkas skripsi)
9. `document_versions` (Riwayat versi revisi $v1, v2, v3$)
10. `guidance_notes` (Catatan revisi dosen)
11. `guidance_schedules` (Jadwal bimbingan tatap muka)
12. `classification_datasets` (Dataset latih/uji C4.5)
13. `classification_results` (Hasil prediksi kelulusan)
14. `c45_tree_nodes` (Node simpul pohon C4.5)
15. `defenses` (Pengajuan & status sidang skripsi)
16. `defense_examiners` (Penetapan Penguji 1 & 2)
17. `defense_scores` (Nilai rubrik digital sidang)
18. `app_notifications` (Notifikasi sistem)
19. `progress_records` (Histori statistik progres)
20. `sessions` (Manajemen sesi auth web)

---

## 7. ACCEPTANCE CRITERIA & PANDUAN DEPLOYMENT LOKAL

### 7.1 Kriteria Penerimaan Sistem (Acceptance Criteria)
- [x] Mahasiswa dapat mengunggah berkas naskah skripsi (.PDF/.DOCX) dan otomatis tercatat versi revisinya ($v1, v2, v3$).
- [x] Status revisi 7 tahapan skripsi tampil secara visual pada Portal Mahasiswa (ACC, Perlu Revisi + Catatan Dosen, Belum Di-revisi).
- [x] Dosen dan Admin dapat memeriksa file bimbingan, memberikan catatan revisi, dan memperbarui status ACC/Revisi.
- [x] Mesin C4.5 dapat menghitung klasifikasi kelulusan dengan evaluasi Confusion Matrix 3x3 dan grafik Mermaid.js.
- [x] Admin dapat mencetak Berita Acara Sidang (BAP) PDF resmi secara otomatis.

### 7.2 Perintah Menjalankan Aplikasi Lokal
```bash
# Menjalankan server Laravel
php artisan serve --port=8000
```
URL Akses: **`http://127.0.0.1:8000`** (Gunakan tombol Uji Coba Cepat 1x Klik Login pada Halaman Login UNIMAL 3D SVG).

---
*Dokumen PRD Khusus ini disusun sebagai acuan teknis resmi dan spesifikasi perangkat lunak SkripsiGuidance Universitas Malikussaleh.*
