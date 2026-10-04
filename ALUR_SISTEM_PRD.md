# PRD ALUR & ARSITEKTUR SISTEM (GAMBARAN ALUR KERJA SKRIPSIGUIDANCE)

**NAMA SISTEM:** SkripsiGuidance  
**INSTITUSI:** Universitas Malikussaleh (UNIMAL) — Jurusan Teknik Informatika  
**TUJUAN DOKUMEN:** Panduan Visual & Diagram Alur Lengkap Perjalanan Data Sistem dari Registrasi Hingga Kelulusan Sidang & BAP PDF.

---

## 🗺️ 1. PETA GAMBARAN BESAR KEMANA SISTEM INI (MASTER SYSTEM DESTINATION MAP)

Sistem Informasi Bimbingan Skripsi ini dirancang untuk mengubah proses bimbingan manual yang sporadis menjadi **ekosistem akademik terintegrasi berstandar digital**. 

Gambar di bawah ini menunjukkan kemana data mengalir dalam 6 fase utama:

```mermaid
flowchart TD
    subgraph FASE_1 [FASE 1: PERSIAPAN MASTER DATA]
        A1[Admin Input Data Mahasiswa & Dosen] --> A2[Admin Tetapkan Pembimbing 1 & 2]
    end

    subgraph FASE_2 [FASE 2: PENGAJUAN NASKAH MAHASISWA]
        B1[Mahasiswa Login Portal UNIMAL] --> B2[Input / Edit Judul Skripsi]
        B2 --> B3[Upload File Draft Proposal / Bab 1-5]
        B3 --> B4[System Catat Versi v1, v2, v3 dalam Storage Isolated]
    end

    subgraph FASE_3 [FASE 3: PEMERIKSAAN & REVISI DOSEN]
        C1[Dosen Buka Workspace Review] --> C2[Pratinjau PDF via Live Viewer]
        C2 --> C3{Dosen Tentukan Status}
        C3 -->|PERLU REVISI| C4[Input Catatan Revisi Dosen]
        C4 --> C5[Mahasiswa Upload Revisi Baru v2 / v3]
        C5 --> C1
        C3 -->|DISETUJUI / ACC| C6[Status Tahapan Menjadi ACC 🟢]
    end

    subgraph FASE_4 [FASE 4: PREDIKSI MACHINE LEARNING C4.5]
        D1[Admin Import / Update Dataset Histori] --> D2[Jalankan Engine C4.5 Native PHP]
        D2 --> D3[Hitung Entropy & Gain Ratio]
        D3 --> D4[Render Tree Flowchart SVG & Confusion Matrix 3x3]
        D4 --> D5[Tampilkan Prediksi Kelulusan: LANCAR / PERLU PERHATIAN / TERLAMBAT]
    end

    subgraph FASE_5 [FASE 5: SIDANG SKRIPSI & PENILAIAN]
        E1[Mahasiswa Daftar Sidang setelah Bab 1-5 ACC] --> E2[Admin Plotting Dosen Penguji 1 & 2]
        E2 --> E3[Pelaksanaan Sidang Skripsi]
        E3 --> E4[Dosen Penguji Input Rubrik Skor Digital]
    end

    subgraph FASE_6 [FASE 6: KELULUSAN & BAP PDF]
        F1[Kalkulasi Nilai Akhir & Huruf Mutu] --> F2[System Generate BAP PDF Resmi UNIMAL]
        F2 --> F3[Sematkan Stempel 3D SVG & E-Signature QR Code Hash]
        F3 --> F4[Mahasiswa & Jurusan Unduh BAP PDF Sah]
    end

    FASE_1 --> FASE_2
    FASE_2 --> FASE_3
    FASE_3 --> FASE_4
    FASE_4 --> FASE_5
    FASE_5 --> FASE_6
```

---

## 🎓 2. JOURNEY MAP MAHASISWA (LANGKAH DEMI LANGKAH MAHASISWA)

Berikut adalah perjalanan penuh seorang mahasiswa dari awal hingga lulus sidang:

```mermaid
sequenceDiagram
    autonumber
    actor M as Mahasiswa UNIMAL
    participant S as Sistem Web Skripsi
    actor D as Dosen Pembimbing
    actor A as Admin Jurusan

    M->>S: 1. Login ke Portal Mahasiswa (/login)
    M->>S: 2. Input/Edit Judul Skripsi Aktif
    M->>S: 3. Unggah Berkas Draft Bab (Format PDF/DOCX)
    S->>S: 4. Catat Riwayat Versi Dokumen (v1, v2, v3)
    S font-weight: 5. Notifikasi Pengajuan Masuk ke Dosen
    D->>S: 6. Periksa Berkas & Masukkan Catatan Revisi
    alt Jika Status PERLU REVISI ⚠️
        S->>M: 7. Widget Tracker Tampilkan Badge Kuning ⚠️ + Quote Catatan Dosen
        M->>S: 8. Unggah Berkas Revisi Baru (Otomatis Menjadi Versi v2 / v3)
    else Jika Status DISETUJUI 🟢
        S->>M: 9. Widget Tracker Tampilkan Badge Hijau 🟢 (ACC)
    end
    M->>S: 10. Mengajukan Pendaftaran Sidang Skripsi (Setelah Bab 1-5 ACC)
    A->>S: 11. Konfirmasi Jadwal & Plotting Penguji
    M->>S: 12. Unduh Berita Acara Sidang (BAP) PDF Resmi
```

