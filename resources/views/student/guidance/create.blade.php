@extends('layouts.app')

@section('title', 'Buat Pengajuan Bimbingan Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Form Pengajuan Bimbingan</h1>
            <p class="text-xs text-slate-500">Unggah berkas naskah skripsi Anda untuk ditinjau oleh dosen pembimbing.</p>
        </div>
        <a href="{{ route('student.guidance.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <form method="POST" action="{{ route('student.guidance.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Dosen Tujuan -->
            <div>
                <label for="lecturer_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Dosen Pembimbing Tujuan <span class="text-rose-500">*</span></label>
                <select name="lecturer_id" id="lecturer_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">-- Pilih Dosen Pembimbing --</option>
                    @foreach($assignments as $assignment)
                        <option value="{{ $assignment->lecturer->id }}" {{ old('lecturer_id') == $assignment->lecturer->id ? 'selected' : '' }}>
                            {{ $assignment->lecturer->name }} ({{ strtoupper(str_replace('_', ' ', $assignment->supervisor_type)) }})
                        </option>
                    @endforeach
                </select>
                @error('lecturer_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Jenis Pengajuan -->
            <div>
                <label for="request_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tahapan / Jenis Pengajuan <span class="text-rose-500">*</span></label>
                <select name="request_type" id="request_type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">-- Pilih Jenis Pengajuan --</option>
                    <option value="proposal" {{ old('request_type') == 'proposal' ? 'selected' : '' }}>Proposal Skripsi</option>
                    <option value="bab_1" {{ old('request_type') == 'bab_1' ? 'selected' : '' }}>Bab 1 — Pendahuluan</option>
                    <option value="bab_2" {{ old('request_type') == 'bab_2' ? 'selected' : '' }}>Bab 2 — Tinjauan Pustaka</option>
                    <option value="bab_3" {{ old('request_type') == 'bab_3' ? 'selected' : '' }}>Bab 3 — Metodologi Penelitian</option>
                    <option value="bab_4" {{ old('request_type') == 'bab_4' ? 'selected' : '' }}>Bab 4 — Hasil dan Pembahasan</option>
                    <option value="bab_5" {{ old('request_type') == 'bab_5' ? 'selected' : '' }}>Bab 5 — Kesimpulan dan Saran</option>
                    <option value="laporan" {{ old('request_type') == 'laporan' ? 'selected' : '' }}>Laporan Skripsi Lengkap</option>
                    <option value="revisi" {{ old('request_type') == 'revisi' ? 'selected' : '' }}>Revisi Umum</option>
                    <option value="lainnya" {{ old('request_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('request_type') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Judul Pengajuan -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Judul / Subjek Bimbingan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Pengajuan Draft Bab 1 Pendahuluan"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi / Catatan Mahasiswa -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Catatan Pengantar Mahasiswa</label>
                <textarea name="description" id="description" rows="4" placeholder="Tuliskan catatan atau poin revisi yang ingin dikonsultasikan..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Upload File Dokumen (PDF, DOC, DOCX max 10MB) -->
            <div>
                <label for="file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">File Dokumen Skripsi <span class="text-rose-500">*</span></label>
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-brand-500 transition bg-slate-50/50">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-700">Pilih berkas atau seret file ke sini</p>
                    <p class="text-xs text-slate-400 mt-1">Format yang diperbolehkan: <strong>PDF, DOC, DOCX</strong> (Maksimum: 10 MB)</p>
                    <input type="file" name="file" id="file" required accept=".pdf,.doc,.docx"
                        class="mt-4 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('file') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-lg shadow-brand-500/25 transition">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengajuan Bimbingan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
