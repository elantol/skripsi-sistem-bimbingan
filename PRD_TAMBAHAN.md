# Product Requirement Document (PRD) — Fitur & Sistem Tambahan SkripsiGuidance

**Nama Project:** SkripsiGuidance (Sistem Informasi Bimbingan Skripsi Berbasis Web)  
**Teknologi:** Laravel 12 + Livewire 3 + Tailwind CSS + MySQL + Algoritma C4.5  
**Dokumen:** PRD Tambahan & Roadmap Pengembangan Futuristik  
**Status:** Draft Modifikasi (Dapat Disesuaikan Oleh Pengguna)  
**Versi:** 1.1  

---

## 📌 Petunjuk Penggunaan Dokumen Ini
> Dokumen ini dibuat sebagai acuan dan arah pengembangan fitur tambahan untuk project **SkripsiGuidance**.  
> Anda dapat **mengedit, menambah, menghapus, atau mengubah status** modul di bawah ini sesuai kebutuhan skripsi / kampus Anda.

---

## 1. DRAFT MODUL TAMBAHAN (PROPOSED MODULES)

---

### 1.1 Modul Seminar Hasil & Sidang Skripsi (Thesis Defense & Examination)
* **Deskripsi:** Mengelola pendaftaran seminar proposal, seminar hasil, dan sidang akhir skripsi secara terintegrasi.
* **Fitur Utama:**
  - [ ] **Syarat Pendaftaran Sidang:** Validasi otomatis minimal jumlah bimbingan (misal: min 8x bimbingan) dan status C4.5 (`LANCAR`).
  - [ ] **Plotting Dewan Penguji:** Admin/Kaprodi menentukan Penguji 1 & Penguji 2.
  - [ ] **Penjadwalan Sidang:** Alokasi waktu, ruang sidang (Tatap Muka) atau link Zoom/Meet (Online).
  - [ ] **Rubrik Penilaian Digital:** Form input nilai oleh Dosen Pembimbing & Dosen Penguji (Presentasi, Penguasaan Materi, Produk/Sistem).
  - [ ] **Generate Berita Acara (BAP):** Cetak otomatis Berita Acara Sidang (BAP) dan Lembar Pengesahan berformat PDF.
* **Status Requirement:** `[x] DISETUJUI & AKTIF (Fokus Pengembangan Utama)`

---

### 1.2 Modul Surat Tugas & SK Dekan (Document & SK Generator)
* **Deskripsi:** Otomatisasi penerbitan dokumen formal akademik untuk pembimbingan dan pengujian skripsi.
* **Fitur Utama:**
  - [ ] **Generate Surat Tugas Pembimbing:** PDF Surat Tugas resmi ber-header fakultas/prodi.
  - [ ] **Generate SK Penguji Sidang:** PDF SK Dekan untuk penugasan penguji sidang skripsi.
  - [ ] **Keamanan QR Code Validation:** Menyisipkan QR Code unik di dokumen PDF untuk verifikasi keaslian surat tanpa tanda tangan basah.
* **Status Requirement:** `[ ] Pending / [x] Disetujui / [ ] Dibatalkan`

---

### 1.3 Modul Lembar Bebas Bimbingan & E-Signature QR Code
* **Deskripsi:** Memfasilitasi kartu bimbingan digital dan surat keterangan bebas bimbingan sebagai syarat daftar yudisium.
* **Fitur Utama:**
  - [ ] **Kartu Bimbingan Digital (PDF):** Cetak riwayat lengkap tanggal bimbingan, bab, dan catatan dosen.
  - [ ] **QR Code Verification Page:** Halaman verifikasi publik saat QR code di kartu bimbingan di-scan menggunakan HP.
  - [ ] **Pernyataan Persetujuan Maju Sidang:** Approve digital 1-click oleh Pembimbing 1 dan Pembimbing 2.
* **Status Requirement:** `[ ] Pending / [x] Disetujui / [ ] Dibatalkan`

---

