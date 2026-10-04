<?php

namespace App\Http\Controllers;

use App\Models\GuidanceNote;
use App\Models\GuidanceRequest;
use App\Models\GuidanceSchedule;
use App\Models\Student;
use App\Models\SupervisionAssignment;
use App\Services\C45\ClassificationService;
use App\Services\NotificationService;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    protected ProgressService $progressService;
    protected ClassificationService $classificationService;
    protected NotificationService $notificationService;

    public function __construct(
        ProgressService $progressService,
        ClassificationService $classificationService,
        NotificationService $notificationService
    ) {
        $this->progressService = $progressService;
        $this->classificationService = $classificationService;
        $this->notificationService = $notificationService;
    }

    public function dashboard(Request $request)
    {
        $lecturer = $request->user()->lecturer;
        if (!$lecturer) {
            abort(404, 'Data profil dosen belum dikonfigurasi.');
        }

        $assignedStudentsCount = SupervisionAssignment::where('lecturer_id', $lecturer->id)->where('is_active', true)->distinct('student_id')->count();

        $pendingReviewCount = GuidanceRequest::where('lecturer_id', $lecturer->id)->where('status', 'MENUNGGU_REVIEW')->count();
        $revisionCount = GuidanceRequest::where('lecturer_id', $lecturer->id)->where('status', 'PERLU_REVISI')->count();
        $scheduledCount = GuidanceSchedule::where('lecturer_id', $lecturer->id)->where('status', 'terjadwal')->count();
        $completedCount = GuidanceRequest::where('lecturer_id', $lecturer->id)->where('status', 'SELESAI')->count();

        $recentRequests = GuidanceRequest::where('lecturer_id', $lecturer->id)->with(['supervision.student', 'documents'])->latest()->take(5)->get();

        return view('lecturer.dashboard', compact(
            'lecturer', 'assignedStudentsCount', 'pendingReviewCount',
            'revisionCount', 'scheduledCount', 'completedCount', 'recentRequests'
        ));
    }

    public function profile(Request $request)
    {
        $lecturer = $request->user()->lecturer;
        return view('lecturer.profile', compact('lecturer'));
    }

    public function updateProfile(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'academic_degree' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
        ]);

        $request->user()->update(['name' => $validated['name']]);
        $lecturer->update([
            'name' => $validated['name'],
            'academic_degree' => $validated['academic_degree'],
            'phone' => $validated['phone'],
        ]);

        return back()->with('status', 'Profil dosen berhasil diperbarui.');
    }

    public function students(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        $assignments = SupervisionAssignment::where('lecturer_id', $lecturer->id)
            ->where('is_active', true)
            ->with(['student.activeSupervision', 'student.latestClassificationResult'])
            ->get();

        $studentsData = $assignments->map(function ($assignment) {
            $student = $assignment->student;
            $metrics = $student ? $this->progressService->calculateProgress($student) : [];
            return [
                'student' => $student,
                'assignment' => $assignment,
                'metrics' => $metrics,
                'classification' => $student->latestClassificationResult ?? null,
            ];
        });

        return view('lecturer.students', compact('studentsData'));
    }

    public function requests(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        $query = GuidanceRequest::where('lecturer_id', $lecturer->id)->with(['supervision.student', 'documents']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);

        return view('lecturer.requests.index', compact('requests'));
    }

    public function showRequest(Request $request, $id)
    {
        $lecturer = $request->user()->lecturer;
        $guidanceRequest = GuidanceRequest::with([
            'supervision.student', 'documents.documentVersions.uploader', 'guidanceSchedules', 'guidanceNotes'
        ])->findOrFail($id);

        if ($guidanceRequest->lecturer_id !== $lecturer->id) {
            abort(403, 'Anda bukan pembimbing untuk pengajuan ini.');
        }

        return view('lecturer.requests.show', compact('guidanceRequest'));
    }

    public function storeReview(Request $request, $id)
    {
        $lecturer = $request->user()->lecturer;
        $guidanceRequest = GuidanceRequest::with('supervision.student')->findOrFail($id);

        if ($guidanceRequest->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:PERLU_REVISI,DISETUJUI,DITOLAK',
            'note' => 'required|string',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $status = $validated['action'];

        $guidanceRequest->update([
            'status' => $status,
            'reviewed_at' => now(),
        ]);

        $progressPercentage = $validated['progress_percentage'] ?? 0;

        GuidanceNote::create([
            'guidance_request_id' => $guidanceRequest->id,
            'lecturer_id' => $lecturer->id,
            'note' => $validated['note'],
            'result' => $status === 'PERLU_REVISI' ? 'lanjut_revisi' : ($status === 'DISETUJUI' ? 'disetujui' : 'lanjut_bimbingan'),
            'progress_percentage' => $progressPercentage,
        ]);

        // Update progress & run auto-classification
        $student = $guidanceRequest->supervision->student;
        if ($student) {
            $this->progressService->recordProgress($student);
            $this->classificationService->predictStudent($student);

            // Send notification
            $studentUser = $student->user;
            if ($studentUser) {
                $statusText = $status === 'PERLU_REVISI' ? 'Perlu Revisi' : ($status === 'DISETUJUI' ? 'Disetujui' : 'Ditolak');
                $this->notificationService->send(
                    $studentUser->id,
                    "Status Pengajuan Bimbingan: {$statusText}",
                    "Dosen {$lecturer->name} telah merespons pengajuan Anda '{$guidanceRequest->title}' dengan status {$statusText}.",
                    $status === 'DISETUJUI' ? 'success' : 'warning'
                );
            }
        }

        return back()->with('status', 'Hasil review dan catatan bimbingan berhasil disimpan.');
    }

    public function storeSchedule(Request $request, $id)
    {
        $lecturer = $request->user()->lecturer;
        $guidanceRequest = GuidanceRequest::with('supervision.student')->findOrFail($id);

        if ($guidanceRequest->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'method' => 'required|in:tatap_muka,online',
            'location' => 'nullable|required_if:method,tatap_muka|string|max:255',
            'meeting_url' => 'nullable|required_if:method,online|url|max:500',
        ]);

        $schedule = GuidanceSchedule::create([
            'guidance_request_id' => $guidanceRequest->id,
            'lecturer_id' => $lecturer->id,
            'scheduled_date' => $validated['scheduled_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'method' => $validated['method'],
            'location' => $validated['location'] ?? null,
            'meeting_url' => $validated['meeting_url'] ?? null,
            'status' => 'terjadwal',
        ]);

        $guidanceRequest->update(['status' => 'TERJADWAL']);

        // Notify Student
        $studentUser = $guidanceRequest->supervision->student->user ?? null;
        if ($studentUser) {
            $this->notificationService->send(
                $studentUser->id,
                'Jadwal Bimbingan Ditetapkan',
                "Dosen {$lecturer->name} telah menetapkan jadwal bimbingan pada tanggal {$schedule->scheduled_date->format('d/m/Y')} pukul {$schedule->start_time} - {$schedule->end_time}.",
                'info'
            );
        }

        return back()->with('status', 'Jadwal bimbingan berhasil dibuat.');
    }

    public function updateSchedule(Request $request, $scheduleId)
    {
        $lecturer = $request->user()->lecturer;
        $schedule = GuidanceSchedule::with('guidanceRequest.supervision.student')->findOrFail($scheduleId);

        if ($schedule->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'method' => 'required|in:tatap_muka,online',
            'location' => 'nullable|required_if:method,tatap_muka|string|max:255',
            'meeting_url' => 'nullable|required_if:method,online|url|max:500',
        ]);

        $schedule->update($validated);

        // Notify Student of Schedule Change (BR-015)
        $studentUser = $schedule->guidanceRequest->supervision->student->user ?? null;
        if ($studentUser) {
            $this->notificationService->send(
                $studentUser->id,
                'Perubahan Jadwal Bimbingan',
                "Dosen {$lecturer->name} telah memperbarui jadwal bimbingan menjadi tanggal {$schedule->scheduled_date->format('d/m/Y')} pukul {$schedule->start_time} - {$schedule->end_time}.",
                'warning'
            );
        }

        return back()->with('status', 'Jadwal bimbingan berhasil diperbarui.');
    }

    public function cancelSchedule(Request $request, $scheduleId)
    {
        $lecturer = $request->user()->lecturer;
        $schedule = GuidanceSchedule::with('guidanceRequest.supervision.student')->findOrFail($scheduleId);

        if ($schedule->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $schedule->update(['status' => 'dibatalkan']);
        $schedule->guidanceRequest->update(['status' => 'DISETUJUI']);

        $studentUser = $schedule->guidanceRequest->supervision->student->user ?? null;
        if ($studentUser) {
            $this->notificationService->send(
                $studentUser->id,
                'Jadwal Bimbingan Dibatalkan',
                "Jadwal bimbingan pada tanggal {$schedule->scheduled_date->format('d/m/Y')} telah dibatalkan oleh dosen.",
                'error'
            );
        }

        return back()->with('status', 'Jadwal bimbingan berhasil dibatalkan.');
    }

    public function completeGuidance(Request $request, $id)
    {
        $lecturer = $request->user()->lecturer;
        $guidanceRequest = GuidanceRequest::with('supervision.student')->findOrFail($id);

        if ($guidanceRequest->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $guidanceRequest->update(['status' => 'SELESAI']);

        $schedule = $guidanceRequest->latestSchedule;
        if ($schedule) {
            $schedule->update(['status' => 'selesai']);
        }

        $student = $guidanceRequest->supervision->student;
        if ($student) {
            $this->progressService->recordProgress($student);
            $this->classificationService->predictStudent($student);
        }

        return back()->with('status', 'Sesi bimbingan telah ditandai Selesai.');
    }

    public function history(Request $request)
    {
        $lecturer = $request->user()->lecturer;
        $history = GuidanceRequest::where('lecturer_id', $lecturer->id)
            ->whereIn('status', ['SELESAI', 'DISETUJUI', 'DITOLAK'])
            ->with(['supervision.student', 'guidanceNotes', 'guidanceSchedules'])
            ->latest()
            ->paginate(15);

        return view('lecturer.history', compact('history'));
    }

    public function progress(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        $assignments = SupervisionAssignment::where('lecturer_id', $lecturer->id)
            ->where('is_active', true)
            ->with('student')
            ->get();

        $studentProgressList = $assignments->map(function ($assignment) {
            $student = $assignment->student;
            return [
                'student' => $student,
                'metrics' => $student ? $this->progressService->calculateProgress($student) : [],
            ];
        });

        return view('lecturer.progress', compact('studentProgressList'));
    }

    public function classification(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        $assignments = SupervisionAssignment::where('lecturer_id', $lecturer->id)
            ->where('is_active', true)
            ->with(['student.latestClassificationResult'])
            ->get();

        return view('lecturer.classification', compact('assignments'));
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->appNotifications()->latest()->paginate(15);
        $request->user()->appNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return view('lecturer.notifications', compact('notifications'));
    }
}
