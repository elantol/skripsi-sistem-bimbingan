@extends('layouts.app')

@section('title', 'Review Pengajuan Bimbingan')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">{{ $guidanceRequest->title }}</h1>
            <p class="text-xs text-slate-500">Mahasiswa: <strong>{{ $guidanceRequest->supervision->student->name }}</strong> (NIM: {{ $guidanceRequest->supervision->student->nim }})</p>
        </div>
        <a href="{{ route('lecturer.requests.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pengajuan</span>
            <div class="mt-1 flex items-center space-x-3">
                @php
                    $badge = match($guidanceRequest->status) {
                        'DISETUJUI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'PERLU_REVISI' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'MENUNGGU_REVIEW' => 'bg-sky-100 text-sky-800 border-sky-300',
                        'TERJADWAL' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                        'SELESAI' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'DITOLAK' => 'bg-rose-100 text-rose-800 border-rose-300',
                        default => 'bg-slate-100 text-slate-800 border-slate-300',
                    };
                @endphp
                <span class="px-4 py-1.5 rounded-xl text-sm font-extrabold border shadow-sm {{ $badge }}">
                    {{ str_replace('_', ' ', $guidanceRequest->status) }}
                </span>
                <span class="text-xs text-slate-500">Tahapan: <strong class="uppercase text-slate-700">{{ str_replace('_', ' ', $guidanceRequest->request_type) }}</strong></span>
            </div>
        </div>

        @if($guidanceRequest->status === 'TERJADWAL')
            <form method="POST" action="{{ route('lecturer.requests.complete', $guidanceRequest->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-check-double mr-1.5"></i> Tandai Bimbingan Selesai
                </button>
            </form>
        @endif
    </div>

    <!-- Student Description -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Catatan Pengantar Mahasiswa</h3>
        <p class="text-sm text-slate-800 bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed">
            {{ $guidanceRequest->description ?? 'Tidak ada catatan pengantar.' }}
        </p>
    </div>

    <!-- Document Versioning & Download -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-file-pdf text-indigo-600 mr-2"></i> Dokumen Naskah & Versi Revisi
        </h3>

        @php $doc = $guidanceRequest->documents->first(); @endphp
        @if($doc)
            <div class="space-y-3">
                @foreach($doc->documentVersions as $version)
                    <div class="p-4 rounded-xl border border-slate-200 flex items-center justify-between hover:bg-slate-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-extrabold flex items-center justify-center text-sm">
                                v{{ $version->version_number }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Versi {{ $version->version_number }} — {{ $doc->original_name }}</h4>
                                <p class="text-xs text-slate-500">{{ $version->notes ?? 'Dokumen pengajuan' }} &bull; Diunggah: {{ \Carbon\Carbon::parse($version->created_at)->isoFormat('D MMM Y, HH:mm') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('documents.versions.download', $version->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-download mr-1.5"></i> Unduh Berkas
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic">Dokumen tidak ditemukan.</p>
        @endif
    </div>

    <!-- Review Form Section (PERLU_REVISI / DISETUJUI) -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <h2 class="text-base font-bold text-slate-800 flex items-center">
            <i class="fa-solid fa-pen-to-square text-brand-600 mr-2"></i> Form Review & Catatan Dosen
        </h2>

        <form method="POST" action="{{ route('lecturer.requests.review', $guidanceRequest->id) }}" class="space-y-4">
            @csrf

            <div>
                <label for="action" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Keputusan Review <span class="text-rose-500">*</span></label>
                <select name="action" id="action" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="PERLU_REVISI" {{ old('action', $guidanceRequest->status) === 'PERLU_REVISI' ? 'selected' : '' }}>PERLU REVISI (Minta Mahasiswa Upload Revisi Baru)</option>
                    <option value="DISETUJUI" {{ old('action', $guidanceRequest->status) === 'DISETUJUI' ? 'selected' : '' }}>DISETUJUI (Dokumen Memenuhi Syarat & Siap Dijadwalkan)</option>
                    <option value="DITOLAK" {{ old('action', $guidanceRequest->status) === 'DITOLAK' ? 'selected' : '' }}>DITOLAK</option>
                </select>
            </div>

            <div>
                <label for="progress_percentage" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Persentase Progres Skripsi Mahasiswa (%)</label>
                <input type="number" step="1" min="0" max="100" name="progress_percentage" id="progress_percentage"
                    value="{{ old('progress_percentage', $guidanceRequest->latestNote->progress_percentage ?? 0) }}"
                    placeholder="Contoh: 50"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label for="note" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Catatan Bimbingan / Instruksi Revisi <span class="text-rose-500">*</span></label>
                <textarea name="note" id="note" rows="5" required placeholder="Tuliskan catatan revisi detail untuk mahasiswa di sini..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Review & Catatan
            </button>
        </form>
    </div>

    <!-- Scheduling Section (Dosen Menentukan Jadwal BR-012) -->
    @if(in_array($guidanceRequest->status, ['DISETUJUI', 'TERJADWAL']))
        <div class="bg-indigo-50/50 rounded-2xl p-6 shadow-sm border border-indigo-200 space-y-4">
            <h2 class="text-base font-bold text-indigo-900 flex items-center">
                <i class="fa-solid fa-calendar-plus text-indigo-600 mr-2"></i> Penjadwalan Bimbingan (Dosen Pembimbing)
            </h2>

            @php $schedule = $guidanceRequest->latestSchedule; @endphp

            <form method="POST" action="{{ route('lecturer.requests.schedule', $guidanceRequest->id) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="scheduled_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Bimbingan <span class="text-rose-500">*</span></label>
                        <input type="date" name="scheduled_date" id="scheduled_date" required value="{{ old('scheduled_date', $schedule->scheduled_date->format('Y-m-d') ?? '') }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                        <input type="time" name="start_time" id="start_time" required value="{{ old('start_time', $schedule->start_time ?? '') }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jam Selesai <span class="text-rose-500">*</span></label>
                        <input type="time" name="end_time" id="end_time" required value="{{ old('end_time', $schedule->end_time ?? '') }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="method" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Metode Bimbingan <span class="text-rose-500">*</span></label>
                        <select name="method" id="method" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="tatap_muka" {{ old('method', $schedule->method ?? '') === 'tatap_muka' ? 'selected' : '' }}>Tatap Muka (Luring)</option>
                            <option value="online" {{ old('method', $schedule->method ?? '') === 'online' ? 'selected' : '' }}>Online (Daring / Zoom / GMeet)</option>
                        </select>
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Lokasi (Jika Tatap Muka)</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $schedule->location ?? '') }}" placeholder="Ruang Dosen Gedung B"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="meeting_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">URL Meeting (Jika Online)</label>
                        <input type="url" name="meeting_url" id="meeting_url" value="{{ old('meeting_url', $schedule->meeting_url ?? '') }}" placeholder="https://meet.google.com/..."
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>

                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i> Simpan / Perbarui Jadwal Bimbingan
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
