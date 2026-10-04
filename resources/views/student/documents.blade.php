@extends('layouts.app')

@section('title', 'Dokumen & Versi Skripsi')

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Quick Action Button -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Perpustakaan Dokumen & Versioning Skripsi</h1>
            <p class="text-xs text-slate-500">Seluruh berkas naskah skripsi asli dan versi revisi ($v1, v2, v3$) tersimpan secara aman dalam private storage.</p>
        </div>
        <a href="{{ route('student.guidance.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/25 transition flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> + Upload Dokumen / Revisi Skripsi Baru
        </a>
    </div>

    @if(session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Document List & Version History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm">Daftar Dokumen Berkas Skripsi</h2>
            <span class="text-xs text-slate-500 font-medium">Total {{ count($documents) }} Dokumen Utama</span>
        </div>

        <div class="p-6 space-y-6">
            @forelse($documents as $doc)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-4 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 pb-3">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">
                                Tahapan: {{ str_replace('_', ' ', $doc->guidanceRequest->request_type ?? 'Bimbingan') }}
                            </span>
                            <h3 class="text-base font-bold text-slate-800 mt-1 flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                {{ $doc->original_name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pengajuan: <strong>{{ $doc->guidanceRequest->title ?? '-' }}</strong> &bull; Ukuran: {{ number_format($doc->file_size / 1024, 1) }} KB</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('documents.download', $doc->id) }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-download"></i> Unduh Berkas Utama
                            </a>
                        </div>
                    </div>

                    <!-- Riwayat Versi Revisi ($v1, v2, v3) -->
                    <div class="pl-4 border-l-2 border-indigo-300 space-y-2.5">
                        <span class="text-xs font-extrabold text-slate-600 uppercase tracking-wider block mb-1">Riwayat Versi Berkas ($v1, v2, v3):</span>
                        @foreach($doc->versions as $version)
                            <div class="flex items-center justify-between text-xs bg-white p-3 rounded-xl border border-slate-200 hover:border-indigo-300 transition shadow-xs">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Versi v{{ $version->version_number }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $version->notes ?? 'Dokumen revisi' }}</div>
                                        <div class="text-[10px] text-slate-400">Diunggah: {{ \Carbon\Carbon::parse($version->created_at)->isoFormat('D MMM Y, HH:mm') }} WIB</div>
                                    </div>
                                </div>
                                <a href="{{ route('documents.versions.download', $version->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                                    <span>Unduh File v{{ $version->version_number }}</span>
                                    <i class="fa-solid fa-arrow-down-to-line"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300 space-y-3">
                    <i class="fa-solid fa-cloud-arrow-up text-slate-300 text-4xl"></i>
                    <p class="text-sm font-semibold text-slate-600">Belum ada dokumen skripsi yang diunggah.</p>
                    <p class="text-xs text-slate-400">Silakan klik tombol di bawah untuk mengunggah berkas naskah skripsi Anda (Format: PDF, DOC, DOCX max 10MB).</p>
                    <a href="{{ route('student.guidance.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition inline-flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> Upload Dokumen Skripsi Pertama Anda
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
