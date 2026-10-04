@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Riwayat & Catatan Bimbingan</h1>
        <p class="text-xs text-slate-500">Rekapitulasi catatan hasil bimbingan dan persetujuan dosen pembimbing.</p>
    </div>

    <div class="space-y-4">
        @forelse($history as $item)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-3 mb-3 gap-2">
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400">{{ str_replace('_', ' ', $item->request_type) }}</span>
                        <h3 class="text-base font-bold text-slate-800">{{ $item->title }}</h3>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Dosen: {{ $item->lecturer->name }}</span>
                </div>

                <div class="space-y-3">
                    @forelse($item->guidanceNotes as $note)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                            <div class="font-bold text-slate-700 mb-1">Catatan Hasil: <span class="uppercase text-brand-600">{{ str_replace('_', ' ', $note->result) }}</span></div>
                            <p class="text-slate-700 leading-relaxed">{{ $note->note }}</p>
                            <div class="mt-2 text-slate-400 font-medium">Ditulis pada: {{ \Carbon\Carbon::parse($note->created_at)->isoFormat('D MMMM Y, HH:mm') }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada catatan hasil bimbingan.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-slate-400 italic">
                Belum ada riwayat bimbingan.
            </div>
        @endforelse
    </div>
</div>
@endsection