### 1.4 Modul Notifikasi WhatsApp & Email Auto-Reminder (Integration)
* **Deskripsi:** Pengiriman pengingat otomatis kepada mahasiswa dan dosen untuk meningkatkan kecepatan kelulusan skripsi.
* **Fitur Utama:**
  - [ ] **Integrasi WA Gateway (Fonnte / Wablas API):** Notifikasi otomatis saat ada bimbingan baru, revisi masuk, atau jadwal disetujui.
  - [ ] **Auto-Alert Mahasiswa Terlambat (C4.5 Alert):** Pengiriman pesan peringatan otomatis untuk mahasiswa berlabel `PERLU_PERHATIAN` atau `TERLAMBAT`.
  - [ ] **Broadcast Pengumuman Prodi:** Pengiriman pesan massal dari Admin ke seluruh mahasiswa skripsi aktif.
* **Status Requirement:** `[ ] Pending / [x] Disetujui / [ ] Dibatalkan`

---

### 1.5 Modul Diskusi / Chat Room Per Dokumen Bimbingan
* **Deskripsi:** Komunikasi interaktif real-time antara mahasiswa dan dosen pembimbing pada setiap pengajuan bimbingan.
* **Fitur Utama:**
  - [ ] **Thread Komentar per Versi Dokumen:** Diskusi terpisah untuk dokumen $v1, v2, v3$.
  - [ ] **Lampiran File Pendukung (Gambar/Source Code):** Fitur selipan gambar error atau file snippet pendukung revisi.
* **Status Requirement:** `[ ] Pending / [x] Disetujui / [ ] Dibatalkan`

---

### 1.6 Modul Executive Analytics & Dashboard Kaprodi / Dekanat
* **Deskripsi:** Laporan analisis tingkat lanjut untuk evaluasi kinerja bimbingan dosen dan kelulusan mahasiswa.
* **Fitur Utama:**
  - [ ] **Rata-rata Durasi Pengerjaan Skripsi:** Grafik statistik rata-rata bulan/hari pengerjaan skripsi per angkatan.
  - [ ] **Beban Kerja Dosen (Lecturer Workload):** Grafik distribusi jumlah mahasiswa bimbingan per dosen.
  - [ ] **Export Data Akreditasi (BAN-PT / LAM INFOKOM):** Download data rekap bimbingan dan kelulusan dalam format Excel (`.xlsx`).
* **Status Requirement:** `[ ] Pending / [x] Disetujui / [ ] Dibatalkan`

---

## 2. RENCANA PRIORTAS & TAHAPAN PENGEMBANGAN (ROADMAP)

| Fase | Fokus Fitur | Estimasi Modul | Target Utama |
| :--- | :--- | :--- | :--- |
| **Fase 1 (Selesai)** | Core Bimbingan Skripsi + Algoritma C4.5 | Base System | Pengajuan, Review, Revisi, Schedule, Matrix Evaluasi C4.5 |
| **Fase 2 (Berikutnya)** | Modul Sidang & Berita Acara (BAP) | Modul 1.1 & 1.3 | Pendaftaran sidang, Penilaian Penguji, BAP PDF |
| **Fase 3 (Lanjutan)** | SK Generator & E-Signature QR Code | Modul 1.2 | QR Code verification, Surat Tugas PDF |
| **Fase 4 (Optimalisasi)** | WhatsApp Gateway & Executive Analytics | Modul 1.4 & 1.6 | Notifikasi WA otomatis & Laporan Akreditasi Excel |

---

## 3. CATATAN KUSTUMISASI PENGGUNA (USER NOTES)
> Silakan tuliskan catatan, perubahan aturan bisnis, atau fitur khusus kampus Anda di bagian ini:

```text
[Tuliskan catatan atau ide tambahan Anda di sini...]
1. 
2. 
3. 
```

---
**Tanggal Pembuatan:** 15 September 2026  
**File Location:** `d:\laragon\www\skripsi\PRD_TAMBAHAN.md`
