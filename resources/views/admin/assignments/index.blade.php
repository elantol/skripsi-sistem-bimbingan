@extends('layouts.app')

@section('title', 'Penetapan Pembimbing')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Penetapan Dosen Pembimbing Skripsi</h1>
        <p class="text-xs text-slate-500">Tetapkan Pembimbing 1 dan Pembimbing 2 untuk setiap mahasiswa.</p>
    </div>

    <!-- Assignment Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-user-check text-brand-600 mr-2"></i> Form Penetapan Pembimbing
        </h2>

        <form method="POST" action="{{ route('admin.assignments.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Mahasiswa <span class="text-rose-500">*</span></label>
                <select name="student_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->nim }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Dosen Pembimbing <span class="text-rose-500">*</span></label>
                <select name="lecturer_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($lecturers as $l)
                        <option value="{{ $l->id }}">{{ $l->name }} (NIDN: {{ $l->nidn }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Periode Akademik <span class="text-rose-500">*</span></label>
                <select name="academic_period_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" {{ $p->is_active ? 'selected' : '' }}>{{ $p->name }} {{ $p->is_active ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jenis Pembimbing <span class="text-rose-500">*</span></label>
                <select name="supervisor_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="pembimbing_1">Pembimbing 1</option>
                    <option value="pembimbing_2">Pembimbing 2</option>
                </select>
            </div>

            <div class="md:col-span-4 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-check-double mr-1"></i> Simpan Penetapan Pembimbing
                </button>
            </div>
        </form>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Dosen Pembimbing</th>
                        <th class="py-3.5 px-6">Jenis Pembimbing</th>
                        <th class="py-3.5 px-6">Periode</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assignments as $a)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $a->student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $a->student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $a->lecturer->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $a->supervisor_type === 'pembimbing_1' ? 'bg-indigo-100 text-indigo-800' : 'bg-sky-100 text-sky-800' }}">
                                    {{ str_replace('_', ' ', $a->supervisor_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $a->academicPeriod->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form method="POST" action="{{ route('admin.assignments.delete', $a->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penetapan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-xs rounded-lg transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada penetapan pembimbing.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
