<?php

namespace App\Http\Controllers;

use App\Models\Defense;
use App\Models\DefenseExaminer;
use App\Models\DefenseScore;
use App\Models\GuidanceRequest;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\Supervision;
use App\Services\DefenseService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DefenseController extends Controller
{
    protected DefenseService $defenseService;
    protected NotificationService $notificationService;

    public function __construct(DefenseService $defenseService, NotificationService $notificationService)
    {
        $this->defenseService = $defenseService;
        $this->notificationService = $notificationService;
    }

    // --- MAHASISWA (STUDENT) METHODS ---

    public function studentIndex(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $defenses = Defense::where('student_id', $student->id)
            ->with(['examiners.lecturer.user', 'scores'])
            ->latest()
            ->get();

        $activeSupervision = Supervision::where('student_id', $student->id)
            ->where('status', 'aktif')
            ->first();

        // Calculate completed guidance count
        $completedGuidanceCount = 0;
        if ($activeSupervision) {
            $completedGuidanceCount = GuidanceRequest::where('supervision_id', $activeSupervision->id)
                ->where('status', 'SELESAI')
                ->count();
        }

        $eligibleForDefense = $completedGuidanceCount >= 8;

        return view('student.defenses.index', compact('student', 'defenses', 'activeSupervision', 'completedGuidanceCount', 'eligibleForDefense'));
    }

    public function studentStore(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();
        $supervision = Supervision::where('student_id', $student->id)->where('status', 'aktif')->firstOrFail();

        $request->validate([
            'defense_type' => 'required|in:proposal,hasil,skripsi_akhir',
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
        ]);

        $defense = Defense::create([
            'student_id' => $student->id,
            'supervision_id' => $supervision->id,
            'defense_type' => $request->defense_type,
            'title' => $request->title,
            'abstract' => $request->abstract,
            'status' => 'MENUNGGU_VERIFIKASI',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.defenses.index')->with('success', 'Pendaftaran sidang skripsi berhasil diajukan! Menunggu verifikasi admin.');
    }

    // --- ADMIN METHODS ---

    public function adminIndex(Request $request)
    {
        $defenses = Defense::with(['student.user', 'supervision', 'examiners.lecturer.user'])
            ->latest()
            ->paginate(15);

        $lecturers = Lecturer::with('user')->get();

        return view('admin.defenses.index', compact('defenses', 'lecturers'));
    }

    public function adminSchedule(Request $request, Defense $defense)
    {
        $request->validate([
            'scheduled_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'method' => 'required|in:tatap_muka,online',
            'room' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url|max:500',
            'penguji_1_id' => 'required|exists:lecturers,id',
            'penguji_2_id' => 'required|exists:lecturers,id',
        ]);

        $defense->update([
            'status' => 'TERJADWAL',
            'scheduled_date' => $request->scheduled_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'method' => $request->method,
            'room' => $request->room,
            'meeting_url' => $request->meeting_url,
        ]);

        // Remove old examiners if re-scheduling
        DefenseExaminer::where('defense_id', $defense->id)->delete();

        // Get supervisor IDs from student assignments
        $assignments = $defense->student->activeAssignments;
        foreach ($assignments as $assignment) {
            DefenseExaminer::create([
                'defense_id' => $defense->id,
                'lecturer_id' => $assignment->lecturer_id,
                'role' => $assignment->supervisor_type === 'pembimbing_1' ? 'pembimbing_1' : 'pembimbing_2',
            ]);
        }

        // Add Penguji 1 & Penguji 2
        DefenseExaminer::create([
            'defense_id' => $defense->id,
            'lecturer_id' => $request->penguji_1_id,
            'role' => 'penguji_1',
        ]);

        DefenseExaminer::create([
            'defense_id' => $defense->id,
            'lecturer_id' => $request->penguji_2_id,
            'role' => 'penguji_2',
        ]);

        // Notify Student
        $this->notificationService->sendNotification(
            $defense->student->user_id,
            'Jadwal Sidang Skripsi Disetujui',
            "Jadwal sidang skripsi Anda telah ditetapkan pada tanggal {$request->scheduled_date} pukul {$request->start_time}.",
            'system'
        );

        return redirect()->route('admin.defenses.index')->with('success', 'Penjadwalan sidang & plotting dewan penguji berhasil disimpan!');
    }

    // --- DOSEN (LECTURER) METHODS ---

    public function lecturerIndex(Request $request)
    {
        $lecturer = Lecturer::where('user_id', auth()->id())->firstOrFail();

        $defenseIds = DefenseExaminer::where('lecturer_id', $lecturer->id)->pluck('defense_id');

        $defenses = Defense::whereIn('id', $defenseIds)
            ->with(['student.user', 'supervision', 'examiners.lecturer.user', 'scores'])
            ->latest()
            ->paginate(15);

        return view('lecturer.defenses.index', compact('lecturer', 'defenses'));
    }

    public function lecturerScoreForm(Defense $defense)
    {
        $lecturer = Lecturer::where('user_id', auth()->id())->firstOrFail();

        // Check if lecturer is assigned to this defense
        $examiner = DefenseExaminer::where('defense_id', $defense->id)
            ->where('lecturer_id', $lecturer->id)
            ->firstOrFail();

        $score = DefenseScore::where('defense_id', $defense->id)
            ->where('lecturer_id', $lecturer->id)
            ->first();

        return view('lecturer.defenses.score', compact('defense', 'lecturer', 'examiner', 'score'));
    }

    public function lecturerStoreScore(Request $request, Defense $defense)
    {
        $lecturer = Lecturer::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'presentation_score' => 'required|numeric|min:0|max:100',
            'manuscript_score' => 'required|numeric|min:0|max:100',
            'mastery_score' => 'required|numeric|min:0|max:100',
            'product_score' => 'required|numeric|min:0|max:100',
            'revision_notes' => 'nullable|string',
        ]);

        $totalScore = $this->defenseService->calculateWeightedScore(
            (float) $request->presentation_score,
            (float) $request->manuscript_score,
            (float) $request->mastery_score,
            (float) $request->product_score
        );

        DefenseScore::updateOrCreate(
            ['defense_id' => $defense->id, 'lecturer_id' => $lecturer->id],
            [
                'presentation_score' => $request->presentation_score,
                'manuscript_score' => $request->manuscript_score,
                'mastery_score' => $request->mastery_score,
                'product_score' => $request->product_score,
                'total_score' => $totalScore,
                'revision_notes' => $request->revision_notes,
            ]
        );

        // Recalculate average final score & grade for defense
        $this->defenseService->recalculateDefenseFinalScore($defense);

        return redirect()->route('lecturer.defenses.index')->with('success', 'Nilai sidang berhasil disimpan!');
    }

    // --- BAP PDF DOWNLOAD / PRINT ---

    public function downloadBap(Defense $defense)
    {
        $bapData = $this->defenseService->getBapData($defense);

        return view('pdf.bap', $bapData);
    }
}
