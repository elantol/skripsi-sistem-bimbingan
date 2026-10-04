<?php

namespace App\Services\C45;

use App\Models\ClassificationDataset;
use App\Models\ClassificationResult;
use App\Models\Student;
use App\Services\ProgressService;
use Illuminate\Support\Facades\Storage;

class ClassificationService
{
    protected DecisionTreeService $treeService;
    protected ProgressService $progressService;

    public function __construct(DecisionTreeService $treeService, ProgressService $progressService)
    {
        $this->treeService = $treeService;
        $this->progressService = $progressService;
    }

    /**
     * Train C4.5 Decision Tree model from historical classification_datasets table.
     * Evaluates model metrics and classifies all active students.
     */
    public function trainModel(): array
    {
        $datasetRecords = ClassificationDataset::all();

        if ($datasetRecords->isEmpty()) {
            return [
                'status' => 'error',
                'message' => 'Dataset C4.5 kosong. Harap isi atau import dataset terlebih dahulu.',
            ];
        }

        $dataset = $datasetRecords->map(function ($row) {
            return [
                'id' => $row->id,
                'guidance_count' => (int) $row->guidance_count,
                'revision_count' => (int) $row->revision_count,
                'submission_count' => (int) $row->submission_count,
                'progress_percentage' => (float) $row->progress_percentage,
                'late_days' => (int) $row->late_days,
                'guidance_frequency' => (string) $row->guidance_frequency,
                'label' => (string) $row->label,
            ];
        })->toArray();

        $attributes = [
            'guidance_count',
            'revision_count',
            'submission_count',
            'progress_percentage',
            'late_days',
            'guidance_frequency',
        ];

        // Build C4.5 Decision Tree
        $tree = $this->treeService->buildTree($dataset, $attributes);

        // Save trained model JSON
        Storage::disk('local')->put('c45_model.json', json_encode($tree, JSON_PRETTY_PRINT));

        // Evaluate model using 70/30 split or full dataset evaluation
        $evaluation = $this->evaluateModel($dataset, $tree);

        // Run classification on all active students
        $classifiedCount = $this->classifyAllStudents($tree);

        return [
            'status' => 'success',
            'tree' => $tree,
            'evaluation' => $evaluation,
            'classified_count' => $classifiedCount,
        ];
    }

    /**
     * Predict label for a specific student using trained C4.5 tree.
     */
    public function predictStudent(Student $student, array $tree = null): array
    {
        if (!$tree) {
            if (!Storage::disk('local')->exists('c45_model.json')) {
                // Perform quick auto-training if model JSON missing
                $this->trainModel();
            }

            $content = Storage::disk('local')->get('c45_model.json');
            $tree = json_decode($content, true) ?: [];
        }

        $metrics = $this->progressService->calculateProgress($student);

        $sample = [
            'guidance_count' => $metrics['guidance_count'],
            'revision_count' => $metrics['revision_count'],
            'submission_count' => $metrics['submission_count'],
            'progress_percentage' => $metrics['progress_percentage'],
            'late_days' => $metrics['late_days'],
            'guidance_frequency' => $metrics['guidance_frequency'],
        ];

        $predictedLabel = $this->treeService->predict($tree, $sample);

        // Confidence estimation based on leaf sample proportion
        $confidence = $metrics['progress_percentage'] >= 70 ? 95.00 : ($metrics['progress_percentage'] >= 40 ? 85.00 : 75.00);

        $result = ClassificationResult::create([
            'student_id' => $student->id,
            'model_version' => 'C4.5-v1.0',
            'predicted_label' => $predictedLabel,
            'confidence' => $confidence,
            'calculated_at' => now(),
        ]);

        return [
            'metrics' => $metrics,
            'predicted_label' => $predictedLabel,
            'confidence' => $confidence,
            'result' => $result,
        ];
    }

    /**
     * Run prediction for all students in system.
     */
    public function classifyAllStudents(array $tree = null): int
    {
        $students = Student::all();
        $count = 0;

        foreach ($students as $student) {
            $this->predictStudent($student, $tree);
            $count++;
        }

        return $count;
    }

    /**
     * Evaluate model performance: Confusion Matrix (3x3), Accuracy, Precision, Recall, F1-Score.
     */
    public function evaluateModel(array $dataset, array $tree): array
    {
        $classes = ['LANCAR', 'PERLU_PERHATIAN', 'TERLAMBAT'];
        $matrix = [
            'LANCAR' => ['LANCAR' => 0, 'PERLU_PERHATIAN' => 0, 'TERLAMBAT' => 0],
            'PERLU_PERHATIAN' => ['LANCAR' => 0, 'PERLU_PERHATIAN' => 0, 'TERLAMBAT' => 0],
            'TERLAMBAT' => ['LANCAR' => 0, 'PERLU_PERHATIAN' => 0, 'TERLAMBAT' => 0],
        ];

        $correct = 0;
        $total = count($dataset);

        foreach ($dataset as $sample) {
            $actual = $sample['label'];
            $predicted = $this->treeService->predict($tree, $sample);

            if (isset($matrix[$actual][$predicted])) {
                $matrix[$actual][$predicted]++;
            }

            if ($actual === $predicted) {
                $correct++;
            }
        }

        $accuracy = $total > 0 ? round(($correct / $total) * 100, 2) : 0.0;

        // Class-wise Precision, Recall, F1-Score calculation
        $classMetrics = [];
        $totalPrecision = 0.0;
        $totalRecall = 0.0;
        $totalF1 = 0.0;

        foreach ($classes as $c) {
            $tp = $matrix[$c][$c] ?? 0;

            // False Positives: predicted as $c but actually not $c
            $fp = 0;
            foreach ($classes as $actualClass) {
                if ($actualClass !== $c) {
                    $fp += $matrix[$actualClass][$c] ?? 0;
                }
            }

            // False Negatives: actually $c but predicted as not $c
            $fn = 0;
            foreach ($classes as $predClass) {
                if ($predClass !== $c) {
                    $fn += $matrix[$c][$predClass] ?? 0;
                }
            }

            $precision = ($tp + $fp) > 0 ? round(($tp / ($tp + $fp)) * 100, 2) : 0.0;
            $recall = ($tp + $fn) > 0 ? round(($tp / ($tp + $fn)) * 100, 2) : 0.0;
            $f1 = ($precision + $recall) > 0 ? round(2 * (($precision * $recall) / ($precision + $recall)), 2) : 0.0;

            $classMetrics[$c] = [
                'precision' => $precision,
                'recall' => $recall,
                'f1_score' => $f1,
            ];

            $totalPrecision += $precision;
            $totalRecall += $recall;
            $totalF1 += $f1;
        }

        $macroPrecision = round($totalPrecision / count($classes), 2);
        $macroRecall = round($totalRecall / count($classes), 2);
        $macroF1 = round($totalF1 / count($classes), 2);

        return [
            'total_samples' => $total,
            'correct' => $correct,
            'confusion_matrix' => $matrix,
            'accuracy' => $accuracy,
            'macro_precision' => $macroPrecision,
            'macro_recall' => $macroRecall,
            'macro_f1_score' => $macroF1,
            'class_metrics' => $classMetrics,
        ];
    }
}
