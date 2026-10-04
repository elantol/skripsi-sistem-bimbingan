@extends('layouts.app')

@section('title', 'Monitoring & Tambah Skripsi')

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Add Button -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Monitoring & Data Skripsi Mahasiswa</h1>
            <p class="text-xs text-slate-500">Kelola dan tambah judul skripsi mahasiswa untuk periode akademik aktif.</p>
        </div>
        <button onclick="openAddSupervisionModal()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-500/25 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> + Tambah / Input Judul Skripsi Baru
        </button>
    </div>

    @if(session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Supervisions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm">Daftar Skripsi Mahasiswa</h2>
            <span class="text-xs text-slate-500 font-medium">Total {{ $supervisions->total() }} Data Skripsi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Judul Skripsi</th>
                        <th class="py-3.5 px-6">Periode</th>
                        <th class="py-3.5 px-6">Total Bimbingan</th>
                        <th class="py-3.5 px-6">Status Skripsi</th>
                        <th class="py-3.5 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($supervisions as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $s->student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $s->student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800 max-w-xs">
                                {{ $s->title }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $s->academicPeriod->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs font-bold text-slate-700">
                                {{ $s->guidanceRequests->count() }} kali
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $s->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <button onclick="openEditSupervisionModal({{ $s->id }}, '{{ addslashes($s->title) }}', '{{ $s->status }}')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition border border-slate-200 flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square text-indigo-600"></i> Edit Judul
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada data skripsi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $supervisions->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Judul Skripsi Baru -->
<div id="addSupervisionModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-indigo-600"></i> Input / Tambah Skripsi Baru
            </h3>
            <button onclick="closeAddSupervisionModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.supervisions.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Pilih Mahasiswa</label>
                <select name="student_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} (NIM: {{ $st->nim }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Judul Skripsi</label>
                <textarea name="title" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Masukkan judul skripsi lengkap mahasiswa..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Skripsi</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="aktif">Aktif (Bimbingan)</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeAddSupervisionModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">Simpan Data Skripsi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Judul Skripsi -->
<div id="editSupervisionModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-indigo-600"></i> Edit Judul Skripsi
            </h3>
            <button onclick="closeEditSupervisionModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editSupervisionForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Judul Skripsi Baru</label>
                <textarea id="editTitleInput" name="title" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Skripsi</label>
                <select id="editStatusInput" name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="aktif">Aktif (Bimbingan)</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeEditSupervisionModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">Update Judul</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddSupervisionModal() {
        document.getElementById('addSupervisionModal').classList.remove('hidden');
    }
    function closeAddSupervisionModal() {
        document.getElementById('addSupervisionModal').classList.add('hidden');
    }
    function openEditSupervisionModal(id, title, status) {
        document.getElementById('editSupervisionForm').action = '/admin/supervisions/' + id;
        document.getElementById('editTitleInput').value = title;
        document.getElementById('editStatusInput').value = status;
        document.getElementById('editSupervisionModal').classList.remove('hidden');
    }
    function closeEditSupervisionModal() {
        document.getElementById('editSupervisionModal').classList.add('hidden');
    }
</script>
@endsection
