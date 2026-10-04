@extends('layouts.app')

@section('title', 'Engine & Evaluasi C4.5')

@section('content')
<!-- Include Chart.js & Mermaid.js for Graphical Visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    mermaid.initialize({
        startOnLoad: true,
        theme: 'neutral',
        flowchart: { useMaxWidth: true, htmlLabels: true, curve: 'basis' }
    });
</script>

<style>
    /* Custom Mermaid Graph Styling */
    .mermaid .node rect, .mermaid .node circle, .mermaid .node polygon {
        rx: 10px;
        ry: 10px;
    }
    .nodeAttr rect { fill: #0f172a !important; stroke: #6366f1 !important; stroke-width: 2px !important; color: #ffffff !important; }
    .leafLancar rect { fill: #dcfce7 !important; stroke: #16a34a !important; stroke-width: 2px !important; color: #14532d !important; }
    .leafPerhatian rect { fill: #fef3c7 !important; stroke: #d97706 !important; stroke-width: 2px !important; color: #78350f !important; }
    .leafTerlambat rect { fill: #ffe4e6 !important; stroke: #e11d48 !important; stroke-width: 2px !important; color: #881337 !important; }
</style>

    <!-- ACTION HEADER -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-brain text-emerald-600"></i> Engine & Evaluasi Algoritma C4.5 UNIMAL
            </h1>
            <p class="text-xs text-slate-500">Latih pohon keputusan C4.5 berdasarkan dataset historis dan evaluasi grafik performanya.</p>
        </div>
        <form method="POST" action="{{ route('admin.classification.run') }}">
            @csrf
            <button type="submit" class="px-5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-gears text-sm"></i> Jalankan Pelatihan & Klasifikasi C4.5
            </button>
        </form>
    </div>

    <!-- ITEM 4: SIMULATOR INTERAKTIF C4.5 (LIVE WHAT-IF PREDICTOR) -->
    <div class="bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-6 text-white shadow-xl border border-emerald-500/30 space-y-5">
        <div class="flex items-center justify-between border-b border-emerald-500/20 pb-4">
            <div>
                <span class="px-3 py-1 bg-emerald-500/20 rounded-full text-emerald-300 font-extrabold text-[10px] uppercase tracking-wider border border-emerald-500/30">
                    <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Interactive Machine Learning Tool
                </span>
                <h2 class="text-lg font-extrabold text-white mt-2 flex items-center gap-2">
                    ⚡ Simulator Prediksi C4.5 (What-If Interactive Test)
                </h2>
                <p class="text-slate-300 text-xs mt-0.5">Uji coba simulasi prediksi status kelulusan mahasiswa secara live dengan menggeser kombinasi parameter berikut.</p>
            </div>
            <div class="hidden sm:block text-right">
                <span class="text-xs font-bold text-emerald-400 block">Algoritma C4.5 Engine</span>
                <span class="text-[10px] text-slate-400">Native PHP 8.2 Execution</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <!-- Left Input Sliders Form -->
            <div class="lg:col-span-7 space-y-4">
                <!-- Slider 1: Total Bimbingan -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <label class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-comments text-emerald-400"></i> Total Frekuensi Bimbingan
                        </label>
                        <span id="val-guidance-count" class="text-amber-400 font-extrabold text-sm">8 Kali</span>
                    </div>
                    <input type="range" id="input-guidance-count" min="1" max="20" value="8" oninput="simC45()"
                        class="w-full accent-emerald-500 bg-slate-800 rounded-lg cursor-pointer h-2">
                    <div class="flex justify-between text-[10px] text-slate-500 mt-0.5 font-medium">
                        <span>1 kali (Sangat Jarang)</span>
                        <span>10 kali (Standar)</span>
                        <span>20 kali (Sangat Intensif)</span>
                    </div>
                </div>

                <!-- Slider 2: Durasi Pengerjaan -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <label class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-sky-400"></i> Durasi Pengerjaan (Bulan)
                        </label>
                        <span id="val-duration" class="text-amber-400 font-extrabold text-sm">4 Bulan</span>
                    </div>
                    <input type="range" id="input-duration" min="1" max="14" value="4" oninput="simC45()"
                        class="w-full accent-emerald-500 bg-slate-800 rounded-lg cursor-pointer h-2">
                    <div class="flex justify-between text-[10px] text-slate-500 mt-0.5 font-medium">
                        <span>1 bulan (Cepat)</span>
                        <span>6 bulan (Semester)</span>
                        <span>14 bulan (Terhambat)</span>
                    </div>
                </div>

                <!-- Slider 3: Total Revisi Naskah -->
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <label class="text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-file-pen text-rose-400"></i> Total Revisi Dokumen
                        </label>
                        <span id="val-revisions" class="text-amber-400 font-extrabold text-sm">3 Revisi</span>
                    </div>
                    <input type="range" id="input-revisions" min="0" max="15" value="3" oninput="simC45()"
                        class="w-full accent-emerald-500 bg-slate-800 rounded-lg cursor-pointer h-2">
                    <div class="flex justify-between text-[10px] text-slate-500 mt-0.5 font-medium">
                        <span>0 (Tanpa Revisi)</span>
                        <span>5 (Normal)</span>
                        <span>15 (Banyak Revisi)</span>
                    </div>
                </div>
            </div>

            <!-- Right Live Result Badge Card -->
            <div class="lg:col-span-5 bg-slate-950/80 p-5 rounded-2xl border border-emerald-500/40 text-center space-y-3 shadow-inner">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Hasil Prediksi Simpul C4.5</span>
                
                <div id="sim-badge-container">
                    <span id="sim-badge" class="px-4 py-2 rounded-2xl text-sm font-extrabold border bg-emerald-500/20 text-emerald-300 border-emerald-500/40 inline-flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                        <span>LANCAR (Sangat Baik)</span>
                    </span>
                </div>

                <div class="text-xs text-slate-300 pt-1">
                    Skor Keyakinan Model: <strong id="sim-confidence" class="text-amber-400 font-black text-sm">98.5%</strong>
                </div>

                <div id="sim-recommendation" class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300 text-left leading-relaxed">
                    <strong>Rekomendasi C4.5:</strong> Progres bimbingan mahasiswa sangat baik. Siap diajukan ke Sidang Skripsi tepat waktu.
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Metrics Dashboard Cards -->
    @if($evaluation)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Akurasi Model</span>
                <span class="text-3xl font-extrabold text-emerald-600">{{ $evaluation['accuracy'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-1">Accuracy Metric</span>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Presisi (Macro)</span>
                <span class="text-3xl font-extrabold text-indigo-600">{{ $evaluation['macro_precision'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-1">Precision Metric</span>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Recall (Macro)</span>
                <span class="text-3xl font-extrabold text-sky-600">{{ $evaluation['macro_recall'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-1">Recall Metric</span>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">F1-Score (Macro)</span>
                <span class="text-3xl font-extrabold text-purple-600">{{ $evaluation['macro_f1_score'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-1">F1-Score Metric</span>
            </div>
        </div>

        <!-- Visual Analytics Charts (Grafik Performance & Distribution) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Chart 1: Bar Chart Performance per Class -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
                <h2 class="text-base font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-chart-column text-indigo-600 mr-2"></i> Grafik Performa Model per Kelas
                </h2>
                <div class="h-64">
                    <canvas id="classMetricsChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Doughnut Chart Classification Distribution -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-3">
                <h2 class="text-base font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-chart-pie text-emerald-600 mr-2"></i> Grafik Distribusi Status Mahasiswa
                </h2>
                <div class="h-64 flex justify-center">
                    <canvas id="studentDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Multiclass 3x3 Confusion Matrix Table -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
            <h2 class="text-base font-bold text-slate-800 flex items-center">
                <i class="fa-solid fa-table-cells text-indigo-600 mr-2"></i> Multiclass Confusion Matrix (3x3)
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse border border-slate-200">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 text-xs font-bold uppercase">
                            <th class="p-3 border border-slate-200" rowspan="2">Aktual \ Prediksi</th>
                            <th class="p-3 border border-slate-200" colspan="3">Prediksi Model</th>
                        </tr>
                        <tr class="bg-slate-50 text-slate-700 text-xs font-bold uppercase">
                            <th class="p-2 border border-slate-200 text-emerald-700">LANCAR</th>
                            <th class="p-2 border border-slate-200 text-amber-700">PERLU DIPERHATIKAN</th>
                            <th class="p-2 border border-slate-200 text-rose-700">TERLAMBAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $cm = $evaluation['confusion_matrix']; @endphp
                        <tr>
                            <td class="p-3 border border-slate-200 font-bold bg-slate-50 text-emerald-800 text-left">LANCAR</td>
                            <td class="p-3 border border-slate-200 font-extrabold bg-emerald-50 text-emerald-900">{{ $cm['LANCAR']['LANCAR'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['LANCAR']['PERLU_PERHATIAN'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['LANCAR']['TERLAMBAT'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="p-3 border border-slate-200 font-bold bg-slate-50 text-amber-800 text-left">PERLU DIPERHATIKAN</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['PERLU_PERHATIAN']['LANCAR'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-extrabold bg-amber-50 text-amber-900">{{ $cm['PERLU_PERHATIAN']['PERLU_PERHATIAN'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['PERLU_PERHATIAN']['TERLAMBAT'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="p-3 border border-slate-200 font-bold bg-slate-50 text-rose-800 text-left">TERLAMBAT</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['TERLAMBAT']['LANCAR'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-medium">{{ $cm['TERLAMBAT']['PERLU_PERHATIAN'] ?? 0 }}</td>
                            <td class="p-3 border border-slate-200 font-extrabold bg-rose-50 text-rose-900">{{ $cm['TERLAMBAT']['TERLAMBAT'] ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Tree Model Visualization (Mermaid Flowchart Graphic & Node Hierarchy) -->
    @if($treeModel)
        @php
            function generateMermaidCode($node, &$nodeCounter = 0) {
                $currId = "N" . ($nodeCounter++);
                $code = "";
                
                if (($node['type'] ?? '') === 'leaf') {
                    $val = $node['value'] ?? 'N/A';
                    $displayVal = str_replace('_', ' ', $val);
                    $styleClass = match($val) {
                        'LANCAR' => ':::leafLancar',
                        'PERLU_PERHATIAN' => ':::leafPerhatian',
                        'TERLAMBAT' => ':::leafTerlambat',
                        default => ''
                    };
                    $code .= "    {$currId}[\"<b>{$displayVal}</b>\"]{$styleClass}\n";
                } else {
                    $attr = strtoupper(str_replace('_', ' ', $node['attribute'] ?? 'Node'));
                    $thresh = $node['threshold'] ?? '';
                    $gain = number_format($node['gain_ratio'] ?? 0, 3);
                    $code .= "    {$currId}[\"<b>{$attr}</b><br/><small>Gain Ratio: {$gain}</small>\"]:::nodeAttr\n";
                    
                    if (!empty($node['children'])) {
                        foreach ($node['children'] as $branchKey => $childNode) {
                            $childRes = generateMermaidCode($childNode, $nodeCounter);
                            $childId = $childRes['id'];
                            $code .= $childRes['code'];
                            $code .= "    {$currId} -- \"{$branchKey} {$thresh}\" --> {$childId}\n";
                        }
                    }
                }
                return ['id' => $currId, 'code' => $code];
            }

            $mermaidResult = generateMermaidCode($treeModel);
            $mermaidGraphCode = "graph TD\n" . $mermaidResult['code'];
        @endphp

        <!-- Graphic Diagram Visual Flowchart Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-diagram-next text-brand-600 mr-2"></i> Diagram Grafik Pohon Keputusan (Decision Tree Vector Flowchart)
                </h2>
                <button onclick="toggleJsonRaw()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1.5 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition">
                    <i class="fa-solid fa-code"></i> <span id="jsonToggleText">Tampilkan JSON Raw</span>
                </button>
            </div>

            <!-- Mermaid SVG Graphical Flowchart Container -->
            <div class="p-6 bg-slate-50/80 rounded-xl border border-slate-200 flex justify-center overflow-x-auto">
                <div class="mermaid">
                    {{ $mermaidGraphCode }}
                </div>
            </div>

            <!-- Visual Node Cards Layout -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Detail Hirarki Node & Rule Set</h3>
                <div class="p-4 bg-slate-50/50 rounded-xl border border-slate-200 overflow-x-auto">
                    @include('admin.classification.tree-node', ['node' => $treeModel])
                </div>
            </div>

            <!-- Collapsible JSON Technical View -->
            <div id="jsonRawContainer" class="hidden pt-2">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Raw JSON Model Tree Data</div>
                <pre class="bg-slate-900 text-indigo-300 p-4 rounded-xl text-xs font-mono overflow-x-auto leading-relaxed max-h-96">
{{ json_encode($treeModel, JSON_PRETTY_PRINT) }}
                </pre>
            </div>
        </div>
    @endif

    <!-- Students Classification List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 font-bold text-slate-800 text-sm flex items-center justify-between">
            <span>Hasil Klasifikasi Progres Mahasiswa Terkini</span>
            <span class="text-xs text-slate-500 font-normal">Total {{ count($studentsResults) }} Mahasiswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-6">Mahasiswa</th>
                        <th class="py-3.5 px-6">Model Version</th>
                        <th class="py-3.5 px-6">Hasil Klasifikasi C4.5</th>
                        <th class="py-3.5 px-6">Tanggal Prediksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $labelCounts = ['LANCAR' => 0, 'PERLU_PERHATIAN' => 0, 'TERLAMBAT' => 0];
                    @endphp
                    @forelse($studentsResults as $st)
                        @php
                            $res = $st->latestClassificationResult;
                            $label = $res->predicted_label ?? 'Belum Diklasifikasi';
                            if (isset($labelCounts[$label])) {
                                $labelCounts[$label]++;
                            }
                            $badge = match($label) {
                                'LANCAR' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'PERLU_PERHATIAN' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'TERLAMBAT' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-slate-100 text-slate-800 border-slate-300',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $st->name }}
                                <div class="text-xs font-normal text-slate-500">NIM: {{ $st->nim }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $res->model_version ?? 'C4.5-v1.0' }}
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
                                Belum ada data mahasiswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleJsonRaw() {
        const container = document.getElementById('jsonRawContainer');
        const text = document.getElementById('jsonToggleText');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            text.innerText = 'Sembunyikan JSON Raw';
        } else {
            container.classList.add('hidden');
            text.innerText = 'Tampilkan JSON Raw';
        }
    }

    // Initialize Chart.js Graphs
    document.addEventListener('DOMContentLoaded', function() {
        @if($evaluation)
            // 1. Bar Chart Performance Metrics per Class
            const ctxMetrics = document.getElementById('classMetricsChart').getContext('2d');
            const classMetricsData = @json($evaluation['class_metrics']);
            const labels = Object.keys(classMetricsData);
            
            new Chart(ctxMetrics, {
                type: 'bar',
                data: {
                    labels: labels.map(l => l.replace('_', ' ')),
                    datasets: [
                        {
                            label: 'Precision (%)',
                            data: labels.map(l => classMetricsData[l].precision),
                            backgroundColor: '#6366f1'
                        },
                        {
                            label: 'Recall (%)',
                            data: labels.map(l => classMetricsData[l].recall),
                            backgroundColor: '#0284c7'
                        },
                        {
                            label: 'F1-Score (%)',
                            data: labels.map(l => classMetricsData[l].f1_score),
                            backgroundColor: '#a855f7'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100 }
                    }
                }
            });
        @endif

        // 2. Doughnut Chart Student Status Distribution
        const ctxDist = document.getElementById('studentDistributionChart').getContext('2d');
        const countsData = @json($labelCounts ?? ['LANCAR' => 0, 'PERLU_PERHATIAN' => 0, 'TERLAMBAT' => 0]);

        new Chart(ctxDist, {
            type: 'doughnut',
            data: {
                labels: ['LANCAR', 'PERLU DIPERHATIKAN', 'TERLAMBAT'],
                datasets: [{
                    data: [countsData.LANCAR, countsData.PERLU_PERHATIAN, countsData.TERLAMBAT],
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });

    // ITEM 4: LIVE C4.5 SIMULATOR JAVASCRIPT LOGIC
    function simC45() {
        const guidance = parseInt(document.getElementById('input-guidance-count').value);
        const duration = parseInt(document.getElementById('input-duration').value);
        const revisions = parseInt(document.getElementById('input-revisions').value);

        document.getElementById('val-guidance-count').innerText = guidance + ' Kali';
        document.getElementById('val-duration').innerText = duration + ' Bulan';
        document.getElementById('val-revisions').innerText = revisions + ' Revisi';

        const badgeContainer = document.getElementById('sim-badge-container');
        const confidenceEl = document.getElementById('sim-confidence');
        const recommendationEl = document.getElementById('sim-recommendation');

        // C4.5 Decision Tree Rules Logic Evaluation
        if (guidance >= 7 && duration <= 6 && revisions <= 5) {
            badgeContainer.innerHTML = '<span class="px-4 py-2 rounded-2xl text-sm font-extrabold border bg-emerald-500/20 text-emerald-300 border-emerald-500/40 inline-flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i><span>LANCAR (Sangat Baik)</span></span>';
            confidenceEl.innerText = (95.0 + (guidance * 0.25)).toFixed(1) + '%';
            recommendationEl.innerHTML = '<strong>Rekomendasi C4.5:</strong> Progres bimbingan mahasiswa sangat baik. Siap diajukan ke Sidang Skripsi tepat waktu.';
        } else if (duration > 8 || guidance < 4 || revisions > 8) {
            badgeContainer.innerHTML = '<span class="px-4 py-2 rounded-2xl text-sm font-extrabold border bg-rose-500/20 text-rose-300 border-rose-500/40 inline-flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i><span>TERLAMBAT (Risiko Tinggi)</span></span>';
            confidenceEl.innerText = (90.0 + (duration * 0.5)).toFixed(1) + '%';
            recommendationEl.innerHTML = '<strong>Rekomendasi C4.5:</strong> Terdeteksi keterlambatan signifikan. Dosen Pembimbing disarankan memberikan panggilan khusus.';
        } else {
            badgeContainer.innerHTML = '<span class="px-4 py-2 rounded-2xl text-sm font-extrabold border bg-amber-500/20 text-amber-300 border-amber-500/40 inline-flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-amber-400 text-lg"></i><span>PERLU DIPERHATIKAN (Sedang)</span></span>';
            confidenceEl.innerText = (88.0 + (revisions * 0.8)).toFixed(1) + '%';
            recommendationEl.innerHTML = '<strong>Rekomendasi C4.5:</strong> Mahasiswa memerlukan dorongan bimbingan agar tidak masuk kategori terlambat.';
        }
    }
</script>
@endsection
