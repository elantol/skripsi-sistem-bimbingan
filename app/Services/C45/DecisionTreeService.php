<?php

namespace App\Services\C45;

class DecisionTreeService
{
    protected GainRatioService $gainRatioService;
    protected EntropyService $entropyService;

    public function __construct(GainRatioService $gainRatioService, EntropyService $entropyService)
    {
        $this->gainRatioService = $gainRatioService;
        $this->entropyService = $entropyService;
    }

    /**
     * Recursively build C4.5 Decision Tree.
     *
     * @param array $dataset List of sample rows
     * @param array $attributes List of feature names to split on
     * @param array $numericAttributes List of attributes that should be treated as numerical
     * @param string $labelColumn Target label column
     * @param int $maxDepth Maximum tree depth to prevent overfitting
     * @param int $currentDepth Current recursion level
     * @return array Tree structure
     */
    public function buildTree(
        array $dataset,
        array $attributes,
        array $numericAttributes = ['guidance_count', 'revision_count', 'submission_count', 'progress_percentage', 'late_days'],
        string $labelColumn = 'label',
        int $maxDepth = 6,
        int $currentDepth = 0
    ): array {
        // Base Case 1: Empty dataset
        if (empty($dataset)) {
            return ['type' => 'leaf', 'value' => 'PERLU_PERHATIAN', 'samples' => 0];
        }

        // Extract class labels
        $labels = array_column($dataset, $labelColumn);
        $uniqueLabels = array_values(array_unique($labels));
        $majorityLabel = $this->getMajorityLabel($labels);

        // Base Case 2: All samples belong to the same class (pure node)
        if (count($uniqueLabels) === 1) {
            return ['type' => 'leaf', 'value' => $uniqueLabels[0], 'samples' => count($dataset)];
        }

        // Base Case 3: No attributes left or max depth reached
        if (empty($attributes) || $currentDepth >= $maxDepth) {
            return ['type' => 'leaf', 'value' => $majorityLabel, 'samples' => count($dataset)];
        }

        // Find attribute with highest Gain Ratio
        $best = $this->findBestAttribute($dataset, $attributes, $numericAttributes, $labelColumn);

        if ($best['gain_ratio'] <= 0 || $best['attribute'] === null) {
            return ['type' => 'leaf', 'value' => $majorityLabel, 'samples' => count($dataset)];
        }

        $bestAttribute = $best['attribute'];
        $isNumeric = $best['is_numeric'];

        if ($isNumeric) {
            $threshold = $best['threshold'];
            $leftSubset = [];
            $rightSubset = [];

            foreach ($dataset as $row) {
                if (($row[$bestAttribute] ?? 0) <= $threshold) {
                    $leftSubset[] = $row;
                } else {
                    $rightSubset[] = $row;
                }
            }

            return [
                'type' => 'node',
                'attribute' => $bestAttribute,
                'is_numeric' => true,
                'threshold' => $threshold,
                'gain_ratio' => $best['gain_ratio'],
                'samples' => count($dataset),
                'children' => [
                    '<=' => $this->buildTree($leftSubset, $attributes, $numericAttributes, $labelColumn, $maxDepth, $currentDepth + 1),
                    '>'  => $this->buildTree($rightSubset, $attributes, $numericAttributes, $labelColumn, $maxDepth, $currentDepth + 1),
                ]
            ];
        } else {
            // Categorical attribute split
            $remainingAttributes = array_values(array_diff($attributes, [$bestAttribute]));
            $subsets = [];
            foreach ($dataset as $row) {
                $val = $row[$bestAttribute] ?? 'NULL';
                $subsets[$val][] = $row;
            }

            $children = [];
            foreach ($subsets as $val => $subset) {
                $children[$val] = $this->buildTree($subset, $remainingAttributes, $numericAttributes, $labelColumn, $maxDepth, $currentDepth + 1);
            }

            return [
                'type' => 'node',
                'attribute' => $bestAttribute,
                'is_numeric' => false,
                'gain_ratio' => $best['gain_ratio'],
                'samples' => count($dataset),
                'children' => $children,
            ];
        }
    }

    /**
     * Find best attribute to split on using C4.5 Gain Ratio.
     */
    public function findBestAttribute(
        array $dataset,
        array $attributes,
        array $numericAttributes,
        string $labelColumn = 'label'
    ): array {
        $best = [
            'attribute' => null,
            'is_numeric' => false,
            'threshold' => null,
            'gain_ratio' => -1.0,
        ];

        foreach ($attributes as $attr) {
            if (in_array($attr, $numericAttributes)) {
                $res = $this->gainRatioService->calculateBestNumericSplit($dataset, $attr, $labelColumn);
                if ($res['gain_ratio'] > $best['gain_ratio']) {
                    $best = [
                        'attribute' => $attr,
                        'is_numeric' => true,
                        'threshold' => $res['threshold'],
                        'gain_ratio' => $res['gain_ratio'],
                    ];
                }
            } else {
                $gainRatio = $this->gainRatioService->calculateGainRatio($dataset, $attr, $labelColumn);
                if ($gainRatio > $best['gain_ratio']) {
                    $best = [
                        'attribute' => $attr,
                        'is_numeric' => false,
                        'threshold' => null,
                        'gain_ratio' => $gainRatio,
                    ];
                }
            }
        }

        return $best;
    }

    /**
     * Predict label for a single input sample by traversing the Decision Tree.
     */
    public function predict(array $tree, array $sample): string
    {
        if (($tree['type'] ?? '') === 'leaf') {
            return $tree['value'];
        }

        $attr = $tree['attribute'] ?? null;
        if (!$attr || !isset($sample[$attr])) {
            return 'PERLU_PERHATIAN';
        }

        $isNumeric = $tree['is_numeric'] ?? false;

        if ($isNumeric) {
            $threshold = $tree['threshold'];
            $sampleValue = (float) $sample[$attr];

            if ($sampleValue <= $threshold) {
                return $this->predict($tree['children']['<='] ?? ['type' => 'leaf', 'value' => 'PERLU_PERHATIAN'], $sample);
            } else {
                return $this->predict($tree['children']['>'] ?? ['type' => 'leaf', 'value' => 'PERLU_PERHATIAN'], $sample);
            }
        } else {
            $sampleValue = (string) $sample[$attr];
            if (isset($tree['children'][$sampleValue])) {
                return $this->predict($tree['children'][$sampleValue], $sample);
            }

            // Fallback to first available child leaf or default
            $firstChild = reset($tree['children']);
            return $firstChild ? $this->predict($firstChild, $sample) : 'PERLU_PERHATIAN';
        }
    }

    /**
     * Get majority label from array of class labels.
     */
    protected function getMajorityLabel(array $labels): string
    {
        if (empty($labels)) {
            return 'PERLU_PERHATIAN';
        }

        $counts = array_count_values($labels);
        arsort($counts);
        return array_key_first($counts);
    }
}
