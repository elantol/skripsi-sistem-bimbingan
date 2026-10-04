@extends('layouts.app')

@section('title', 'Daftar Pengajuan Review')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Daftar Pengajuan Bimbingan Mahasiswa</h1>
        <p class="text-xs text-slate-500">Tinjau naskah skripsi, berikan catatan revisi, dan atur jadwal bimbingan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <form method="GET" action="{{ route('lecturer.requests.index') }}" class="flex items-center space-x-2">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
                    <option value="">-- Semua Status --</option>
                    <option value="MENUNGGU_REVIEW" {{ request('status') === 'MENUNGGU_REVIEW' ? 'selected' : '' }}>MENUNGGU REVIEW</option>
                    <option value="PERLU_REVISI" {{ request('status') === 'PERLU_REVISI' ? 'selected' : '' }}>PERLU REVISI</option>
                    <option value="DISETUJUI" {{ request('status') === 'DISETUJUI' ? 'selected' : '' }}>DISETUJUI</option>
                    <option value="TERJADWAL" {{ request('status') === 'TERJADWAL' ? 'selected' : '' }}>TERJADWAL</option>
                    <option value="SELESAI" {{ request('status') === 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Tahapan & Judul</th>
                        <th class="py-3.5 px-6">Tanggal Kirim</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                <div>{{ $req->supervision->student->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500 font-medium">NIM: {{ $req->supervision->student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="uppercase text-[10px] font-extrabold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $req->request_type) }}
                                </span>
                                <div class="font-bold text-slate-800 text-sm mt-1">{{ $req->title }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($req->submitted_at)->isoFormat('D MMM Y, HH:mm') }}
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
                                <a href="{{ route('lecturer.requests.show', $req->id) }}" class="inline-flex items-center px-3 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-lg transition">
                                    <i class="fa-solid fa-file-pen mr-1"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                Belum ada pengajuan bimbingan.
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
