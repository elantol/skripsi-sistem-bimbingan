@extends('layouts.app')

@section('title', 'Kelola Sidang Skripsi & Plotting Penguji')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Sidang Skripsi</h1>
            <p class="text-sm text-gray-500">Verifikasi Syarat, Plotting Dosen Penguji, dan Penjadwalan Sidang</p>
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
            <h2 class="font-bold text-gray-800 text-lg">Daftar Pengajuan Sidang Skripsi</h2>
            <span class="text-xs text-gray-500 font-medium">Total {{ $defenses->total() }} Pengajuan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs text-gray-500 font-semibold uppercase">
                        <th class="p-4">Mahasiswa</th>
                        <th class="p-4">Judul Skripsi & Jenis</th>
                        <th class="p-4">Jadwal & Lokasi</th>
                        <th class="p-4">Dewan Penguji (Penguji 1 & 2)</th>
                        <th class="p-4">Status & Nilai</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($defenses as $def)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-900">{{ $def->student->name }}</div>
                                <div class="text-xs text-gray-500">NIM: {{ $def->student->nim }}</div>
                                <div class="text-xs text-indigo-600 font-medium mt-0.5">{{ $def->student->study_program }}</div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 uppercase">{{ $def->defense_type }}</span>
                                <div class="font-medium text-gray-900 mt-1 line-clamp-2">{{ $def->title }}</div>
                            </td>
                            <td class="p-4">
                                @if($def->scheduled_date)
                                    <div class="font-bold text-gray-900">{{ $def->scheduled_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ substr($def->start_time, 0, 5) }} - {{ substr($def->end_time, 0, 5) }} WIB</div>
                                    <div class="text-xs font-semibold text-emerald-600 mt-0.5">{{ $def->method === 'online' ? 'Online' : 'Ruang: ' . ($def->room ?? '-') }}</div>
                                @else
                                    <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-1 rounded">Belum Dijadwalkan</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="space-y-1 text-xs">
                                    @forelse($def->examiners as $ex)
                                        <div>
                                            <span class="font-bold text-gray-800">{{ Str::headline($ex->role) }}:</span>
                                            <span class="text-gray-600">{{ $ex->lecturer->name }}</span>
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Belum ada penguji</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                    @if($def->status === 'LULUS') bg-emerald-100 text-emerald-800
                                    @elseif($def->status === 'LULUS_REVISI') bg-blue-100 text-blue-800
                                    @elseif($def->status === 'TERJADWAL') bg-purple-100 text-purple-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ str_replace('_', ' ', $def->status) }}
                                </span>
                                @if($def->final_score)
                                    <div class="mt-1 font-bold text-gray-900 text-xs">Nilai: {{ $def->final_score }} ({{ $def->grade }})</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="openScheduleModal({{ $def->id }}, '{{ addslashes($def->student->name) }}', '{{ addslashes($def->title) }}')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                        Plot Penguji & Jadwal
                                    </button>
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
                            <td colspan="6" class="p-8 text-center text-gray-400">Belum ada data pendaftaran sidang.</td>
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

<!-- Modal Plotting Penguji & Penjadwalan -->
<div id="scheduleModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl max-w-lg w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="font-bold text-gray-800 text-lg">Plotting Penguji & Penjadwalan Sidang</h3>
            <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="scheduleForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Mahasiswa & Judul</label>
                <div id="modalStudentTitle" class="text-xs text-gray-800 font-medium bg-slate-50 p-2.5 rounded-lg border border-slate-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dosen Penguji 1</label>
                    <select name="penguji_1_id" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">-- Pilih Penguji 1 --</option>
                        @foreach($lecturers as $lec)
                            <option value="{{ $lec->id }}">{{ $lec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dosen Penguji 2</label>
                    <select name="penguji_2_id" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">-- Pilih Penguji 2 --</option>
                        @foreach($lecturers as $lec)
                            <option value="{{ $lec->id }}">{{ $lec->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tanggal Sidang</label>
                    <input type="date" name="scheduled_date" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" value="09:00" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" value="10:30" required class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Metode Sidang</label>
                    <select name="method" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="tatap_muka">Tatap Muka (Offline)</option>
                        <option value="online">Online (Meet/Zoom)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ruang Sidang</label>
                    <input type="text" name="room" placeholder="Ruang Sidang 301" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">Simpan Plotting & Jadwal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openScheduleModal(defenseId, studentName, title) {
        document.getElementById('scheduleForm').action = '/admin/defenses/' + defenseId + '/schedule';
        document.getElementById('modalStudentTitle').innerText = studentName + ' — ' + title;
        document.getElementById('scheduleModal').classList.remove('hidden');
    }
    function closeScheduleModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }
</script>
@endsection
