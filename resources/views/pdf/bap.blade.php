<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Sidang Skripsi (BAP) — {{ $student->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-surat h3 {
            margin: 2px 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title-bap {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .table-meta {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .table-meta td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-meta td.label {
            width: 25%;
            font-weight: bold;
        }
        .table-meta td.colon {
            width: 2%;
        }
        .table-scores {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table-scores th, .table-scores td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            font-size: 11pt;
        }
        .table-scores th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left { text-align: left !important; }
        .box-result {
            border: 2px solid #000;
            padding: 10px 15px;
            margin: 15px 0;
            font-weight: bold;
            text-align: center;
            font-size: 13pt;
            background-color: #f9f9f9;
        }
        .ttd-container {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .ttd-box {
            float: right;
            width: 45%;
            text-align: center;
        }
        .ttd-box-left {
            float: left;
            width: 45%;
            text-align: center;
        }
        .clear { clear: both; }
        .no-print {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e2e8f0;
            border-radius: 8px;
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4f46e5; color: #fff; font-weight: bold; border: none; border-radius: 6px; cursor: pointer;">
            🖨️ Cetak / Simpan Berita Acara (PDF)
        </button>
    </div>

    <!-- Header Kop Surat Universitas Malikussaleh -->
    <div class="kop-surat">
        <div style="float: left; width: 15%; text-align: center;">
            @php
                $logoPath = public_path('images/logo-unimal.png');
                $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="{{ $logoData }}" style="width: 70px; height: auto;" alt="Logo UNIMAL">
            @else
                <img src="{{ asset('images/logo-unimal.png') }}" style="width: 70px; height: auto;" alt="Logo UNIMAL">
            @endif
        </div>
        <div style="float: right; width: 84%; text-align: center;">
            <h2 style="font-size: 11pt; margin: 0; font-weight: normal;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>
            <h3 style="font-size: 14pt; margin: 2px 0; font-weight: bold;">UNIVERSITAS MALIKUSSALEH</h3>
            <h3 style="font-size: 12pt; margin: 0; font-weight: bold; color: #047857;">FAKULTAS TEKNIK — JURUSAN TEKNIK INFORMATIKA</h3>
            <p style="font-size: 9pt; font-style: normal; margin-top: 3px;">Kampus Main Reuleut, Jl. Cot Seurani, Aceh Utara, Aceh. Website: unimal.ac.id</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Judul Dokumen BAP -->
    <div class="title-bap">BERITA ACARA SIDANG SKRIPSI MAHASISWA</div>

    <p style="text-align: justify; margin-bottom: 15px;">
        Pada hari ini, <strong>{{ $formatted_date }}</strong>, telah dilaksanakan Ujian Sidang Skripsi secara komprehensif bagi mahasiswa Universitas Malikussaleh berikut:
    </p>

    <!-- Meta Data Mahasiswa -->
    <table class="table-meta">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="colon">:</td>
            <td><strong>{{ $student->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIM / Angkatan</td>
            <td class="colon">:</td>
            <td>{{ $student->nim }} (Angkatan {{ $student->generation }})</td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td class="colon">:</td>
            <td>{{ $student->study_program }} — Fakultas Teknik UNIMAL</td>
        </tr>
        <tr>
            <td class="label">Judul Skripsi</td>
            <td class="colon">:</td>
            <td>"{{ $defense->title }}"</td>
        </tr>
    </table>

    <p><strong>A. Rincian Penilaian Dewan Penguji Sidang:</strong></p>

    <!-- Tabel Rincian Nilai -->
    <table class="table-scores">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;" class="text-left">Nama Dewan Penguji UNIMAL</th>
                <th style="width: 20%;">Peran Penguji</th>
                <th style="width: 20%;">Total Skor (0-100)</th>
                <th style="width: 20%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examiners as $index => $ex)
                @php
                    $scoreEntry = $scores->get($ex->lecturer_id);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $ex->lecturer->name }}</strong><br><small style="color: #555;">NIDN: {{ $ex->lecturer->nidn }}</small></td>
                    <td>{{ Str::headline($ex->role) }}</td>
                    <td><strong>{{ $scoreEntry ? number_format($scoreEntry->total_score, 2) : '-' }}</strong></td>
                    <td style="font-size: 8.5pt; color: #047857; font-weight: bold;">[ E-Verified Digital ]</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Hasil Kelulusan -->
    <div class="box-result" style="border-color: #047857; background-color: #f0fdf4;">
        HASIL KEPUTUSAN SIDANG: {{ str_replace('_', ' ', $defense->status) }}<br>
        <span style="font-size: 11pt; font-weight: normal;">Nilai Akhir Sidang: <strong>{{ number_format($defense->final_score, 2) }}</strong> | Huruf Mutu: <strong style="color: #047857;">{{ $defense->grade }}</strong></span>
    </div>

    <p><strong>B. Catatan & Rekomendasi Revisi Dewan Penguji:</strong></p>
    <div style="border: 1px solid #047857; padding: 10px; min-height: 45px; font-size: 10.5pt; margin-bottom: 20px; background-color: #fafafa;">
        @php
            $hasNotes = false;
        @endphp
        @foreach($scores as $sc)
            @if($sc->revision_notes)
                @php $hasNotes = true; @endphp
                <p style="margin: 2px 0;">• <strong>{{ $sc->lecturer->name }}:</strong> {{ $sc->revision_notes }}</p>
            @endif
        @endforeach
        @if(!$hasNotes)
            <p style="margin: 0; font-style: italic; color: #666;">- Naskah disetujui tanpa catatan revisi khusus -</p>
        @endif
    </div>

    <!-- Tanda Tangan & QR CODE VERIFICATION SEAL -->
    <div class="ttd-container">
        <!-- E-Signature QR Code Verification Box -->
        <div class="ttd-box-left" style="border: 1px dashed #047857; padding: 8px; border-radius: 6px; background-color: #f0fdf4;">
            <div style="font-size: 8pt; font-weight: bold; color: #047857; margin-bottom: 4px;">VERIFIKASI E-SIGNATURE QR CODE:</div>
            <!-- Dynamic QR Code Canvas / Visual Stamp -->
            <div style="font-family: monospace; font-size: 7.5pt; color: #333; word-break: break-all;">
                HASH: UNIMAL-BAP-{{ $defense->id }}-{{ strtoupper(substr(md5($defense->id . $student->nim), 0, 10)) }}<br>
                STATUS: DOKUMEN SAH TERVERIFIKASI SISTEM
            </div>
        </div>

        <div class="ttd-box">
            Reuleut, {{ $formatted_date }}<br>
            Ketua Jurusan Teknik Informatika UNIMAL,<br><br><br>
            <strong><u>Dr. Ir. Budi Santoso, M.Kom.</u></strong><br>
            NIP. 198003152005011002
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
