@extends('layouts.app')

@section('title', 'Hasil Klasifikasi C4.5 Progres Mahasiswa')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Klasifikasi Progres (Decision Tree C4.5)</h1>
            <p class="text-xs text-slate-500">Hasil analisis dan prediksi kondisi progres bimbingan skripsi berbasis algoritma C4.5.</p>
        </div>
    </div>

    <!-- Main Result Card -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200 text-center space-y-6">
        @php
            $label = $result->predicted_label ?? 'PERLU_PERHATIAN';
            $badgeColor = match($label) {
                'LANCAR' => 'bg-emerald-500 text-white shadow-emerald-500/30',
                'PERLU_PERHATIAN' => 'bg-amber-500 text-white shadow-amber-500/30',
                'TERLAMBAT' => 'bg-rose-500 text-white shadow-rose-500/30',
                default => 'bg-slate-500 text-white shadow-slate-500/30',
            };
            $desc = match($label) {
                'LANCAR' => 'Selamat! Progres bimbingan Anda berjalan sangat lancar dan sesuai target jadwal kelulusan program studi.',
                'PERLU_PERHATIAN' => 'Perhatian! Progres bimbingan Anda tergolong sedang. Tingkatkan frekuensi bimbingan dan segera selesaikan revisi.',
                'TERLAMBAT' => 'Peringatan! Progres bimbingan Anda terindikasi terlambat. Harap segera berkonsultasi dengan Dosen Pembimbing Anda.',
                default => 'Model belum melakukan klasifikasi.',
            };
        @endphp

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-900 text-amber-400 text-3xl shadow-xl">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Prediksi Status Progres</span>
            <div class="mt-2">
                <span class="inline-block px-6 py-3 rounded-2xl text-xl font-extrabold shadow-xl {{ $badgeColor }}">
                    {{ $label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $label) }}
                </span>
            </div>
            <p class="text-sm text-slate-600 mt-4 max-w-xl mx-auto leading-relaxed">{{ $desc }}</p>
        </div>

        <div class="pt-6 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs text-slate-400 font-bold block">TOTAL BIMBINGAN</span>
                <span class="text-lg font-extrabold text-slate-800">{{ $metrics['guidance_count'] }} kali</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs text-slate-400 font-bold block">JUMLAH REVISI</span>
                <span class="text-lg font-extrabold text-slate-800">{{ $metrics['revision_count'] }} kali</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs text-slate-400 font-bold block">LATE DAYS</span>
                <span class="text-lg font-extrabold text-slate-800">{{ $metrics['late_days'] }} hari</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs text-slate-400 font-bold block">FREKUENSI</span>
                <span class="text-lg font-extrabold text-slate-800">{{ $metrics['guidance_frequency'] }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
