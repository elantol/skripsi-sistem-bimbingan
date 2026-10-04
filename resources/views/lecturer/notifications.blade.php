@extends('layouts.app')

@section('title', 'Notifikasi Dosen')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Notifikasi Masuk</h1>
        <p class="text-xs text-slate-500">Pemberitahuan otomatis seputar pengajuan dan revisi mahasiswa.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notif)
            <div class="p-4 hover:bg-slate-50 transition flex items-start space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800">{{ $notif->title }}</h4>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-400 text-xs italic">
                Belum ada notifikasi.
            </div>
        @endforelse
    </div>
</div>
@endsection
