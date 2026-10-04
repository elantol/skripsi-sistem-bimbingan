@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-900 to-brand-900 text-white rounded-2xl p-6 shadow-lg">
        <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-brand-200 mb-2">
            <i class="fa-solid fa-shield-halved mr-1"></i> Panel Administrator
        </span>
        <h1 class="text-2xl font-extrabold">Dashboard Pengelolaan Sistem & C4.5</h1>
        <p class="text-slate-300 text-sm mt-1">Monitoring komprehensif data mahasiswa, bimbingan, dan klasifikasi algoritma C4.5.</p>
    </div>

    <!-- Master Data Stats Grid (PRD 55) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Mahasiswa</span>
            <div class="text-3xl font-extrabold text-slate-800">{{ $totalStudents }} Orang</div>
            <a href="{{ route('admin.students.index') }}" class="text-xs text-brand-600 font-bold mt-2 block hover:underline">Kelola Mahasiswa &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Dosen</span>
            <div class="text-3xl font-extrabold text-slate-800">{{ $totalLecturers }} Dosen</div>
            <a href="{{ route('admin.lecturers.index') }}" class="text-xs text-brand-600 font-bold mt-2 block hover:underline">Kelola Dosen &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Bimbingan</span>
            <div class="text-3xl font-extrabold text-slate-800">{{ $totalGuidance }} Transaksi</div>
            <a href="{{ route('admin.guidance.index') }}" class="text-xs text-brand-600 font-bold mt-2 block hover:underline">Lihat Bimbingan &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Bimbingan Selesai</span>
            <div class="text-3xl font-extrabold text-emerald-600">{{ $completedGuidance }} Selesai</div>
            <span class="text-xs text-slate-400 mt-2 block">Telah Disetujui Dosen</span>
        </div>
    </div>

    <!-- C4.5 Classification Summary Cards -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Ringkasan Klasifikasi Progres C4.5</h2>
            <a href="{{ route('admin.classification.index') }}" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow transition">
                <i class="fa-solid fa-play mr-1"></i> Jalankan Engine C4.5
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-emerald-700">Mahasiswa Lancar</span>
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-emerald-900 mt-2">{{ $lancarCount }}</div>
                <p class="text-xs text-emerald-700 mt-1">Progres $\ge 70\%$ dan sesuai jadwal.</p>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-amber-700">Perlu diperhatikan</span>
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-amber-900 mt-2">{{ $perluPerhatianCount }}</div>
                <p class="text-xs text-amber-700 mt-1">Progres $40\% - 69\%$ membutuhkan dorongan.</p>
            </div>

            <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase text-rose-700">Mahasiswa Terlambat</span>
                    <i class="fa-solid fa-clock text-rose-500 text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-rose-900 mt-2">{{ $terlambatCount }}</div>
                <p class="text-xs text-rose-700 mt-1">Progres $< 40\%$ atau late days tinggi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
