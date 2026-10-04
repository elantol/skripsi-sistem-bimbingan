@extends('layouts.app')

@section('title', 'Dataset C4.5')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Manajemen Dataset Historis C4.5</h1>
        <p class="text-xs text-slate-500">Dataset pelatihan algoritma C4.5 untuk klasifikasi progres mahasiswa.</p>
    </div>

    <!-- CSV Import Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center">
            <i class="fa-solid fa-file-import text-brand-600 mr-2"></i> Impor Dataset Baru (CSV)
        </h2>

        <form method="POST" action="{{ route('admin.datasets.import') }}" enctype="multipart/form-data" class="flex flex-col md:flex-row items-end gap-4">
            @csrf
            <div class="flex-1">
                <label for="file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Berkas CSV Dataset</label>
                <input type="file" name="file" id="file" required accept=".csv,.txt"
                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer border border-slate-300 rounded-xl bg-slate-50">
            </div>

            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> Impor CSV
            </button>
        </form>
    </div>

    <!-- Dataset Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Guidance</th>
                        <th class="py-3.5 px-6">Revision</th>
                        <th class="py-3.5 px-6">Submission</th>
                        <th class="py-3.5 px-6">Progress (%)</th>
                        <th class="py-3.5 px-6">Late Days</th>
                        <th class="py-3.5 px-6">Frequency</th>
                        <th class="py-3.5 px-6">Label Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($datasets as $d)
                        <tr class="hover:bg-slate-50 transition text-xs font-medium">
                            <td class="py-3.5 px-6">{{ $d->guidance_count }}</td>
                            <td class="py-3.5 px-6">{{ $d->revision_count }}</td>
                            <td class="py-3.5 px-6">{{ $d->submission_count }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-800">{{ $d->progress_percentage }}%</td>
                            <td class="py-3.5 px-6">{{ $d->late_days }} hari</td>
                            <td class="py-3.5 px-6 font-semibold text-purple-700">{{ $d->guidance_frequency }}</td>
                            <td class="py-3.5 px-6">
                                @php
                                    $badge = match($d->label) {
                                        'LANCAR' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                        'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800 border-amber-300',
                                        'TERLAMBAT' => 'bg-rose-100 text-rose-800 border-rose-300',
                                        default => 'bg-slate-100 text-slate-800 border-slate-300',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-extrabold border {{ $badge }}">
                                    {{ $d->label === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $d->label) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">Dataset C4.5 masih kosong. Impor berkas CSV untuk memulai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($datasets, 'links'))
            <div class="p-4 border-t border-slate-100">{{ $datasets->links() }}</div>
        @endif
    </div>
</div>
@endsection
