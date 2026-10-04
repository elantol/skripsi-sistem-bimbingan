@extends('layouts.app')

@section('title', 'Detail Pengajuan Bimbingan')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Back Bar -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">{{ $guidanceRequest->title }}</h1>
            <p class="text-xs text-slate-500">Tahapan: <span class="uppercase font-bold text-slate-700">{{ str_replace('_', ' ', $guidanceRequest->request_type) }}</span> &bull; Dosen: {{ $guidanceRequest->lecturer->name }}</p>
        </div>
        <a href="{{ route('student.guidance.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Saat Ini</span>
            <div class="mt-1 flex items-center space-x-3">
                @php
                    $badge = match($guidanceRequest->status) {
                        'DISETUJUI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'PERLU_REVISI' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'MENUNGGU_REVIEW' => 'bg-sky-100 text-sky-800 border-sky-300',
                        'TERJADWAL' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                        'SELESAI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'DITOLAK' => 'bg-rose-100 text-rose-800 border-rose-300',
                        default => 'bg-slate-100 text-slate-800 border-slate-300',
                    };
                @endphp
                <span class="px-4 py-1.5 rounded-xl text-sm font-extrabold border shadow-sm {{ $badge }}">
                    {{ str_replace('_', ' ', $guidanceRequest->status) }}
                </span>
                <span class="text-xs text-slate-500">Dikirim: {{ $guidanceRequest->submitted_at ? \Carbon\Carbon::parse($guidanceRequest->submitted_at)->isoFormat('D MMMM Y, HH:mm') : '-' }}</span>
            </div>
        </div>

        @if($guidanceRequest->status === 'PERLU_REVISI')
            <a href="#upload-revisi" class="px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/30 transition">
                <i class="fa-solid fa-upload mr-1.5"></i> Unggah Versi Revisi Baru
            </a>
        @endif
    </div>

    <!-- Catatan Dosen / Review Notes -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-comment-dots text-brand-600 mr-2"></i> Catatan & Hasil Review Dosen
        </h2>

        <div class="space-y-4">
            @forelse($guidanceRequest->guidanceNotes as $note)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2">
                        <span>Oleh: {{ $note->lecturer->name }}</span>
                        <span>{{ \Carbon\Carbon::parse($note->created_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                    </div>
                    <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">{{ $note->note }}</p>
                    <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-600">Hasil: <strong class="uppercase text-brand-600">{{ str_replace('_', ' ', $note->result) }}</strong></span>
                        <span class="font-bold text-slate-600">Progres Ditargetkan: <strong class="text-emerald-600">{{ $note->progress_percentage }}%</strong></span>
                    </div>
                </div>
            @empty
                <div class="text-xs text-slate-400 italic p-4 text-center bg-slate-50 rounded-xl">
                    Belum ada catatan review dari dosen pembimbing.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Document Versioning Section (BR-010: Document Versioning) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-file-code text-indigo-600 mr-2"></i> Riwayat Versi Dokumen (Document Versioning)
        </h2>

        @php $doc = $guidanceRequest->documents->first(); @endphp
        @if($doc)
            <div class="space-y-3">
                @foreach($doc->documentVersions as $version)
                    <div class="p-4 rounded-xl border border-slate-200 flex items-center justify-between hover:bg-slate-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-extrabold flex items-center justify-center text-sm">
                                v{{ $version->version_number }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Versi {{ $version->version_number }}</h4>
                                <p class="text-xs text-slate-500">{{ $version->notes ?? 'Dokumen revisi' }} &bull; Diunggah: {{ \Carbon\Carbon::parse($version->created_at)->isoFormat('D MMM Y, HH:mm') }}</p>
                            </div>
                        </div>

                        <!-- Secure Download Button -->
                        <a href="{{ route('documents.versions.download', $version->id) }}" class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition flex items-center">
                            <i class="fa-solid fa-download mr-1.5"></i> Unduh File
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic">Dokumen belum diunggah.</p>
        @endif
    </div>

    <!-- Form Upload Revisi Baru (Jika Status PERLU_REVISI) -->
    @if($guidanceRequest->status === 'PERLU_REVISI')
        <div id="upload-revisi" class="bg-amber-50/50 rounded-2xl p-6 shadow-sm border border-amber-200 space-y-4">
            <h2 class="text-base font-bold text-amber-900 flex items-center">
                <i class="fa-solid fa-cloud-arrow-up text-amber-600 mr-2"></i> Unggah Berkas Revisi Baru
            </h2>
            <p class="text-xs text-slate-600">Unggah berkas yang telah diperbaiki sesuai catatan dosen. Sistem akan menyimpan sebagai versi baru (v{{ ($doc ? $doc->documentVersions->max('version_number') + 1 : 2) }}) secara otomatis.</p>

            <form method="POST" action="{{ route('student.guidance.revision', $guidanceRequest->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Catatan Perubahan / Revisi</label>
                    <input type="text" name="notes" id="notes" placeholder="Contoh: Perbaikan Bab 1 sesuai masukan dosen"
                        class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div>
                    <label for="file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">File Dokumen Revisi <span class="text-rose-500">*</span></label>
                    <input type="file" name="file" id="file" required accept=".pdf,.doc,.docx"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                </div>

                <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Revisi
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
