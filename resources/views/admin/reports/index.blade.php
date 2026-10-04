@extends('layouts.app')

@section('title', 'Laporan Akademik & C4.5')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Laporan Progres Bimbingan Skripsi</h1>
            <p class="text-xs text-slate-500">Laporan akademis perkembangan skripsi dan klasifikasi mahasiswa.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow transition">
            <i class="fa-solid fa-print mr-1.5"></i> Cetak Laporan
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 font-bold text-slate-800 text-sm">
            Rekapitulasi Hasil Bimbingan & Classification C4.5
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Judul Skripsi</th>
                        <th class="py-3.5 px-6">Periode</th>
                        <th class="py-3.5 px-6">Hasil C4.5</th>
                        <th class="py-3.5 px-6">Status Skripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supervisions as $sup)
                        @php
                            $res = $results->firstWhere('student_id', $sup->student_id);
                            $label = $res->predicted_label ?? 'PERLU_PERHATIAN';
                            $badge = match($label) {
                                'LANCAR' => 'bg-emerald-100 text-emerald-800',
                                'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800',
                                'TERLAMBAT' => 'bg-rose-100 text-rose-800',
                                default => 'bg-slate-100 text-slate-800',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $sup->student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $sup->student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $sup->title }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $sup->academicPeriod->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $badge }}">
                                    {{ $label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $label) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs font-bold text-slate-700 uppercase">
                                {{ $sup->status }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
