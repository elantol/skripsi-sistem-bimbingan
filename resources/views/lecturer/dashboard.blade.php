@extends('layouts.app')

@section('title', 'Dashboard Dosen Pembimbing')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-900 text-white rounded-2xl p-6 shadow-lg">
        <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-indigo-200 mb-2">
            <i class="fa-solid fa-chalkboard-user mr-1"></i> Dosen Pembimbing
        </span>
        <h1 class="text-2xl font-extrabold">Selamat Datang, {{ $lecturer->name }}</h1>
        <p class="text-slate-300 text-sm mt-1">NIDN: {{ $lecturer->nidn }} &bull; {{ $lecturer->academic_degree }}</p>
    </div>

    <!-- Stats Overview Grid (PRD 54) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Mahasiswa Bimbingan</span>
            <span class="text-2xl font-extrabold text-slate-800">{{ $assignedStudentsCount }} Orang</span>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-sky-500 block mb-1">Menunggu Review</span>
            <span class="text-2xl font-extrabold text-sky-700">{{ $pendingReviewCount }}</span>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500 block mb-1">Perlu Revisi</span>
            <span class="text-2xl font-extrabold text-amber-700">{{ $revisionCount }}</span>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-500 block mb-1">Terjadwal</span>
            <span class="text-2xl font-extrabold text-indigo-700">{{ $scheduledCount }}</span>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-500 block mb-1">Selesai</span>
            <span class="text-2xl font-extrabold text-emerald-700">{{ $completedCount }}</span>
        </div>
    </div>

    <!-- Recent Guidance Requests -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-800">Pengajuan Terbaru Perlu Ditinjau</h2>
            <a href="{{ route('lecturer.requests.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($recentRequests as $req)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-extrabold uppercase px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                {{ str_replace('_', ' ', $req->request_type) }}
                            </span>
                            <span class="text-xs text-slate-400">&bull; {{ \Carbon\Carbon::parse($req->submitted_at)->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 mt-1">{{ $req->title }}</h4>
                        <p class="text-xs text-slate-500">Mahasiswa: <strong>{{ $req->supervision->student->name ?? '-' }}</strong> (NIM: {{ $req->supervision->student->nim ?? '-' }})</p>
                    </div>

                    <a href="{{ route('lecturer.requests.show', $req->id) }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-sm transition text-center">
                        <i class="fa-solid fa-signature mr-1.5"></i> Review & Tanggapi
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400 italic text-center py-6">Belum ada pengajuan bimbingan terbaru.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
