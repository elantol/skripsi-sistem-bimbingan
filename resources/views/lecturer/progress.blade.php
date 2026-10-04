@extends('layouts.app')

@section('title', 'Monitoring Progres Mahasiswa')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Monitoring Progres Mahasiswa Bimbingan</h1>
        <p class="text-xs text-slate-500">Rekapitulasi kuantitatif aktivitas bimbingan seluruh mahasiswa bimbingan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($studentProgressList as $item)
            @php
                $student = $item['student'];
                $m = $item['metrics'];
            @endphp
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">{{ $student->name ?? '-' }}</h3>
                        <p class="text-xs text-slate-500">NIM: {{ $student->nim ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-extrabold text-brand-600">{{ $m['progress_percentage'] ?? 0 }}%</span>
                        <span class="text-[10px] text-slate-400 block">PROGRES SKRIPSI</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 font-bold block">BIMBINGAN</span>
                        <span class="font-extrabold text-slate-800 text-sm">{{ $m['guidance_count'] ?? 0 }}x</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 font-bold block">REVISI</span>
                        <span class="font-extrabold text-slate-800 text-sm">{{ $m['revision_count'] ?? 0 }}x</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 font-bold block">LATE DAYS</span>
                        <span class="font-extrabold text-slate-800 text-sm">{{ $m['late_days'] ?? 0 }} hari</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl p-8 text-center text-slate-400 italic">
                Belum ada mahasiswa bimbingan.
            </div>
        @endforelse
    </div>
</div>
@endsection
