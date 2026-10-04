<?php

namespace App\Services\C45;

class EntropyService
{
    /**
     * Calculate entropy of dataset S based on target label column.
     * Entropy(S) = - sum( p_i * log2(p_i) )
     *
     * @param array $dataset List of associative arrays representing samples
     * @param string $labelColumn Name of target class column (e.g. 'label')
     * @return float
     */
    public function calculateEntropy(array $dataset, string $labelColumn = 'label'): float
    {
        $totalCount = count($dataset);
        if ($totalCount === 0) {
            return 0.0;
        }

        $classCounts = [];
        foreach ($dataset as $row) {
            $label = $row[$labelColumn] ?? 'UNKNOWN';
            $classCounts[$label] = ($classCounts[$label] ?? 0) + 1;
        }

        $entropy = 0.0;
        foreach ($classCounts as $count) {
            $p = $count / $totalCount;
            if ($p > 0) {
                $entropy -= $p * (log($p, 2));
            }
        }

        return round($entropy, 4);
    }
}
