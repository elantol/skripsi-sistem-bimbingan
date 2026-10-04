@extends('layouts.app')

@section('title', 'Hasil Klasifikasi C4.5')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Hasil Klasifikasi Progres Mahasiswa (C4.5)</h1>
        <p class="text-xs text-slate-500">Hasil prediksi label progres mahasiswa berdasarkan decision tree C4.5.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Peran</th>
                        <th class="py-3.5 px-6">Label Prediksi C4.5</th>
                        <th class="py-3.5 px-6">Tanggal Kalkulasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assignments as $a)
                        @php
                            $student = $a->student;
                            $res = $student->latestClassificationResult ?? null;
                            $label = $res->predicted_label ?? 'PERLU_PERHATIAN';
                            $badge = match($label) {
                                'LANCAR' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'TERLAMBAT' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-slate-100 text-slate-800 border-slate-300',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $student->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $student->nim ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs font-bold uppercase text-indigo-600">
                                {{ str_replace('_', ' ', $a->supervisor_type) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold border {{ $badge }}">
                                    {{ $label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $label) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $res ? \Carbon\Carbon::parse($res->calculated_at)->isoFormat('D MMM Y, HH:mm') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">
                                Belum ada data hasil klasifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
