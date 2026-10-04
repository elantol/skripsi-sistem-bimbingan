@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-6">
        <h1 class="text-xl font-bold text-slate-800">Profil Mahasiswa</h1>

        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">NIM</label>
                <input type="text" value="{{ $student->nim }}" disabled class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Program Studi & Angkatan</label>
                <input type="text" value="{{ $student->study_program }} ({{ $student->generation }})" disabled class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="08123456789" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
