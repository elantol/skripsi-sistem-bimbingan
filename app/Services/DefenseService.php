<?php

namespace App\Services;

use App\Models\Defense;
use App\Models\DefenseScore;
use Carbon\Carbon;

class DefenseService
{
    /**
     * Calculate individual weighted score for an examiner based on 4 criteria:
     * - Presentation: 20%
     * - Manuscript/Thesis Writing: 25%
     * - Mastery of Subject: 35%
     * - Software/System Product: 20%
     */
    public function calculateWeightedScore(float $presentation, float $manuscript, float $mastery, float $product): float
    {
        $weighted = ($presentation * 0.20) + ($manuscript * 0.25) + ($mastery * 0.35) + ($product * 0.20);
        return round($weighted, 2);
    }

    /**
     * Map numeric score (0 - 100) to Letter Grade.
     */
    public function determineGrade(float $score): string
    {
        if ($score >= 85.0) {
            return 'A';
        } elseif ($score >= 70.0) {
            return 'B';
        } elseif ($score >= 55.0) {
            return 'C';
        } elseif ($score >= 40.0) {
            return 'D';
        } else {
            return 'E';
        }
    }

    /**
     * Recalculate average final score from all examiners' score entries.
     */
    public function recalculateDefenseFinalScore(Defense $defense): Defense
    {
        $scores = DefenseScore::where('defense_id', $defense->id)->get();

        if ($scores->isEmpty()) {
            return $defense;
        }

        $avgScore = round($scores->avg('total_score'), 2);
        $grade = $this->determineGrade($avgScore);

        $status = $defense->status;
        if ($avgScore >= 70.0) {
            $status = 'LULUS';
        } elseif ($avgScore >= 55.0) {
            $status = 'LULUS_REVISI';
        } else {
            $status = 'TIDAK_LULUS';
        }

        $defense->update([
            'final_score' => $avgScore,
            'grade' => $grade,
            'status' => $status,
            'completed_at' => now(),
        ]);

        return $defense;
    }

    /**
     * Prepare data array for BAP PDF rendering.
     */
    public function getBapData(Defense $defense): array
    {
        $defense->load(['student.user', 'supervision', 'examiners.lecturer.user', 'scores.lecturer.user']);

        $examiners = $defense->examiners;
        $scores = $defense->scores->keyBy('lecturer_id');

        return [
            'defense' => $defense,
            'student' => $defense->student,
            'supervision' => $defense->supervision,
            'examiners' => $examiners,
            'scores' => $scores,
            'formatted_date' => $defense->scheduled_date ? Carbon::parse($defense->scheduled_date)->locale('id')->isoFormat('D MMMM Y') : '-',
            'printed_at' => Carbon::now()->locale('id')->isoFormat('D MMMM Y HH:mm'),
        ];
    }
}