---

## 👨‍🏫 3. JOURNEY MAP DOSEN (LANGKAH DEMI LANGKAH DOSEN)

Alur kerja Dosen Pembimbing dan Dosen Penguji di dalam aplikasi:

```mermaid
flowchart LR
    A[Dosen Login Portal UNIMAL] --> B[Dashboard Monitoring Mahasiswa Bimbingan]
    B --> C[Buka Pengajuan Review Masuk]
    C --> D[Pratinjau Naskah PDF via Modal Live Viewer]
    D --> E{Pilihan Keputusan Dosen}
    E -->|Minta Revisi| F[Input Catatan Revisi & Set PERLU_REVISI]
    E -->|Setuju / ACC| G[Set Status DISETUJUI / ACC]
    G --> H[Atur Jadwal Bimbingan Tatap Muka jika Diperlukan]
    
    subgraph KETIKA_JADI_DOKUMEN_PENGUJI_SIDANG [Saat Menjadi Dosen Penguji Sidang]
        I[Buka Modul Penilaian Sidang] --> J[Input Skor Presentasi, Naskah, & Penguasaan]
        J --> K[Sistem Hitung Skor Total & Mutu Akhir]
    end
```

---

## 🛠️ 4. JOURNEY MAP ADMIN (LANGKAH DEMI LANGKAH ADMINISTRATOR)

Alur kerja Administrator Program Studi / Jurusan:

```mermaid
flowchart TD
    A[Admin Login Portal UNIMAL] --> B[Kelola Master Data: Mahasiswa, Dosen, Periode]
    B --> C[Penetapan Pembimbing 1 & Pembimbing 2]
    C --> D[Monitoring Seluruh Bimbingan Jurusan /admin/guidance]
    D --> E{Filter Status Bimbingan}
    E -->|PERLU_REVISI| F[Pantau Mahasiswa Terhambat Revisi]
    E -->|DISETUJUI| G[Pantau Mahasiswa Siap Sidang]
    
    subgraph ENGINE_C45 [Modul Machine Learning C4.5]
        H[Buka /admin/classification] --> I[Import 50 Dataset Histori Bimbingan]
        I --> J[Klik 'Jalankan Pelatihan C4.5']
        J --> K[Lihat Pohon Keputusan Flowchart SVG & Confusion Matrix 3x3]
        K --> L[Uji Simulator Interaktif What-If C4.5 Live]
    end

    subgraph MODUL_SIDANG [Modul Sidang & BAP PDF]
        M[Buka /admin/defenses] --> N[Plotting Dosen Penguji 1 & 2]
        N --> O[Cetak Berita Acara Sidang BAP PDF Resmi UNIMAL]
    </div>
```

---

## ⚡ 5. INTEGRASI TEKNIS ENGINES & FITUR ADVANCED

```mermaid
mindmap
  root((SkripsiGuidance System))
    Security Engine
      Dual Portal UNIMAL SVG
      Session Auto Detect
      Role Middleware EnsureRole
      Isolated Private Storage
    Student Portal
      Input Judul Skripsi
      Upload Draft Bab
      Versioning Document v1 v2 v3
      Interactive Revision Tracker
      Modal Live PDF Viewer
    Lecturer Workspace
      Review Draft Naskah
      Input Catatan Revisi
      Status Review Update
      Rubrik Penilaian Digital Sidang
      WhatsApp Gateway Simulator
    Admin Management
      Master Data CRUD
      Monitoring Jurusan Filter
      Plotting Penguji Sidang
      Generator BAP PDF Official Stamp
    C45 Machine Learning
      Entropy & Gain Ratio Engine
      3x3 Confusion Matrix 100 Percent
      Mermaid Flowchart Tree SVG
      Interactive What If Simulator
```

---

## 📌 6. RINGKASAN GAMBARAN AKHIR (KEMANA SISTEM INI BERJALAN)

Dengan alur sistem di atas, **SkripsiGuidance Universitas Malikussaleh** berjalan menuju 3 hasil akhir utama:

1. **Bagi Mahasiswa**: Memiliki kepastian status revisi tiap bab secara visual dan riwayat dokumen yang rapi ($v1 \rightarrow v2 \rightarrow v3$).
2. **Bagi Dosen & Admin**: Memiliki transparansi riwayat bimbingan, verifikasi naskah secara *online*, serta kemudahan pembuatan BAP PDF otomatis.
3. **Bagi Program Studi**: Memiliki alat *Machine Learning C4.5* yang dapat **mengidentifikasi lebih awal** mahasiswa yang berisiko terlambat lulus sehingga dapat diberikan intervensi bimbingan lebih dini.

---
*Dokumen PRD Alur & Arsitektur Sistem ini dirancang untuk memberikan gambaran komprehensif alur kerja perangkat lunak SkripsiGuidance.*
