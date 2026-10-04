@extends('layouts.app')

@section('title', 'Daftar Pengajuan Bimbingan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Daftar Pengajuan Bimbingan</h1>
            <p class="text-xs text-slate-500">Kelola dan pantau seluruh riwayat pengajuan naskah skripsi Anda.</p>
        </div>
        <a href="{{ route('student.guidance.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
            <i class="fa-solid fa-plus mr-2"></i> Buat Pengajuan Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Tahapan</th>
                        <th class="py-3.5 px-6">Judul Pengajuan</th>
                        <th class="py-3.5 px-6">Dosen Pembimbing</th>
                        <th class="py-3.5 px-6">Tanggal Kirim</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                <span class="uppercase text-[11px] font-extrabold px-2.5 py-1 rounded bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $req->request_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $req->title }}
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $req->lecturer->name }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $req->submitted_at ? \Carbon\Carbon::parse($req->submitted_at)->isoFormat('D MMM Y, HH:mm') : '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $badge = match($req->status) {
                                        'DISETUJUI' => 'bg-emerald-100 text-emerald-800',
                                        'PERLU_REVISI' => 'bg-amber-100 text-amber-800',
                                        'MENUNGGU_REVIEW' => 'bg-sky-100 text-sky-800',
                                        'TERJADWAL' => 'bg-indigo-100 text-indigo-800',
                                        'SELESAI' => 'bg-emerald-100 text-emerald-800',
                                        'DITOLAK' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-slate-100 text-slate-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('student.guidance.show', $req->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                Belum ada pengajuan bimbingan. Klik tombol di atas untuk membuat pengajuan baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($requests, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
