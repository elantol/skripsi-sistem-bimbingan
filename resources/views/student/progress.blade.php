@extends('layouts.app')

@section('title', 'Monitoring Progres')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Monitoring Progres Akademik</h1>
        <p class="text-xs text-slate-500">Statistik real-time aktivitas bimbingan dan riwayat snapshot progres.</p>
    </div>

    <!-- Active Metrics Overview -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4">Statistik Progres Saat Ini</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
            <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100">
                <span class="text-xs font-bold uppercase text-indigo-400">Total Bimbingan</span>
                <div class="text-2xl font-extrabold text-indigo-900 mt-1">{{ $metrics['guidance_count'] }} kali</div>
            </div>
            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100">
                <span class="text-xs font-bold uppercase text-amber-500">Jumlah Revisi</span>
                <div class="text-2xl font-extrabold text-amber-900 mt-1">{{ $metrics['revision_count'] }} kali</div>
            </div>
            <div class="p-4 rounded-xl bg-sky-50/50 border border-sky-100">
                <span class="text-xs font-bold uppercase text-sky-400">Total Submit</span>
                <div class="text-2xl font-extrabold text-sky-900 mt-1">{{ $metrics['submission_count'] }} kali</div>
            </div>
            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                <span class="text-xs font-bold uppercase text-emerald-500">Progres Persentase</span>
                <div class="text-2xl font-extrabold text-emerald-900 mt-1">{{ $metrics['progress_percentage'] }}%</div>
            </div>
            <div class="p-4 rounded-xl bg-rose-50/50 border border-rose-100">
                <span class="text-xs font-bold uppercase text-rose-400">Late Days</span>
                <div class="text-2xl font-extrabold text-rose-900 mt-1">{{ $metrics['late_days'] }} hari</div>
            </div>
            <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100">
                <span class="text-xs font-bold uppercase text-purple-400">Frekuensi</span>
                <div class="text-2xl font-extrabold text-purple-900 mt-1">{{ $metrics['guidance_frequency'] }}</div>
            </div>
        </div>
    </div>

    <!-- Snapshot History -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4">Riwayat Log Progres (Progress Records)</h2>
        <div class="space-y-3">
            @forelse($records as $rec)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-slate-800">Progres: {{ $rec->progress_percentage }}%</span>
                        <span class="text-slate-500 ml-2">&bull; Bimbingan: {{ $rec->guidance_count }}x | Revisi: {{ $rec->revision_count }}x</span>
                    </div>
                    <span class="text-slate-400">{{ \Carbon\Carbon::parse($rec->recorded_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-4">Belum ada riwayat snapshot progres.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
