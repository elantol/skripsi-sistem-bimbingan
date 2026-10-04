@extends('layouts.app')

@section('title', 'Jadwal Bimbingan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Jadwal Bimbingan Skripsi</h1>
        <p class="text-xs text-slate-500">Jadwal bimbingan ditetapkan oleh Dosen Pembimbing.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Tanggal & Waktu</th>
                        <th class="py-3.5 px-6">Dosen Pembimbing</th>
                        <th class="py-3.5 px-6">Pengajuan</th>
                        <th class="py-3.5 px-6">Metode & Lokasi</th>
                        <th class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $sch)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                <div>{{ \Carbon\Carbon::parse($sch->scheduled_date)->isoFormat('D MMMM Y') }}</div>
                                <div class="text-xs text-slate-500 font-medium">Pukul {{ $sch->start_time }} - {{ $sch->end_time }}</div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $sch->lecturer->name }}
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $sch->guidanceRequest->title }}
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="font-bold uppercase text-purple-700 bg-purple-50 px-2 py-0.5 rounded">{{ $sch->method === 'online' ? 'Online' : 'Tatap Muka' }}</span>
                                @if($sch->method === 'online' && $sch->meeting_url)
                                    <div class="mt-1"><a href="{{ $sch->meeting_url }}" target="_blank" class="text-brand-600 hover:underline font-bold"><i class="fa-solid fa-video mr-1"></i> Buka URL Meeting</a></div>
                                @elseif($sch->location)
                                    <div class="mt-1 text-slate-500"><i class="fa-solid fa-location-dot mr-1"></i> {{ $sch->location }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $badge = match($sch->status) {
                                        'terjadwal' => 'bg-indigo-100 text-indigo-800',
                                        'selesai' => 'bg-emerald-100 text-emerald-800',
                                        'dibatalkan' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-slate-100 text-slate-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badge }}">
                                    {{ $sch->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                Belum ada jadwal bimbingan yang ditetapkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
