@extends('layouts.app')

@section('title', 'Portal Mahasiswa — Status Bimbingan & Upload Berkas')

@section('content')
<div class="space-y-6">
    <!-- Header Card Mahasiswa (UNIMAL Green Theme) -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-emerald-950 text-white rounded-2xl p-6 shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-emerald-200 mb-2">
                    <i class="fa-solid fa-graduation-cap mr-1"></i> Portal Mahasiswa Skripsi
                </span>
                <h1 class="text-2xl font-extrabold tracking-tight">Selamat Datang, {{ $student->name }}</h1>
                <p class="text-emerald-200 text-sm mt-1">NIM: {{ $student->nim }} &bull; Program Studi: {{ $student->study_program }} ({{ $student->generation }})</p>
            </div>
            <a href="{{ route('student.guidance.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-950/40 transition transform active:scale-95 text-xs uppercase tracking-wider border border-emerald-500/30">
                <i class="fa-solid fa-cloud-arrow-up mr-2 text-base text-amber-300"></i> + Upload Berkas / Revisi Baru
            </a>
        </div>
    </div>

    <!-- Status Overview Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Progress Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">
                <span>Progres Skripsi</span>
                <i class="fa-solid fa-chart-line text-emerald-600 text-base"></i>
            </div>
            <div class="my-1">
                <div class="text-3xl font-extrabold text-slate-800">{{ $metrics['progress_percentage'] }}%</div>
                <div class="w-full bg-slate-100 h-2 rounded-full mt-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $metrics['progress_percentage'])) }}%"></div>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 font-medium">Auto-calculated progress</p>
        </div>

        <!-- C4.5 Classification Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">
                <span>Status Kelulusan (C4.5)</span>
                <i class="fa-solid fa-wand-magic-sparkles text-amber-500 text-base"></i>
            </div>
            <div class="my-1">
                @php
                    $label = $classification->predicted_label ?? 'PERLU_PERHATIAN';
                    $badgeClass = match($label) {
                        'LANCAR' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'TERLAMBAT' => 'bg-rose-100 text-rose-800 border-rose-300',
                        default => 'bg-slate-100 text-slate-800 border-slate-300',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-extrabold border shadow-xs {{ $badgeClass }}">
                    {{ $label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $label) }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium">Model: Decision Tree C4.5</p>
        </div>

        <!-- Pembimbing 1 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col justify-between">
            <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-1 flex items-center justify-between">
                <span>Pembimbing 1</span>
                <i class="fa-solid fa-user-check text-emerald-600"></i>
            </div>
            <div class="my-1">
                <div class="font-bold text-slate-800 text-sm truncate">{{ $pembimbing1 ? $pembimbing1->lecturer->name : 'Belum Ditetapkan' }}</div>
                <div class="text-xs text-slate-500">NIDN: {{ $pembimbing1 ? $pembimbing1->lecturer->nidn : '-' }}</div>
            </div>
            <div class="text-[11px] text-slate-400">Pembimbing Utama</div>
        </div>

        <!-- Pembimbing 2 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex flex-col justify-between">
            <div class="text-xs font-bold uppercase tracking-wider text-teal-700 mb-1 flex items-center justify-between">
                <span>Pembimbing 2</span>
                <i class="fa-solid fa-user-check text-teal-600"></i>
            </div>
            <div class="my-1">
                <div class="font-bold text-slate-800 text-sm truncate">{{ $pembimbing2 ? $pembimbing2->lecturer->name : 'Belum Ditetapkan' }}</div>
                <div class="text-xs text-slate-500">NIDN: {{ $pembimbing2 ? $pembimbing2->lecturer->nidn : '-' }}</div>
            </div>
            <div class="text-[11px] text-slate-400">Pembimbing Pendamping</div>
        </div>
    </div>

    <!-- TRACKER TAHAPAN SKRIPSI (STATUS SUDAH / BELUM DI-REVISI / ACC) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-emerald-700"></i> Tracker Status Tahapan Skripsi & Revisi
                </h2>
                <p class="text-xs text-slate-500">Pantau status pemeriksaan berkas Anda pada setiap bab skripsi secara langsung.</p>
            </div>
            <a href="{{ route('student.guidance.create') }}" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-emerald-700"></i> Upload Tahapan Baru
            </a>
        </div>

        @php
            $stages = [
                'proposal' => ['title' => 'Proposal Skripsi', 'icon' => 'fa-file-lines'],
                'bab_1' => ['title' => 'Bab 1 — Pendahuluan', 'icon' => 'fa-bookmark'],
                'bab_2' => ['title' => 'Bab 2 — Tinjauan Pustaka', 'icon' => 'fa-book-open'],
                'bab_3' => ['title' => 'Bab 3 — Metodologi', 'icon' => 'fa-gears'],
                'bab_4' => ['title' => 'Bab 4 — Hasil & Pembahasan', 'icon' => 'fa-chart-pie'],
                'bab_5' => ['title' => 'Bab 5 — Kesimpulan & Saran', 'icon' => 'fa-circle-check'],
                'skripsi_akhir' => ['title' => 'Sidang Skripsi Akhir', 'icon' => 'fa-graduation-cap'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stages as $stageKey => $stageInfo)
                @php
                    if ($stageKey === 'skripsi_akhir') {
                        $def = $defenses->first();
                        $status = $def ? $def->status : 'BELUM_DIAJUKAN';
                        $note = $def->notes ?? null;
                        $latestVerNum = null;
                        $docName = null;
                    } else {
                        $req = $guidanceRequests->where('request_type', $stageKey)->first();
                        $status = $req ? $req->status : 'BELUM_DIAJUKAN';
                        $note = $req ? ($req->guidanceNotes->sortByDesc('created_at')->first()->note ?? null) : null;
                        $doc = $req ? $req->documents->first() : null;
                        $latestVerNum = $doc ? ($doc->versions->sortByDesc('version_number')->first()->version_number ?? 1) : null;
                        $docName = $doc ? $doc->original_name : null;
                    }
                @endphp

                <div class="p-4 rounded-2xl border transition flex flex-col justify-between space-y-3
                    @if(in_array($status, ['DISETUJUI', 'SELESAI', 'LULUS'])) bg-emerald-50/40 border-emerald-200
                    @elseif(in_array($status, ['PERLU_REVISI', 'LULUS_REVISI'])) bg-amber-50/50 border-amber-300
                    @elseif(in_array($status, ['MENUNGGU_REVIEW', 'TERJADWAL'])) bg-sky-50/50 border-sky-300
                    @else bg-slate-50/60 border-slate-200 @endif">

                    <div class="space-y-2">
                        <!-- Stage Header & Icon -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 shadow-xs">
                                    <i class="fa-solid {{ $stageInfo['icon'] }}"></i>
                                </div>
                                <span class="font-bold text-slate-800 text-xs">{{ $stageInfo['title'] }}</span>
                            </div>
                            @if($latestVerNum)
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-800">v{{ $latestVerNum }}</span>
                            @endif
                        </div>

                        <!-- Status Badge Status Revisi -->
                        <div>
                            @if(in_array($status, ['DISETUJUI', 'SELESAI', 'LULUS']))
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i> ACC / Disetujui
                                </span>
                            @elseif(in_array($status, ['PERLU_REVISI', 'LULUS_REVISI']))
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> ⚠️ Perlu Revisi
                                </span>
                            @elseif(in_array($status, ['MENUNGGU_REVIEW', 'TERJADWAL']))
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-100 text-sky-800 border border-sky-300 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-hourglass-half text-sky-600"></i> ⏳ Belum Di-revisi (Diperiksa)
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-circle-dot text-slate-400"></i> Belum Diajukan
                                </span>
                            @endif
                        </div>

                        <!-- Notes Box jika ada revisi -->
                        @if($note)
                            <div class="p-2.5 bg-amber-100/60 rounded-xl border border-amber-200 text-[11px] text-amber-900 leading-relaxed">
                                <strong>Catatan Dosen:</strong> "{{ $note }}"
                            </div>
                        @endif

                        @if($docName)
                            <div class="text-[11px] text-slate-600 font-semibold truncate flex items-center justify-between gap-1 pt-1">
                                <span class="truncate"><i class="fa-solid fa-file-pdf text-rose-500 mr-1"></i> {{ $docName }}</span>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    @if(isset($doc) && $doc)
                                        <button type="button" onclick="openPdfViewer('{{ route('documents.download', $doc->id) }}', '{{ $docName }}')" title="Pratinjau PDF" class="px-2 py-0.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded text-[10px] font-bold transition">
                                            <i class="fa-solid fa-eye"></i> View PDF
                                        </button>
                                    @endif
                                    <button type="button" onclick="openWaSimulator('0812-3456-7890', '{{ $student->name }}', '[UNIMAL SKRIPSI NOTIFIKASI]\nTahapan: {{ $stageInfo['title'] }}\nStatus: {{ $status }}\nCatatan Dosen: {{ $note ?? 'Sudah diperbarui' }}')" title="Notifikasi WA" class="px-2 py-0.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded text-[10px] font-bold transition">
                                        <i class="fa-brands fa-whatsapp"></i> WA
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Upload Action Button -->
                    <div class="pt-2 border-t border-slate-200/60">
                        @if($status === 'PERLU_REVISI')
                            <a href="{{ route('student.guidance.create') }}" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Upload File Revisi Baru
                            </a>
                        @elseif(in_array($status, ['DISETUJUI', 'SELESAI', 'LULUS']))
                            <div class="text-[11px] text-emerald-700 font-bold text-center">✓ Tahap Ini Telah Disetujui</div>
                        @elseif(in_array($status, ['MENUNGGU_REVIEW', 'TERJADWAL']))
                            <div class="text-[11px] text-sky-700 font-medium text-center">⏳ Menunggu Pemeriksaan Dosen</div>
                        @else
                            <a href="{{ route('student.guidance.create') }}" class="w-full py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Upload Berkas Tahapan Ini
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tabel Riwayat Seluruh File & Pengajuan Mahasiswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between font-bold text-slate-800 text-sm">
            <span>Riwayat Seluruh File & Pengajuan Bimbingan Anda</span>
            <a href="{{ route('student.documents') }}" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold">Lihat Perpustakaan Dokumen &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Tahapan & Judul</th>
                        <th class="py-3.5 px-6">Dosen Pembimbing</th>
                        <th class="py-3.5 px-6">Status Revisi</th>
                        <th class="py-3.5 px-6">Dokumen File</th>
                        <th class="py-3.5 px-6">Tanggal Kirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guidanceRequests as $req)
                        @php
                            $doc = $req->documents->first();
                            $ver = $doc ? $doc->versions->sortByDesc('version_number')->first() : null;
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                <span class="uppercase text-[10px] font-extrabold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    {{ str_replace('_', ' ', $req->request_type) }}
                                </span>
                                <div class="font-bold text-slate-800 text-xs mt-1">{{ $req->title }}</div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $req->lecturer->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($req->status === 'PERLU_REVISI')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">⚠️ PERLU REVISI</span>
                                @elseif($req->status === 'DISETUJUI')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">✓ ACC / DISETUJUI</span>
                                @elseif($req->status === 'SELESAI')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-900 text-white">✓ SELESAI</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300">⏳ MENUNGGU REVIEW</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs font-medium text-slate-700">
                                @if($ver)
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                        {{ $doc->original_name }}
                                        <span class="px-1.5 py-0.5 rounded font-bold bg-emerald-100 text-emerald-800 text-[10px]">v{{ $ver->version_number }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Tanpa dokumen</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($req->submitted_at)->isoFormat('D MMM Y, HH:mm') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada file atau pengajuan bimbingan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
