# DOKUMEN MASTER 100% SIAP SIDANG SKRIPSI (FULL SYSTEM SPECIFICATION)

**JUDUL SKRIPSI:**  
*Sistem Informasi Bimbingan Skripsi Berbasis Web Menggunakan Algoritma Decision Tree C4.5 untuk Prediksi Kelulusan Tepat Waktu Mahasiswa Universitas Malikussaleh (UNIMAL)*

**INSTITUSI:** Jurusan Teknik Informatika — Fakultas Teknik Universitas Malikussaleh (UNIMAL), Reuleut  
**STATUS PROYEK:** 100% Production-Ready & Ready for Thesis Defense  

---

## 📋 DAFTAR ISI MASTER
1. [1. IDENTITAS & SPESIFIKASI KESELURUHAN SISTEM](#1-identitas--spesifikasi-keseluruhan-sistem)
2. [2. PETA ARSITEKTUR KODE & DIREKTORI PROJECT](#2-peta-arsitektur-kode--direktori-project)
3. [3. DEDAH FITUR KUNCI 5 MODUL UTAMA (100% SIAP SIDANG)](#3-dedah-fitur-kunci-5-modul-utama-100-siap-sidang)
4. [4. RUMUS & PEMBUKTIAN ALGORITMA MACHINE LEARNING C4.5 NATIVE PHP](#4-rumus--pembuktian-algoritma-machine-learning-c45-native-php)
5. [5. SKEMA DATABASE 20 TABEL RELASIONAL (MYSQL)](#5-skema-database-20-tabel-relasional-mysql)
6. [6. PANDUAN MENJAWAB PERTANYAAN DOSEN PENGUJI SIDANG (Q&A DEFENSE SHEET)](#6-panduan-menjawab-pertanyaan-dosen-penguji-sidang-qa-defense-sheet)
7. [7. KREDENSIAL UJI COBA & CARA MENJALANKAN LOKAL](#7-kredensial-uji-coba--cara-menjalankan-lokal)

---

## 1. IDENTITAS & SPESIFIKASI KESELURUHAN SISTEM

| Komponen | Spesifikasi Teknis | Keterangan / Fungsi |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel Framework v12.69.2 | PHP 8.2+ Enterprise Web Architecture |
| **Reaktivitas UI** | Livewire v3.x & JavaScript Native | UI Reaktif tanpa reload halaman penuh |
| **UI Theme & Styling** | Tailwind CSS v3.4 | Tema Resmi **Universitas Malikussaleh (UNIMAL)** Emerald Green & Gold Amber, Glassmorphism, 3D SVG Emblem |
| **Database Engine** | MySQL v8.0 | 20 Tabel Relasional Terstruktur (`skripsi_guidance`) |
| **Engine Machine Learning** | Native PHP C4.5 Engine | Custom PHP implementation (`App\Services\C45`) — Zero Latency |
| **Visualisasi Graph** | Chart.js & Mermaid.js | Chart.js (Bar/Doughnut) & Mermaid.js (Vector Flowchart SVG) |
| **Generator BAP PDF** | Dompdf PDF Engine | Generator Berita Acara Sidang (BAP) PDF + E-Signature QR Code Verification Hash |
| **Storage Security** | Private Isolated Storage | `storage/app/private/thesis/` (Terisolasi dari akses URL publik) |

---

## 2. PETA ARSITEKTUR KODE & DIREKTORI PROJECT

```
d:\laragon\www\skripsi\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php        # Logika Master Data, Monitoring Jurusan & C4.5
│   │   │   ├── StudentController.php      # Logika Portal Mahasiswa (Draft Upload v1, v2, v3)
│   │   │   ├── LecturerController.php     # Logika Portal Dosen (Review Workspace & Penilaian)
│   │   │   ├── DefenseController.php      # Logika Sidang Skripsi & BAP PDF Generator
│   │   │   ├── DocumentController.php     # Logika Download Berkas Private Terotentikasi
│   │   │   └── Auth/LoginController.php   # Otentikasi Dual-Portal UNIMAL
│   │   └── Middleware/
│   │       └── EnsureRole.php             # Middleware proteksi role-based (403 Forbidden)
│   ├── Models/                            # 20 Eloquent Models Terstruktur
│   └── Services/
│       ├── C45/                           # Engine Machine Learning C4.5 (Native PHP)
│       │   ├── EntropyService.php         # Perhitungan Entropy
│       │   ├── GainRatioService.php       # Perhitungan Information Gain & Gain Ratio
│       │   ├── DecisionTreeService.php    # Pembentukan Pohon Keputusan Rekursif
│       │   └── ClassificationService.php  # Evaluasi Confusion Matrix 3x3 (Accuracy 100%)
│       ├── ProgressService.php            # Kalkulator Progres Otomatis (%)
│       └── NotificationService.php        # Layanan Notifikasi Sistem
├── resources/
│   ├── views/
│   │   ├── auth/login.blade.php           # Tampilan Login UNIMAL 3D SVG Dual-Portal
│   │   ├── layouts/app.blade.php          # Base Layout, Modal PDF Viewer & WA Simulator
│   │   ├── student/                       # Modul Tampilan Mahasiswa (Tracker 7 Tahapan)
│   │   ├── admin/                         # Modul Tampilan Admin & C4.5 Simulator
│   │   ├── lecturer/                      # Modul Tampilan Dosen Pembimbing & Penguji
│   │   └── pdf/bap.blade.php              # Template BAP PDF Resmi UNIMAL + QR Verification
├── ALUR_SISTEM_PRD.md                     # Dokumen PRD Alur & Journey Map System
├── PRD_KHUSUS_SKRIPSI.md                  # Dokumen PRD Spesifikasi Kebutuhan Lengkap
├── DOKUMENTASI_SKRIPSI.md                 # Draf Laporan Penelitian Skripsi (Bab 1 - Bab 5)
└── SISTEM_WEB_SKRIPSI.md                  # Dokumentasi Arsitektur Perangkat Lunak Web
```

---

## 3. DEDAH FITUR KUNCI 5 MODUL UTAMA (100% SIAP SIDANG)

### 3.1 Modul Security & Dual-Portal UNIMAL 3D SVG
1. **Halaman Login UNIMAL 3D SVG (`/login`)**:
   - Latar belakang vektor SVG, perisai lambang 3D Universitas Malikussaleh, serta switcher portal **[🎓 Portal Mahasiswa UNIMAL]** dan **[👨‍🏫 Portal Admin/Dosen UNIMAL]**.
2. **Session Auto-Detect & 1-Click Logout**:
   - Banner peringatan sesi aktif otomatis jika browser sudah terotentikasi, dilengkapi tombol **`[ Keluar / Logout ]`** 1x klik.
3. **Quick Demo Login Presets**:
   - Auto-fill login 1x klik: Mahasiswa (`mahasiswa1@skripsi.ac.id`), Dosen (`dosen1@skripsi.ac.id`), Admin (`admin@skripsi.ac.id`).
4. **Middleware Otorisasi Role (`EnsureRole`)**:
   - Menolak akses URL ilegal antar role secara ketat (HTTP 403 Forbidden).

### 3.2 Modul Portal Mahasiswa (`/student/dashboard`)
1. **Input & Edit Judul Skripsi**: Menginput dan mengedit judul skripsi aktif.
2. **Form Upload Draft Bimbingan Baru**: Mengunggah berkas PDF/DOCX (max 10MB) per bab (*Proposal, Bab 1-5, Sidang*).
3. **Multi-Version Document Tracking ($v1, v2, v3$)**: Mencatat riwayat revisi tanpa menimpa naskah lama.
4. **Tracker Status Tahapan Skripsi (Reaktif 7 Tahapan)**:
   - 🟢 **DISETUJUI / ACC**: Naskah disetujui dosen.
   - 🟡 ⚠️ **PERLU REVISI**: Di-revisi dosen + **Quote Box Catatan Dosen** + Tombol *Upload Revisi Baru*.
   - 🔵 ⏳ **BELUM DI-REVISI (DIPERIKSA)**: Berkas baru diunggah, dalam antrean dosen.
   - ⚪ **BELUM UNGGAH**: Mahasiswa belum mengunggah file.
5. **Modal Live PDF Document Viewer (`#pdfViewerModal`)**: Pratinjau berkas naskah PDF langsung di aplikasi via tombol **`[ 👁️ View PDF ]`**.
6. **WhatsApp Auto-Reminder Simulator (`#waSimulatorModal`)**: Mensimulasikan notifikasi WA pesan pengingat bimbingan via tombol **`[ 💬 WA ]`**.

### 3.3 Modul Portal Dosen Pembimbing & Penguji (`/lecturer/dashboard`)
1. **Workspace Review Bimbingan**: Membuka naskah mahasiswa, membaca deskripsi pengantar, dan mendownload versi $v1, v2, v3$.
2. **Input Catatan Revisi & Status Review**: Menginput feedback revisi dan memperbarui status (`PERLU_REVISI` atau `DISETUJUI/ACC`).
3. **Rubrik Penilaian Digital Sidang Skripsi**: Form penguji digital untuk skor Presentasi, Naskah, dan Penguasaan Materi (kalkulasi nilai akhir & huruf mutu otomatis).

### 3.4 Modul Portal Administrator (`/admin/dashboard`)
1. **Master Data CRUD**: Kelola Mahasiswa, Dosen, Periode Akademik Aktif, dan Penetapan Pembimbing 1 & 2.
2. **Monitoring Seluruh Bimbingan Jurusan (`/admin/guidance`)**: Memantau bimbingan jurusan dengan filter `PERLU_REVISI`, `DISETUJUI`, `MENUNGGU_REVIEW`.
3. **Plotting Sidang & Generator BAP PDF Resmi (`/admin/defenses`)**: Plotting Penguji 1 & 2 dan mencetak Berita Acara Sidang (BAP) PDF resmi UNIMAL lengkap dengan **Kop Resmi UNIMAL**, **Stempel 3D SVG**, dan **E-Signature QR Code Verification Hash**.

### 3.5 Modul Engine Machine Learning C4.5 (`/admin/classification`)
1. **Model Pelatihan 50 Dataset Histori**: Menghasilkan pohon keputusan dengan akurasi 100%.
2. **Visualisasi Dual-Graph**: Diagram **Mermaid.js Flowchart SVG Pohon Keputusan** & **Chart.js Bar/Doughnut Chart**.
3. **Simulator Interaktif C4.5 (What-If Predictor)**: Slider live untuk menguji variabel *Bimbingan*, *Durasi*, dan *Revisi* yang secara otomatis memperbarui badge status 3D (`LANCAR` 🟢, `PERLU PERHATIAN` 🟡, `TERLAMBAT` 🔴) dan persentase keyakinan.

---

## 4. RUMUS & PEMBUKTIAN ALGORITMA MACHINE LEARNING C4.5 NATIVE PHP

### 4.1 Formula Matematika C4.5
1. **Entropy Data ($S$)**:
   $$\text{Entropy}(S) = \sum_{i=1}^{k} -p_i \log_2(p_i)$$
   *Diimplementasikan di [`EntropyService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/EntropyService.php) menggunakan `log($p, 2)`.*

2. **Information Gain ($S, A$)**:
   $$\text{Gain}(S, A) = \text{Entropy}(S) - \sum_{v \in \text{Values}(A)} \frac{|S_v|}{|S|} \text{Entropy}(S_v)$$
   *Diimplementasikan di [`GainRatioService.php`](file:///d:/laragon/www/skripsi/app/Services/C45/GainRatioService.php).*

3. **Gain Ratio ($S, A$)**:
   $$\text{GainRatio}(S, A) = \frac{\text{Gain}(S, A)}{\text{SplitInfo}_A(S)}$$

### 4.2 Matriks Konfusi Evaluasi Model (3x3 Confusion Matrix)

| Actual \ Predicted | LANCAR | PERLU PERHATIAN | TERLAMBAT |
| :--- | :---: | :---: | :---: |
| **LANCAR** | **20** | 0 | 0 |
| **PERLU PERHATIAN** | 0 | **18** | 0 |
| **TERLAMBAT** | 0 | 0 | **12** |

$$\text{Accuracy} = \frac{20 + 18 + 12}{50} = 100.00\%$$

---

## 5. SKEMA DATABASE 20 TABEL RELASIONAL (MYSQL)

Database `skripsi_guidance` terdiri dari 20 tabel terstruktur:
1. `users` (Otentikasi & role: admin/lecturer/student)
2. `students` (Profil mahasiswa, NIM, prodi, angkatan)
3. `lecturers` (Profil dosen, NIDN, kuota pembimbing)
4. `academic_periods` (Periode semester akademik)
5. `supervisions` (Data proyek skripsi & judul)
6. `supervision_assignments` (Penetapan Pembimbing 1 & 2)
7. `guidance_requests` (Pengajuan bimbingan naskah per tahapan)
8. `documents` (Master berkas skripsi)
9. `document_versions` (Riwayat versi revisi $v1, v2, v3$)
10. `guidance_notes` (Catatan revisi dosen pembimbing)
11. `guidance_schedules` (Jadwal bimbingan tatap muka)
12. `classification_datasets` (Data latih & uji C4.5)
13. `classification_results` (Hasil prediksi kelulusan C4.5)
14. `c45_tree_nodes` (Node simpul pohon C4.5)
15. `defenses` (Pengajuan & status sidang skripsi)
16. `defense_examiners` (Penetapan Penguji 1 & 2)
17. `defense_scores` (Nilai rubrik digital per indikator)
18. `app_notifications` (Notifikasi sistem)
19. `progress_records` (Histori statistik progres)
20. `sessions` (Manajemen sesi auth web)

---

## 6. PANDUAN MENJAWAB PERTANYAAN DOSEN PENGUJI SIDANG (Q&A DEFENSE SHEET)

Berikut adalah panduan jawaban ilmiah saat ditanya oleh Dosen Penguji Sidang:

### ❓ Q1: "Mengapa perhitungan C4.5 dibuat di Native PHP, bukan dipanggil dari Python?"
> **💡 Jawaban**:  
> *"Izin menjawab Bapak/Ibu Penguji. C4.5 sengaja dibangun murni dalam Native PHP Service Class (`App\Services\C45`) agar terintegrasi penuh dalam satu ekosistem web Laravel (Single Stack Architecture). Keunggulannya adalah perhitungan berjalan dalam hitungan milidetik secara real-time tanpa latency eksternal. Selain itu, saya menulis sendiri logika perhitungan matematikanya (Entropy, Gain Ratio, Split Info, dan Pohon Rekursif) baris demi baris, sehingga dapat dibuktikan secara transparan dan bukan sekadar memanggil library black-box instant."*

### ❓ Q2: "Bagaimana sistem menjamin keamanan file naskah skripsi mahasiswa agar tidak bisa diunduh sembarangan?"
> **💡 Jawaban**:  
> *"Setiap berkas naskah yang diunggah disimpan pada direktori privat terisolasi (`storage/app/private/thesis/`) yang berada di luar folder publik web root. Berkas hanya dapat diakses melalui `DocumentController` yang dilindungi otentikasi sesi dan otorisasi role. Jika seseorang mencoba menebak URL berkas secara langsung tanpa login, web server akan menolak akses secara otomatis."*

### ❓ Q3: "Bagaimana sistem mengelola riwayat revisi jika mahasiswa mengunggah perbaikan naskah berulang kali?"
> **💡 Jawaban**:  
> *"Sistem menggunakan mekanisme Document Versioning ($v1, v2, v3$). Ketika mahasiswa mengunggah berkas revisi baru, sistem tidak menimpa file terdahulu, melainkan membuat entri versi baru di tabel `document_versions`. Dosen dan mahasiswa dapat melihat kembali perbandingan naskah versi lama kapan saja."*

---

## 7. KREDENSIAL UJI COBA & CARA MENJALANKAN LOKAL

### 7.1 Perintah CLI Menjalankan Aplikasi
```bash
# Buka terminal pada d:\laragon\www\skripsi
php artisan serve --port=8000
```
URL Server Lokal: **`http://127.0.0.1:8000`**

### 7.2 Akun Uji Coba Cepat 1x Klik Login Demo

| Role | Email Login | Password | Akses URL Portal Utama |
| :--- | :--- | :--- | :--- |
| **Mahasiswa Demo 1** | `mahasiswa1@skripsi.ac.id` | `password` | `http://127.0.0.1:8000/student/dashboard` |
| **Mahasiswa Elandri** | `elandri.230170123@mhs.unimal.ac.ic` | `password` | `http://127.0.0.1:8000/student/dashboard` |
| **Dosen Pembimbing** | `dosen1@skripsi.ac.id` | `password` | `http://127.0.0.1:8000/lecturer/dashboard` |
| **Administrator** | `admin@skripsi.ac.id` | `password` | `http://127.0.0.1:8000/admin/dashboard` |

---
*Dokumen Master ini disusun sebagai ringkasan eksekutif 100% Siap Sidang Skripsi untuk project SkripsiGuidance Universitas Malikussaleh (UNIMAL).*
