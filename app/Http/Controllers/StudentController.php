<?php

namespace App\Http\Controllers;

use App\Models\GuidanceNote;
use App\Models\GuidanceRequest;
use App\Models\GuidanceSchedule;
use App\Models\Notification;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Services\C45\ClassificationService;
use App\Services\NotificationService;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class StudentController extends Controller
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
        $student = $request->user()->student;
        if (!$student) {
            abort(404, 'Data profil mahasiswa belum dikonfigurasi.');
        }

        $supervision = $student->activeSupervision;
        $assignments = $student->activeAssignments()->with('lecturer')->get();

        $pembimbing1 = $assignments->where('supervisor_type', 'pembimbing_1')->first();
        $pembimbing2 = $assignments->where('supervisor_type', 'pembimbing_2')->first();

        $metrics = $this->progressService->calculateProgress($student);
        $classification = $student->latestClassificationResult;

        $lastRequest = $supervision ? $supervision->guidanceRequests()->latest()->first() : null;

        $nextSchedule = GuidanceSchedule::whereHas('guidanceRequest', function ($q) use ($supervision) {
            if ($supervision) {
                $q->where('supervision_id', $supervision->id);
            }
        })->where('status', 'terjadwal')->where('scheduled_date', '>=', now()->toDateString())->orderBy('scheduled_date')->first();

        $guidanceRequests = $supervision ? $supervision->guidanceRequests()->with(['documents.versions', 'guidanceNotes.lecturer.user'])->latest()->get() : collect([]);
        $defenses = $student->defenses()->with('examiners')->latest()->get();

        return view('student.dashboard', compact(
            'student', 'supervision', 'pembimbing1', 'pembimbing2',
            'metrics', 'classification', 'lastRequest', 'nextSchedule',
            'guidanceRequests', 'defenses'
        ));
    }

    public function profile(Request $request)
    {
        $student = $request->user()->student;
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = $request->user()->student;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
        ]);

        $request->user()->update(['name' => $validated['name']]);
        $student->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function supervision(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;
        $assignments = $student->activeAssignments()->with('lecturer')->get();

        return view('student.supervision', compact('student', 'supervision', 'assignments'));
    }

    public function updateSupervisionTitle(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        $request->validate([
            'title' => 'required|string|max:500',
        ]);

        if ($supervision) {
            $supervision->update(['title' => $request->title]);
        } else {
            $activePeriod = \App\Models\AcademicPeriod::where('is_active', true)->first();
            \App\Models\Supervision::create([
                'student_id' => $student->id,
                'academic_period_id' => $activePeriod->id ?? 1,
                'title' => $request->title,
                'status' => 'aktif',
                'started_at' => now(),
            ]);
        }

        return back()->with('status', 'Judul skripsi Anda berhasil disimpan/diperbarui.');
    }

    public function guidanceList(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        $requests = $supervision ? $supervision->guidanceRequests()->with(['lecturer', 'documents', 'guidanceSchedules'])->latest()->paginate(10) : collect([]);

        return view('student.guidance.index', compact('requests', 'supervision'));
    }

    public function createGuidance(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        if (!$supervision) {
            return redirect()->route('student.guidance.index')->with('error', 'Anda belum memiliki data skripsi aktif.');
        }

        $assignments = $student->activeAssignments()->with('lecturer')->get();

        return view('student.guidance.create', compact('student', 'supervision', 'assignments'));
    }

    public function storeGuidance(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        if (!$supervision) {
            return back()->with('error', 'Anda belum memiliki data skripsi aktif.');
        }

        $validated = $request->validate([
            'lecturer_id' => 'required|exists:lecturers,id',
            'request_type' => 'required|in:proposal,bab_1,bab_2,bab_3,bab_4,bab_5,laporan,revisi,lainnya',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $guidanceRequest = GuidanceRequest::create([
            'supervision_id' => $supervision->id,
            'lecturer_id' => $validated['lecturer_id'],
            'request_type' => $validated['request_type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'MENUNGGU_REVIEW',
            'submitted_at' => now(),
        ]);

        // Upload file to private storage
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        $storagePath = "thesis/{$student->id}/{$supervision->id}/{$guidanceRequest->id}/v1";
        $savedPath = $file->storeAs($storagePath, "document_v1.{$fileType}", 'local');

        $doc = Document::create([
            'guidance_request_id' => $guidanceRequest->id,
            'uploaded_by' => $request->user()->id,
            'original_name' => $originalName,
            'file_path' => $savedPath,
            'file_type' => $fileType,
            'file_size' => $fileSize,
        ]);

        DocumentVersion::create([
            'document_id' => $doc->id,
            'version_number' => 1,
            'file_path' => $savedPath,
            'uploaded_by' => $request->user()->id,
            'notes' => 'Pengajuan awal dokumen',
        ]);

        // Notify Lecturer
        $lecturer = $guidanceRequest->lecturer;
        if ($lecturer && $lecturer->user_id) {
            $this->notificationService->send(
                $lecturer->user_id,
                'Pengajuan Bimbingan Baru',
                "Mahasiswa {$student->name} ({$student->nim}) mengajukan bimbingan baru: {$guidanceRequest->title}",
                'info'
            );
        }

        return redirect()->route('student.guidance.show', $guidanceRequest->id)
            ->with('status', 'Pengajuan bimbingan dan dokumen berhasil dikirim.');
    }

    public function showGuidance(Request $request, $id)
    {
        $student = $request->user()->student;
        $guidanceRequest = GuidanceRequest::with([
            'lecturer', 'documents.documentVersions.uploader', 'guidanceSchedules', 'guidanceNotes'
        ])->findOrFail($id);

        if ($guidanceRequest->supervision->student_id !== $student->id) {
            abort(403);
        }

        return view('student.guidance.show', compact('guidanceRequest'));
    }

    public function storeRevision(Request $request, $id)
    {
        $student = $request->user()->student;
        $guidanceRequest = GuidanceRequest::with('documents')->findOrFail($id);

        if ($guidanceRequest->supervision->student_id !== $student->id) {
            abort(403);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);

        $document = $guidanceRequest->documents()->latest()->first();

        if (!$document) {
            return back()->with('error', 'Dokumen pengajuan tidak ditemukan.');
        }

        $nextVersionNumber = $document->documentVersions()->max('version_number') + 1;

        $file = $request->file('file');
        $fileType = $file->getClientOriginalExtension();

        $storagePath = "thesis/{$student->id}/{$guidanceRequest->supervision_id}/{$guidanceRequest->id}/v{$nextVersionNumber}";
        $savedPath = $file->storeAs($storagePath, "document_v{$nextVersionNumber}.{$fileType}", 'local');

        DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $nextVersionNumber,
            'file_path' => $savedPath,
            'uploaded_by' => $request->user()->id,
            'notes' => $validated['notes'] ?? 'Unggah versi revisi ' . $nextVersionNumber,
        ]);

        $guidanceRequest->update([
            'status' => 'MENUNGGU_REVIEW',
            'submitted_at' => now(),
        ]);

        // Notify Lecturer
        if ($guidanceRequest->lecturer && $guidanceRequest->lecturer->user_id) {
            $this->notificationService->send(
                $guidanceRequest->lecturer->user_id,
                'Revisi Dokumen Diunggah',
                "Mahasiswa {$student->name} telah mengunggah revisi versi {$nextVersionNumber} untuk pengajuan: {$guidanceRequest->title}",
                'info'
            );
        }

        return back()->with('status', "Revisi versi {$nextVersionNumber} berhasil diunggah.");
    }

    public function documents(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        $documents = $supervision ? Document::whereHas('guidanceRequest', function ($q) use ($supervision) {
            $q->where('supervision_id', $supervision->id);
        })->with(['guidanceRequest', 'documentVersions'])->latest()->get() : collect([]);

        return view('student.documents', compact('documents'));
    }

    public function schedules(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        $schedules = $supervision ? GuidanceSchedule::whereHas('guidanceRequest', function ($q) use ($supervision) {
            $q->where('supervision_id', $supervision->id);
        })->with(['lecturer', 'guidanceRequest'])->latest('scheduled_date')->get() : collect([]);

        return view('student.schedules', compact('schedules'));
    }

    public function history(Request $request)
    {
        $student = $request->user()->student;
        $supervision = $student->activeSupervision;

        $history = $supervision ? GuidanceRequest::where('supervision_id', $supervision->id)
            ->with(['lecturer', 'guidanceSchedules', 'guidanceNotes'])
            ->latest()
            ->paginate(15) : collect([]);

        return view('student.history', compact('history'));
    }

    public function progress(Request $request)
    {
        $student = $request->user()->student;
        $metrics = $this->progressService->calculateProgress($student);
        $records = $student->progressRecords()->latest()->get();

        return view('student.progress', compact('student', 'metrics', 'records'));
    }

    public function classification(Request $request)
    {
        $student = $request->user()->student;
        $result = $student->latestClassificationResult;
        $metrics = $this->progressService->calculateProgress($student);

        return view('student.classification', compact('student', 'result', 'metrics'));
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->appNotifications()->latest()->paginate(15);
        $request->user()->appNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return view('student.notifications', compact('notifications'));
    }
}
