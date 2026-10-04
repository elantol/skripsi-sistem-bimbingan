@extends('layouts.app')

@section('title', 'Kelola Dosen')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Manajemen Data Dosen Pembimbing</h1>
        <p class="text-xs text-slate-500">Kelola akun dan NIDN dosen pembimbing skripsi.</p>
    </div>

    <!-- Create Lecturer Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-user-plus text-brand-600 mr-2"></i> Tambah Dosen Baru
        </h2>

        <form method="POST" action="{{ route('admin.lecturers.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIDN <span class="text-rose-500">*</span></label>
                <input type="text" name="nidn" required placeholder="NIDN Dosen" value="{{ old('nidn') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="Nama Lengkap & Gelar" value="{{ old('name') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required placeholder="dosen@skripsi.ac.id" value="{{ old('email') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Gelar Akademik</label>
                <input type="text" name="academic_degree" placeholder="M.Kom. / Dr." value="{{ old('academic_degree') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="phone" placeholder="08123456789" value="{{ old('phone') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <i class="fa-solid fa-plus mr-1"></i> Simpan Data Dosen
                </button>
            </div>
        </form>
    </div>

    <!-- Lecturers Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">NIDN</th>
                        <th class="py-3.5 px-6">Nama Dosen</th>
                        <th class="py-3.5 px-6">Email</th>
                        <th class="py-3.5 px-6">Gelar</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lecturers as $lec)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $lec->nidn }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ $lec->name }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $lec->user->email ?? '-' }}</td>
                            <td class="py-4 px-6 text-xs text-slate-500">{{ $lec->academic_degree ?? '-' }}</td>
                            <td class="py-4 px-6 text-center">
                                <form method="POST" action="{{ route('admin.lecturers.delete', $lec->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
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
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
