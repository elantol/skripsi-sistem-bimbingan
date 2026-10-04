<?php

namespace App\Services\C45;

class GainRatioService
{
    protected EntropyService $entropyService;

    public function __construct(EntropyService $entropyService)
    {
        $this->entropyService = $entropyService;
    }

    /**
     * Calculate Information Gain for categorical attribute split.
     */
    public function calculateInformationGain(array $dataset, string $attribute, string $labelColumn = 'label'): float
    {
        $totalCount = count($dataset);
        if ($totalCount === 0) {
            return 0.0;
        }

        $systemEntropy = $this->entropyService->calculateEntropy($dataset, $labelColumn);

        // Group dataset by attribute values
        $subsets = [];
        foreach ($dataset as $row) {
            $val = $row[$attribute] ?? 'NULL';
            $subsets[$val][] = $row;
        }

        $subsetEntropySum = 0.0;
        foreach ($subsets as $subset) {
            $subsetCount = count($subset);
            $subsetEntropy = $this->entropyService->calculateEntropy($subset, $labelColumn);
            $subsetEntropySum += ($subsetCount / $totalCount) * $subsetEntropy;
        }

        return round($systemEntropy - $subsetEntropySum, 4);
    }

    /**
     * Calculate Split Information for categorical attribute split.
     * SplitInfo(S, A) = - sum( (|Sv| / |S|) * log2(|Sv| / |S|) )
     */
    public function calculateSplitInfo(array $dataset, string $attribute): float
    {
        $totalCount = count($dataset);
        if ($totalCount === 0) {
            return 0.0;
        }

        $subsets = [];
        foreach ($dataset as $row) {
            $val = $row[$attribute] ?? 'NULL';
            $subsets[$val] = ($subsets[$val] ?? 0) + 1;
        }

        $splitInfo = 0.0;
        foreach ($subsets as $count) {
            $p = $count / $totalCount;
            if ($p > 0) {
                $splitInfo -= $p * log($p, 2);
            }
        }

        return round($splitInfo, 4);
    }

    /**
     * Calculate Gain Ratio for categorical attribute split.
     */
    public function calculateGainRatio(array $dataset, string $attribute, string $labelColumn = 'label'): float
    {
        $gain = $this->calculateInformationGain($dataset, $attribute, $labelColumn);
        $splitInfo = $this->calculateSplitInfo($dataset, $attribute);

        if ($splitInfo == 0.0) {
            return 0.0;
        }

        return round($gain / $splitInfo, 4);
    }

    /**
     * Evaluate best binary split threshold for numerical attribute.
     * Returns ['threshold' => float, 'gain_ratio' => float, 'gain' => float, 'split_info' => float]
     */
    public function calculateBestNumericSplit(array $dataset, string $attribute, string $labelColumn = 'label'): array
    {
        $totalCount = count($dataset);
        if ($totalCount === 0) {
            return ['threshold' => null, 'gain_ratio' => 0.0, 'gain' => 0.0, 'split_info' => 0.0];
        }

        $systemEntropy = $this->entropyService->calculateEntropy($dataset, $labelColumn);

        // Extract and sort distinct numeric values
        $values = array_column($dataset, $attribute);
        sort($values);
        $uniqueValues = array_values(array_unique($values));

        if (count($uniqueValues) <= 1) {
            return ['threshold' => $uniqueValues[0] ?? 0, 'gain_ratio' => 0.0, 'gain' => 0.0, 'split_info' => 0.0];
        }

        $bestSplit = [
            'threshold' => null,
            'gain_ratio' => -1.0,
            'gain' => 0.0,
            'split_info' => 0.0,
        ];

        // Evaluate candidate midpoints
        for ($i = 0; $i < count($uniqueValues) - 1; $i++) {
            $threshold = ($uniqueValues[$i] + $uniqueValues[$i + 1]) / 2.0;

            $leftSubset = [];
            $rightSubset = [];

            foreach ($dataset as $row) {
                if ($row[$attribute] <= $threshold) {
                    $leftSubset[] = $row;
                } else {
                    $rightSubset[] = $row;
                }
            }

            $leftCount = count($leftSubset);
            $rightCount = count($rightSubset);

            if ($leftCount === 0 || $rightCount === 0) {
                continue;
            }

            $leftEntropy = $this->entropyService->calculateEntropy($leftSubset, $labelColumn);
            $rightEntropy = $this->entropyService->calculateEntropy($rightSubset, $labelColumn);

            $gain = $systemEntropy - (($leftCount / $totalCount) * $leftEntropy + ($rightCount / $totalCount) * $rightEntropy);

            $pLeft = $leftCount / $totalCount;
            $pRight = $rightCount / $totalCount;
            $splitInfo = - ($pLeft * log($pLeft, 2) + $pRight * log($pRight, 2));

            $gainRatio = ($splitInfo > 0) ? ($gain / $splitInfo) : 0.0;

            if ($gainRatio > $bestSplit['gain_ratio']) {
                $bestSplit = [
                    'threshold' => round($threshold, 2),
                    'gain_ratio' => round($gainRatio, 4),
                    'gain' => round($gain, 4),
                    'split_info' => round($splitInfo, 4),
                ];
            }
        }

        return $bestSplit;
    }
}
