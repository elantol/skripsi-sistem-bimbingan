@extends('layouts.app')

@section('title', 'Penilaian Sidang Skripsi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penilaian Sidang Skripsi</h1>
            <p class="text-sm text-gray-500">Daftar Sidang Dimana Anda Bertugas Sebagai Dewan Penguji atau Pembimbing</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-lg">Jadwal Tugas Pengujian Sidang</h2>
            <span class="text-xs text-gray-500 font-medium">Total {{ $defenses->total() }} Tugas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs text-gray-500 font-semibold uppercase">
                        <th class="p-4">Mahasiswa</th>
                        <th class="p-4">Judul Skripsi</th>
                        <th class="p-4">Jadwal & Ruang</th>
                        <th class="p-4">Peran Anda</th>
                        <th class="p-4">Status & Nilai Akhir</th>
                        <th class="p-4">Aksi Input Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($defenses as $def)
                        @php
                            $roleInDef = $def->examiners->where('lecturer_id', $lecturer->id)->first()->role ?? 'penguji';
                            $myScore = $def->scores->where('lecturer_id', $lecturer->id)->first();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-900">{{ $def->student->name }}</div>
                                <div class="text-xs text-gray-500">NIM: {{ $def->student->nim }}</div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 uppercase">{{ $def->defense_type }}</span>
                                <div class="font-medium text-gray-900 mt-1 line-clamp-2">{{ $def->title }}</div>
                            </td>
                            <td class="p-4">
                                @if($def->scheduled_date)
                                    <div class="font-bold text-gray-900">{{ $def->scheduled_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ substr($def->start_time, 0, 5) }} WIB</div>
                                    <div class="text-xs font-semibold text-emerald-600 mt-0.5">{{ $def->method === 'online' ? 'Online' : 'Ruang: ' . ($def->room ?? '-') }}</div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-800">
                                    {{ Str::headline($roleInDef) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                    @if($def->status === 'LULUS') bg-emerald-100 text-emerald-800
                                    @elseif($def->status === 'LULUS_REVISI') bg-blue-100 text-blue-800
                                    @elseif($def->status === 'TERJADWAL') bg-purple-100 text-purple-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ str_replace('_', ' ', $def->status) }}
                                </span>
                                @if($myScore)
                                    <div class="mt-1 text-xs text-indigo-600 font-bold">Skor Anda: {{ $myScore->total_score }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('lecturer.defenses.score', $def->id) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $myScore ? 'Edit Nilai' : 'Input Nilai' }}
                                    </a>
                                    @if(in_array($def->status, ['LULUS', 'LULUS_REVISI', 'TIDAK_LULUS']))
                                        <a href="{{ route('defenses.bap.download', $def->id) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition" title="Lihat BAP PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">Belum ada tugas pengujian sidang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $defenses->links() }}
        </div>
    </div>
</div>
@endsection
