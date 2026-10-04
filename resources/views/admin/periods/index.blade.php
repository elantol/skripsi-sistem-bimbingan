@extends('layouts.app')

@section('title', 'Periode Akademik')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Manajemen Periode Akademik</h1>
        <p class="text-xs text-slate-500">Kelola semester ganjil/genap dan tetapkan periode aktif.</p>
    </div>

    <!-- Create Period Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-calendar-plus text-brand-600 mr-2"></i> Tambah Periode Akademik Baru
        </h2>

        <form method="POST" action="{{ route('admin.periods.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Periode <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Semester Ganjil 2026/2027" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tahun Akademik <span class="text-rose-500">*</span></label>
                <input type="text" name="academic_year" required placeholder="2026/2027" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Semester <span class="text-rose-500">*</span></label>
                <select name="semester" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                <input type="date" name="start_date" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                <input type="date" name="end_date" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div class="flex items-center space-x-2 pt-6">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 text-brand-600 rounded">
                <label for="is_active" class="text-xs font-bold text-slate-700">Tetapkan Sebagai Periode Aktif</label>
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Periode
                </button>
            </div>
        </form>
    </div>

    <!-- Periods Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Nama Periode</th>
                        <th class="py-3.5 px-6">Tahun & Semester</th>
                        <th class="py-3.5 px-6">Tanggal Mulai — Selesai</th>
                        <th class="py-3.5 px-6 text-center">Status Aktif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($periods as $p)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $p->name }}</td>
                            <td class="py-4 px-6 text-xs text-slate-600 font-semibold">{{ $p->academic_year }} ({{ strtoupper($p->semester) }})</td>
                            <td class="py-4 px-6 text-xs text-slate-500">{{ $p->start_date->format('d/m/Y') }} — {{ $p->end_date->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($p->is_active)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-extrabold uppercase">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold uppercase">Non-aktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada periode akademik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
