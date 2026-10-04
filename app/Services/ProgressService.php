<?php

namespace App\Services;

use App\Models\Student;
use App\Models\ProgressRecord;
use Carbon\Carbon;

class ProgressService
{
    /**
     * Calculate current student progress metrics based on system activity.
     */
    public function calculateProgress(Student $student): array
    {
        $supervision = $student->activeSupervision;

        if (!$supervision) {
            return [
                'guidance_count' => 0,
                'revision_count' => 0,
                'submission_count' => 0,
                'progress_percentage' => 0.00,
                'late_days' => 0,
                'guidance_frequency' => 'Rendah',
            ];
        }

        $requests = $supervision->guidanceRequests;

        $submissionCount = $requests->count();
        $guidanceCount = $requests->whereIn('status', ['DISETUJUI', 'TERJADWAL', 'SELESAI'])->count();
        $revisionCount = $requests->where('status', 'PERLU_REVISI')->count();

        // Calculate progress percentage based on completed guidance stages & latest guidance note
        $latestNote = $requests->flatMap->guidanceNotes->sortByDesc('created_at')->first();
        $progressPercentage = $latestNote ? (float) $latestNote->progress_percentage : 0.00;

        if ($progressPercentage == 0 && $guidanceCount > 0) {
            // Default stage-based progress calculation fallback
            $approvedTypes = $requests->whereIn('status', ['DISETUJUI', 'SELESAI'])->pluck('request_type')->toArray();
            if (in_array('laporan', $approvedTypes)) {
                $progressPercentage = 100.00;
            } elseif (in_array('bab_5', $approvedTypes)) {
                $progressPercentage = 90.00;
            } elseif (in_array('bab_4', $approvedTypes)) {
                $progressPercentage = 75.00;
            } elseif (in_array('bab_3', $approvedTypes)) {
                $progressPercentage = 60.00;
            } elseif (in_array('bab_2', $approvedTypes)) {
                $progressPercentage = 45.00;
            } elseif (in_array('bab_1', $approvedTypes)) {
                $progressPercentage = 30.00;
            } elseif (in_array('proposal', $approvedTypes)) {
                $progressPercentage = 20.00;
            }
        }

        // Calculate late days (days elapsed without progress beyond 14 days baseline)
        $startedAt = $supervision->started_at ? Carbon::parse($supervision->started_at) : Carbon::parse($supervision->created_at);
        $daysElapsed = (int) $startedAt->diffInDays(now());
        
        // Expected progress per month is 20%. Late days calculated if progress falls behind expected pace
        $expectedDays = ($progressPercentage > 0) ? (int) (($progressPercentage / 100) * 120) : 0;
        $lateDays = max(0, $daysElapsed - max(14, $expectedDays));

        $frequency = $this->getGuidanceFrequency($guidanceCount);

        return [
            'guidance_count' => $guidanceCount,
            'revision_count' => $revisionCount,
            'submission_count' => $submissionCount,
            'progress_percentage' => round($progressPercentage, 2),
            'late_days' => $lateDays,
            'guidance_frequency' => $frequency,
        ];
    }

    /**
     * Categorize guidance frequency.
     */
    public function getGuidanceFrequency(int $guidanceCount): string
    {
        if ($guidanceCount >= 8) {
            return 'Tinggi';
        } elseif ($guidanceCount >= 4) {
            return 'Sedang';
        }
        return 'Rendah';
    }

    /**
     * Snapshot student progress into progress_records table.
     */
    public function recordProgress(Student $student): ProgressRecord
    {
        $metrics = $this->calculateProgress($student);
        $supervision = $student->activeSupervision;

        return ProgressRecord::create([
            'student_id' => $student->id,
            'supervision_id' => $supervision ? $supervision->id : null,
            'guidance_count' => $metrics['guidance_count'],
            'revision_count' => $metrics['revision_count'],
            'submission_count' => $metrics['submission_count'],
            'progress_percentage' => $metrics['progress_percentage'],
            'late_days' => $metrics['late_days'],
            'recorded_at' => now(),
        ]);
    }
}
