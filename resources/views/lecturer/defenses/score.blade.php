@extends('layouts.app')

@section('title', 'Form Rubrik Penilaian Sidang Digital')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('lecturer.defenses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Sidang
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Form Penilaian Sidang Digital</h1>
            <p class="text-sm text-gray-500">Peran Anda: <span class="font-bold text-indigo-600 uppercase">{{ Str::headline($examiner->role) }}</span></p>
        </div>
    </div>

    <!-- Informasi Sidang & Mahasiswa -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400 uppercase font-semibold">Nama Mahasiswa</div>
                <div class="text-base font-bold text-gray-900">{{ $defense->student->name }} (NIM: {{ $defense->student->nim }})</div>
                <div class="text-xs text-gray-500">{{ $defense->student->study_program }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase font-semibold">Jenis & Tanggal Sidang</div>
                <div class="text-base font-bold text-indigo-700 uppercase">{{ $defense->defense_type }}</div>
                <div class="text-xs text-gray-600">{{ $defense->scheduled_date ? $defense->scheduled_date->format('d M Y') : 'TBD' }} | Jam {{ substr($defense->start_time, 0, 5) }} WIB</div>
            </div>
        </div>
        <div>
            <div class="text-xs text-gray-400 uppercase font-semibold">Judul Skripsi</div>
            <div class="text-sm font-semibold text-gray-800">{{ $defense->title }}</div>
        </div>
    </div>

    <!-- Form Input Rubrik Nilai -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Rubrik Penilaian Berbobot (Skala 0 - 100)
        </h2>

        <form action="{{ route('lecturer.defenses.score.store', $defense->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- 1. Presentasi & Sikap (20%) -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="font-bold text-gray-800 text-sm block">1. Presentasi & Penyampaian (Bobot 20%)</label>
                        <p class="text-xs text-gray-500 mt-0.5">Kejelasan alur presentasi, komunikasi, kerapihan slide, dan ketepatan waktu.</p>
                    </div>
                    <div class="w-32">
                        <input type="number" name="presentation_score" step="0.1" min="0" max="100" value="{{ old('presentation_score', $score->presentation_score ?? 80) }}" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold text-center text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- 2. Naskah Skripsi (25%) -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="font-bold text-gray-800 text-sm block">2. Naskah & Format Skripsi (Bobot 25%)</label>
                        <p class="text-xs text-gray-500 mt-0.5">Kesesuaian tata bahasa, format laporan, sitasi pustaka, dan sistematika bab.</p>
                    </div>
                    <div class="w-32">
                        <input type="number" name="manuscript_score" step="0.1" min="0" max="100" value="{{ old('manuscript_score', $score->manuscript_score ?? 85) }}" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold text-center text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- 3. Penguasaan Materi (35%) -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="font-bold text-gray-800 text-sm block">3. Penguasaan Materi & Tanya Jawab (Bobot 35%)</label>
                        <p class="text-xs text-gray-500 mt-0.5">Pemahaman landasan teori, analisis metodologi C4.5, dan respon terhadap pertanyaan penguji.</p>
                    </div>
                    <div class="w-32">
                        <input type="number" name="mastery_score" step="0.1" min="0" max="100" value="{{ old('mastery_score', $score->mastery_score ?? 82) }}" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold text-center text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <!-- 4. Produk / Software (20%) -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="font-bold text-gray-800 text-sm block">4. Demonstrasi Produk / Sistem (Bobot 20%)</label>
                        <p class="text-xs text-gray-500 mt-0.5">Kinerja aplikasi web, fungsionalitas fitur, UI/UX, dan akurasi implementasi sistem.</p>
                    </div>
                    <div class="w-32">
                        <input type="number" name="product_score" step="0.1" min="0" max="100" value="{{ old('product_score', $score->product_score ?? 88) }}" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm font-bold text-center text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Catatan Revisi / Masukan Penguji</label>
                <textarea name="revision_notes" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Tuliskan poin-poin revisi naskah atau sistem yang wajib diperbaiki mahasiswa...">{{ old('revision_notes', $score->revision_notes ?? '') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('lecturer.defenses.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Nilai Rubrik Sidang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
