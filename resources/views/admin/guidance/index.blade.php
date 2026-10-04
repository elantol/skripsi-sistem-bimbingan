@extends('layouts.app')

@section('title', 'Seluruh Bimbingan')

@section('content')
<div class="space-y-6">
    <!-- Header Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Monitoring Seluruh Bimbingan Program Studi</h1>
            <p class="text-xs text-slate-500">Pantau status revisi, dokumen yang diunggah, dan catatan dosen pembimbing.</p>
        </div>
    </div>

    <!-- Filter Status Tabs & Search Bar -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-1.5 text-xs font-semibold">
            <a href="{{ route('admin.guidance.index') }}" class="px-3 py-1.5 rounded-xl transition {{ request('status') == '' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Bimbingan
            </a>
            <a href="{{ route('admin.guidance.index', ['status' => 'PERLU_REVISI']) }}" class="px-3 py-1.5 rounded-xl transition {{ request('status') == 'PERLU_REVISI' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                ⚠️ Perlu Revisi
            </a>
            <a href="{{ route('admin.guidance.index', ['status' => 'DISETUJUI']) }}" class="px-3 py-1.5 rounded-xl transition {{ request('status') == 'DISETUJUI' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                ✓ Disetujui (ACC)
            </a>
            <a href="{{ route('admin.guidance.index', ['status' => 'MENUNGGU_REVIEW']) }}" class="px-3 py-1.5 rounded-xl transition {{ request('status') == 'MENUNGGU_REVIEW' ? 'bg-sky-600 text-white shadow-sm' : 'bg-sky-50 text-sky-800 hover:bg-sky-100' }}">
                ⏳ Menunggu Review
            </a>
            <a href="{{ route('admin.guidance.index', ['status' => 'SELESAI']) }}" class="px-3 py-1.5 rounded-xl transition {{ request('status') == 'SELESAI' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                ✓ Selesai
            </a>
        </div>

        <form method="GET" action="{{ route('admin.guidance.index') }}" class="flex items-center gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, atau dosen..." class="px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-48 sm:w-64">
            <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                Cari
            </button>
        </form>
    </div>

    <!-- Main Table View -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm">Daftar Transaksi Bimbingan</h2>
            <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $requests->total() }} Data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Dosen Pembimbing</th>
                        <th class="py-3.5 px-6">Tahapan & Judul</th>
                        <th class="py-3.5 px-6">Status Revisi / Bimbingan</th>
                        <th class="py-3.5 px-6">Dokumen & Catatan Dosen</th>
                        <th class="py-3.5 px-6">Aksi Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        @php
                            $doc = $req->documents->first();
                            $latestVer = $doc ? $doc->versions->sortByDesc('version_number')->first() : null;
                            $latestNote = $req->guidanceNotes->sortByDesc('created_at')->first();
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <!-- Mahasiswa Info -->
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $req->supervision->student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $req->supervision->student->nim ?? '-' }}</div>
                            </td>

                            <!-- Dosen Pembimbing -->
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $req->lecturer->name ?? '-' }}
                                <div class="text-[10px] text-slate-400 font-medium">NIDN: {{ $req->lecturer->nidn ?? '-' }}</div>
                            </td>

                            <!-- Tahapan & Judul -->
                            <td class="py-4 px-6 max-w-xs">
                                <span class="uppercase text-[10px] font-extrabold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700">
                                    {{ str_replace('_', ' ', $req->request_type) }}
                                </span>
                                <div class="font-bold text-slate-800 text-xs mt-1 line-clamp-2">{{ $req->title }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Dikirim: {{ \Carbon\Carbon::parse($req->submitted_at)->isoFormat('D MMM Y, HH:mm') }}</div>
                            </td>

                            <!-- Status Revisi Indikator -->
                            <td class="py-4 px-6">
                                @if($req->status === 'PERLU_REVISI')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i> PERLU REVISI
                                    </span>
                                @elseif($req->status === 'DISETUJUI')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> DISETUJUI (ACC)
                                    </span>
                                @elseif($req->status === 'SELESAI')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-emerald-400"></i> SELESAI
                                    </span>
                                @elseif($req->status === 'MENUNGGU_REVIEW')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300 inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-hourglass-half text-sky-600"></i> MENUNGGU REVIEW
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-300">
                                        {{ str_replace('_', ' ', $req->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Dokumen & Ringkasan Catatan -->
                            <td class="py-4 px-6 max-w-xs">
                                @if($latestVer)
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                        <span class="truncate max-w-[140px]">{{ $latestVer->file_name }}</span>
                                        <span class="px-1.5 py-0.5 text-[10px] rounded font-extrabold bg-indigo-100 text-indigo-800">v{{ $latestVer->version_number }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tanpa dokumen</span>
                                @endif

                                <!-- Quote Box Catatan Dosen -->
                                @if($latestNote)
                                    <div class="mt-2 p-2 bg-amber-50/80 rounded-lg border border-amber-200/70 text-[11px] text-amber-900 line-clamp-2">
                                        <strong class="font-bold">Catatan Dosen:</strong> "{{ $latestNote->note }}"
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi Modal Detail -->
                            <td class="py-4 px-6">
                                <button onclick="showGuidanceDetail({{ $req->id }}, '{{ addslashes($req->supervision->student->name ?? '') }}', '{{ addslashes($req->title) }}', '{{ $req->status }}', '{{ addslashes($latestNote->note ?? 'Belum ada catatan dosen') }}', '{{ $latestVer ? $latestVer->file_name : '' }}', '{{ $latestVer ? $latestVer->version_number : '1' }}')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition border border-slate-200 flex items-center gap-1">
                                    <i class="fa-solid fa-eye text-indigo-600"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada transaksi bimbingan yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<!-- Modal Pop-Up Detail Revisi & Catatan Dosen -->
<div id="guidanceModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                <i class="fa-solid fa-file-signature text-indigo-600"></i> Detail Status Revisi & Catatan Bimbingan
            </h3>
            <button onclick="closeGuidanceDetail()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div>
                <span class="text-slate-400 font-semibold uppercase block">Mahasiswa & Judul</span>
                <div id="modalStudentName" class="font-bold text-slate-800 text-sm mt-0.5"></div>
                <div id="modalTitle" class="text-slate-600 mt-0.5"></div>
            </div>

            <div>
                <span class="text-slate-400 font-semibold uppercase block">Status Bimbingan Terkini</span>
                <div id="modalStatusBadge" class="mt-1"></div>
            </div>

            <div>
                <span class="text-slate-400 font-semibold uppercase block">Dokumen Berkas Terakhir</span>
                <div id="modalDocInfo" class="mt-1 p-2.5 bg-slate-50 rounded-xl border border-slate-200 font-medium text-slate-700"></div>
            </div>

            <div>
                <span class="text-slate-400 font-semibold uppercase block">Catatan Tambahan & Catatan Revisi Dosen</span>
                <div id="modalNotes" class="mt-1 p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 font-medium text-xs leading-relaxed"></div>
            </div>
        </div>

        <div class="pt-2 flex justify-end">
            <button onclick="closeGuidanceDetail()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                Tutup Window
            </button>
        </div>
    </div>
</div>

<script>
    function showGuidanceDetail(id, studentName, title, status, note, docName, docVer) {
        document.getElementById('modalStudentName').innerText = studentName;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalNotes').innerText = '"' + note + '"';
        
        let statusBadgeHtml = '';
        if (status === 'PERLU_REVISI') {
            statusBadgeHtml = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">⚠️ PERLU REVISI (Wajib Memperbaiki Berkas)</span>';
        } else if (status === 'DISETUJUI') {
            statusBadgeHtml = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">✓ DISETUJUI (ACC oleh Dosen)</span>';
        } else if (status === 'SELESAI') {
            statusBadgeHtml = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white inline-flex items-center gap-1">✓ SELESAI</span>';
        } else {
            statusBadgeHtml = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-300 inline-flex items-center gap-1">⏳ MENUNGGU REVIEW DOSEN</span>';
        }
        document.getElementById('modalStatusBadge').innerHTML = statusBadgeHtml;

        let docHtml = docName ? ('📄 ' + docName + ' (Versi Revisi v' + docVer + ')') : 'Belum ada dokumen diunggah.';
        document.getElementById('modalDocInfo').innerText = docHtml;

        document.getElementById('guidanceModal').classList.remove('hidden');
    }

    function closeGuidanceDetail() {
        document.getElementById('guidanceModal').classList.add('hidden');
    }
</script>
@endsection
