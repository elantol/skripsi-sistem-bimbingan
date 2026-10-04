@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Riwayat Bimbingan Selesai</h1>
        <p class="text-xs text-slate-500">Arsip bimbingan mahasiswa yang telah ditinjau dan diselesaikan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Pengajuan</th>
                        <th class="py-3.5 px-6">Tanggal Bimbingan</th>
                        <th class="py-3.5 px-6">Hasil & Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $item->supervision->student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $item->supervision->student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $item->title }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($item->updated_at)->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-700">
                                @if($item->guidanceNotes->isNotEmpty())
                                    <div class="font-bold text-slate-800">{{ $item->guidanceNotes->last()->note }}</div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">
                                Belum ada riwayat bimbingan selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
