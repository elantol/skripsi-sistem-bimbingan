@extends('layouts.app')

@section('title', 'Dosen Pembimbing & Judul Skripsi Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if(session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Card Informasi Judul Skripsi -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Judul Skripsi Saya</h1>
                <p class="text-xs text-slate-500">Judul skripsi aktif yang terdaftar untuk bimbingan akademik.</p>
            </div>
            <button onclick="openEditTitleModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square"></i> {{ $supervision ? 'Ubah Judul Skripsi' : '+ Input Judul Skripsi' }}
            </button>
        </div>

        @if($supervision)
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Judul Penelitian Final</span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-extrabold uppercase bg-emerald-100 text-emerald-800">
                        STATUS: {{ $supervision->status }}
                    </span>
                </div>
                <h2 class="text-lg font-bold text-slate-800 leading-snug">{{ $supervision->title }}</h2>
                <div class="text-xs text-slate-500 flex items-center gap-2 pt-1 border-t border-slate-200/60">
                    <span>Mulai Pengerjaan: {{ $supervision->started_at ? \Carbon\Carbon::parse($supervision->started_at)->isoFormat('D MMMM Y') : '-' }}</span>
                </div>
            </div>
        @else
            <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300 space-y-3">
                <i class="fa-solid fa-book-open text-slate-300 text-4xl"></i>
                <p class="text-sm text-slate-500 font-medium">Anda belum menginputkan judul skripsi. Silakan klik tombol di bawah untuk menginputkan judul skripsi Anda.</p>
                <button onclick="openEditTitleModal()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Input Judul Skripsi Sekarang
                </button>
            </div>
        @endif
    </div>

    <!-- Dosen Pembimbing Ditetapkan -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <h2 class="text-base font-bold text-slate-800">Dosen Pembimbing Terdaftar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($assignments as $assignment)
                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between space-y-3">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full {{ $assignment->supervisor_type === 'pembimbing_1' ? 'bg-indigo-100 text-indigo-800' : 'bg-sky-100 text-sky-800' }}">
                            {{ str_replace('_', ' ', $assignment->supervisor_type) }}
                        </span>
                        <h3 class="text-lg font-bold text-slate-800 mt-3">{{ $assignment->lecturer->name }}</h3>
                        <p class="text-xs text-slate-500">NIDN: {{ $assignment->lecturer->nidn }} &bull; {{ $assignment->lecturer->academic_degree }}</p>
                        <p class="text-xs text-slate-600 mt-2 flex items-center gap-1.5"><i class="fa-solid fa-phone text-slate-400"></i> {{ $assignment->lecturer->phone ?? 'Tidak ada nomor telepon' }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-slate-400 italic">
                    Belum ada dosen pembimbing yang ditetapkan oleh Program Studi / Admin.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Edit / Input Judul Skripsi Mahasiswa -->
<div id="editTitleModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-600"></i> {{ $supervision ? 'Edit Judul Skripsi' : 'Input Judul Skripsi Baru' }}
            </h3>
            <button onclick="closeEditTitleModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('student.supervision.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Judul Skripsi Lengkap</label>
                <textarea name="title" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Ketikkan judul proposal / skripsi penelitian Anda secara lengkap...">{{ old('title', $supervision->title ?? '') }}</textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeEditTitleModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">Simpan Judul</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditTitleModal() {
        document.getElementById('editTitleModal').classList.remove('hidden');
    }
    function closeEditTitleModal() {
        document.getElementById('editTitleModal').classList.add('hidden');
    }
</script>
@endsection
