@extends('layouts.app')

@section('title', 'Mahasiswa Bimbingan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Daftar Mahasiswa Bimbingan Saya</h1>
        <p class="text-xs text-slate-500">Monitoring status progres dan hasil prediksi algoritma C4.5 mahasiswa bimbingan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Peran Pembimbing</th>
                        <th class="py-3.5 px-6">Progres (%)</th>
                        <th class="py-3.5 px-6">Late Days</th>
                        <th class="py-3.5 px-6">Klasifikasi C4.5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($studentsData as $item)
                        @php
                            $student = $item['student'];
                            $metrics = $item['metrics'];
                            $classification = $item['classification'];
                            $label = $classification->predicted_label ?? 'PERLU_PERHATIAN';
                            $badge = match($label) {
                                'LANCAR' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'TERLAMBAT' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-slate-100 text-slate-800 border-slate-300',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                <div>{{ $student->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500 font-medium">NIM: {{ $student->nim ?? '-' }} &bull; {{ $student->study_program ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs font-bold uppercase text-indigo-600">
                                {{ str_replace('_', ' ', $item['assignment']->supervisor_type) }}
                            </td>
                            <td class="py-4 px-6 font-extrabold text-slate-800">
                                {{ $metrics['progress_percentage'] ?? 0 }}%
                            </td>
                            <td class="py-4 px-6 text-xs font-bold text-slate-700">
                                {{ $metrics['late_days'] ?? 0 }} hari
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold border {{ $badge }}">
                                    {{ $label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $label) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                Belum ada mahasiswa bimbingan yang ditugaskan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
