@if(($node['type'] ?? '') === 'leaf')
    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl border-2 font-bold text-xs shadow-sm transition
        @if(($node['value'] ?? '') === 'LANCAR') bg-emerald-50 text-emerald-800 border-emerald-300
        @elseif(($node['value'] ?? '') === 'PERLU_PERHATIAN') bg-amber-50 text-amber-800 border-amber-300
        @else bg-rose-50 text-rose-800 border-rose-300 @endif">
        <i class="fa-solid 
            @if(($node['value'] ?? '') === 'LANCAR') fa-circle-check text-emerald-600 text-base
            @elseif(($node['value'] ?? '') === 'PERLU_PERHATIAN') fa-triangle-exclamation text-amber-600 text-base
            @else fa-circle-xmark text-rose-600 text-base @endif"></i>
        <span>KEPUTUSAN: {{ ($node['value'] ?? '') === 'PERLU_PERHATIAN' ? 'PERLU DIPERHATIKAN' : str_replace('_', ' ', $node['value'] ?? 'N/A') }}</span>
    </div>
@elseif(($node['type'] ?? '') === 'node')
    <div class="space-y-3">
        <!-- Node Header Card -->
        <div class="inline-flex items-center gap-3 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-xs font-medium shadow-md">
            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-300 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-code-branch"></i>
            </div>
            <div>
                <div class="font-bold text-sm text-slate-100 uppercase tracking-wide">
                    Atribut Pengujian: <span class="text-indigo-400">{{ Str::headline($node['attribute']) }}</span>
                </div>
                <div class="text-[11px] text-slate-400 flex items-center gap-3 mt-0.5">
                    @if(isset($node['threshold']))
                        <span>Nilai Ambang (Threshold): <strong class="text-amber-300">{{ $node['threshold'] }}</strong></span>
                    @endif
                    <span>Gain Ratio: <strong class="text-emerald-400">{{ number_format($node['gain_ratio'] ?? 0, 4) }}</strong></span>
                    <span>Total Sampel: <strong>{{ $node['samples'] ?? 0 }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Children Branches -->
        @if(!empty($node['children']))
            <div class="pl-6 border-l-2 border-indigo-200 space-y-4 ml-4 pt-1">
                @foreach($node['children'] as $branchKey => $childNode)
                    <div class="relative">
                        <!-- Connector Line Label -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-900 text-xs font-bold border border-indigo-200 mb-2 shadow-xs">
                            <i class="fa-solid fa-right-long text-indigo-500"></i>
                            <span>Jika {{ Str::headline($node['attribute']) }} {{ $branchKey }} {{ $node['threshold'] ?? '' }}</span>
                        </div>
                        <div class="ml-2">
                            @include('admin.classification.tree-node', ['node' => $childNode])
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif
