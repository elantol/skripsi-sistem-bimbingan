@extends('layouts.app')

@section('title', 'Pendaftaran & Status Sidang Skripsi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sidang & Examination</h1>
            <p class="text-sm text-gray-500">Pendaftaran Sidang Skripsi & Unduh Berita Acara Sidang (BAP)</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Status Syarat Pendaftaran -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Status Syarat Pendaftaran Sidang
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-gray-500 uppercase font-semibold">Total Bimbingan Selesai</div>
                <div class="text-2xl font-bold text-slate-800 mt-1">{{ $completedGuidanceCount }} / 8 Selesai</div>
                <div class="mt-2 text-xs font-medium {{ $eligibleForDefense ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $eligibleForDefense ? '✓ Memenuhi Syarat Minimal' : '⚠️ Kurang ' . (8 - $completedGuidanceCount) . ' bimbingan lagi' }}
                </div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-gray-500 uppercase font-semibold">Status SK Bimbingan</div>
                <div class="text-2xl font-bold text-slate-800 mt-1">{{ $activeSupervision ? 'AKTIF' : 'TIDAK AKTIF' }}</div>
                <div class="mt-2 text-xs text-emerald-600 font-medium">Judul: {{ Str::limit($activeSupervision->title ?? '-', 35) }}</div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                <div class="text-xs text-gray-500 uppercase font-semibold">Status Kelayakan</div>
                <div class="mt-2">
                    @if($eligibleForDefense)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Bisa Daftar Sidang</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Belum Memenuhi Syarat</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pendaftaran Sidang (Jika Eligible) -->
    @if($eligibleForDefense)
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Formulir Pengajuan Sidang Baru
        </h2>
        <form action="{{ route('student.defenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jenis Sidang</label>
                    <select name="defense_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="skripsi_akhir">Sidang Skripsi Akhir (Maju Ujian)</option>
                        <option value="hasil">Seminar Hasil (Semhas)</option>
                        <option value="proposal">Seminar Proposal (Sempro)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Skripsi Final</label>
                    <input type="text" name="title" value="{{ $activeSupervision->title ?? '' }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Abstrak / Ringkasan Penelitian</label>
                <textarea name="abstract" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Tuliskan ringkasan / abstrak skripsi Anda..."></textarea>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Pengajuan Sidang
            </button>
        </form>
    </div>
    @endif

    <!-- Riwayat Pendaftaran Sidang -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-lg">Riwayat & Status Sidang</h2>
            <span class="text-xs text-gray-500 font-medium">{{ count($defenses) }} Pendaftaran</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs text-gray-500 font-semibold uppercase">
                        <th class="p-4">Jenis & Judul Sidang</th>
                        <th class="p-4">Jadwal & Ruang</th>
                        <th class="p-4">Dewan Penguji</th>
                        <th class="p-4">Status & Nilai</th>
                        <th class="p-4">Aksi / BAP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($defenses as $def)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 uppercase">{{ $def->defense_type }}</span>
                                <div class="font-bold text-gray-900 mt-1 max-w-md">{{ $def->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">Diajukan: {{ $def->submitted_at ? $def->submitted_at->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="p-4">
                                @if($def->scheduled_date)
                                    <div class="font-medium text-gray-900">{{ $def->scheduled_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ substr($def->start_time, 0, 5) }} - {{ substr($def->end_time, 0, 5) }} WIB</div>
                                    <div class="text-xs font-semibold text-indigo-600 mt-0.5">{{ $def->method === 'online' ? 'Online: ' . $def->meeting_url : 'Ruang: ' . ($def->room ?? 'TBD') }}</div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="space-y-1 text-xs">
                                    @forelse($def->examiners as $ex)
                                        <div>
                                            <span class="font-semibold text-gray-800">{{ Str::headline($ex->role) }}:</span>
                                            <span class="text-gray-600">{{ $ex->lecturer->name }}</span>
                                        </div>
                                    @empty
                                        <span class="text-gray-400 italic">Belum diplot</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($def->status === 'LULUS') bg-emerald-100 text-emerald-800
                                    @elseif($def->status === 'LULUS_REVISI') bg-blue-100 text-blue-800
                                    @elseif($def->status === 'TERJADWAL') bg-purple-100 text-purple-800
                                    @elseif($def->status === 'TIDAK_LULUS') bg-rose-100 text-rose-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ str_replace('_', ' ', $def->status) }}
                                </span>
                                @if($def->final_score)
                                    <div class="mt-1 font-bold text-gray-900 text-sm">Nilai: {{ $def->final_score }} ({{ $def->grade }})</div>
                                @endif
                            </td>
                            <td class="p-4">
                                @if(in_array($def->status, ['LULUS', 'LULUS_REVISI', 'TIDAK_LULUS']))
                                    <a href="{{ route('defenses.bap.download', $def->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak BAP PDF
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sidang Belum Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">Belum ada data pendaftaran sidang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
