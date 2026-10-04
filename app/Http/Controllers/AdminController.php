<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassificationDataset;
use App\Models\ClassificationResult;
use App\Models\GuidanceRequest;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\Supervision;
use App\Models\SupervisionAssignment;
use App\Models\User;
use App\Services\C45\ClassificationService;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected ClassificationService $classificationService;
    protected ProgressService $progressService;

    public function __construct(ClassificationService $classificationService, ProgressService $progressService)
    {
        $this->classificationService = $classificationService;
        $this->progressService = $progressService;
    }

    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalLecturers = Lecturer::count();
        $totalGuidance = GuidanceRequest::count();
        $completedGuidance = GuidanceRequest::where('status', 'SELESAI')->count();
        $pendingReview = GuidanceRequest::where('status', 'MENUNGGU_REVIEW')->count();
        $needsRevision = GuidanceRequest::where('status', 'PERLU_REVISI')->count();
        $scheduledGuidance = GuidanceRequest::where('status', 'TERJADWAL')->count();

        // Classification counts
        $latestResults = ClassificationResult::latest('calculated_at')->get()->unique('student_id');
        $lancarCount = $latestResults->where('predicted_label', 'LANCAR')->count();
        $perluPerhatianCount = $latestResults->where('predicted_label', 'PERLU_PERHATIAN')->count();
        $terlambatCount = $latestResults->where('predicted_label', 'TERLAMBAT')->count();

        return view('admin.dashboard', compact(
            'totalStudents', 'totalLecturers', 'totalGuidance', 'completedGuidance',
            'pendingReview', 'needsRevision', 'scheduledGuidance',
            'lancarCount', 'perluPerhatianCount', 'terlambatCount'
        ));
    }

    // --- STUDENT MANAGEMENT ---
    public function students()
    {
        $students = Student::with('user')->latest()->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'nim' => 'required|string|max:50|unique:students,nim',
            'email' => 'required|email|unique:users,email',
            'study_program' => 'required|string|max:150',
            'generation' => 'required|integer|min:2015|max:2030',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'is_active' => true,
        ]);

        Student::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'name' => $validated['name'],
            'study_program' => $validated['study_program'],
            'generation' => $validated['generation'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('status', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'nim' => 'required|string|max:50|unique:students,nim,' . $student->id,
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'study_program' => 'required|string|max:150',
            'generation' => 'required|integer|min:2015|max:2030',
            'phone' => 'nullable|string|max:30',
        ]);

        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $student->update([
            'nim' => $validated['nim'],
            'name' => $validated['name'],
            'study_program' => $validated['study_program'],
            'generation' => $validated['generation'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->user->delete();

        return back()->with('status', 'Data mahasiswa berhasil dihapus.');
    }

    // --- LECTURER MANAGEMENT ---
    public function lecturers()
    {
        $lecturers = Lecturer::with('user')->latest()->paginate(15);
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function storeLecturer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'nidn' => 'required|string|max:50|unique:lecturers,nidn',
            'email' => 'required|email|unique:users,email',
            'academic_degree' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'lecturer',
            'is_active' => true,
        ]);

        Lecturer::create([
            'user_id' => $user->id,
            'nidn' => $validated['nidn'],
            'name' => $validated['name'],
            'academic_degree' => $validated['academic_degree'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('status', 'Data dosen berhasil ditambahkan.');
    }

    public function updateLecturer(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'nidn' => 'required|string|max:50|unique:lecturers,nidn,' . $lecturer->id,
            'email' => 'required|email|unique:users,email,' . $lecturer->user_id,
            'academic_degree' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
        ]);

        $lecturer->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $lecturer->update([
            'nidn' => $validated['nidn'],
            'name' => $validated['name'],
            'academic_degree' => $validated['academic_degree'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with('status', 'Data dosen berhasil diperbarui.');
    }

    public function deleteLecturer($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->user->delete();

        return back()->with('status', 'Data dosen berhasil dihapus.');
    }

    // --- ACADEMIC PERIODS ---
    public function periods()
    {
        $periods = AcademicPeriod::latest()->get();
        return view('admin.periods.index', compact('periods'));
    }

    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_active'])) {
            AcademicPeriod::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicPeriod::create([
            'name' => $validated['name'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => !empty($validated['is_active']),
        ]);

        return back()->with('status', 'Periode akademik berhasil dibuat.');
    }

    public function updatePeriod(Request $request, $id)
    {
        $period = AcademicPeriod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_active'])) {
            AcademicPeriod::where('id', '!=', $period->id)->update(['is_active' => false]);
        }

        $period->update([
            'name' => $validated['name'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => !empty($validated['is_active']),
        ]);

        return back()->with('status', 'Periode akademik berhasil diperbarui.');
    }

    // --- SUPERVISION ASSIGNMENTS ---
    public function assignments()
    {
        $assignments = SupervisionAssignment::with(['student', 'lecturer', 'academicPeriod'])->latest()->paginate(15);
        $students = Student::all();
        $lecturers = Lecturer::all();
        $periods = AcademicPeriod::all();

        return view('admin.assignments.index', compact('assignments', 'students', 'lecturers', 'periods'));
    }

    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'supervisor_type' => 'required|in:pembimbing_1,pembimbing_2',
        ]);

        // Validation Rule BR-003 & Section 16: Only 1 active Pembimbing 1 and 1 active Pembimbing 2 per student per period
        $exists = SupervisionAssignment::where('student_id', $validated['student_id'])
            ->where('academic_period_id', $validated['academic_period_id'])
            ->where('supervisor_type', $validated['supervisor_type'])
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            return back()->with('error', "Mahasiswa ini sudah memiliki {$validated['supervisor_type']} aktif pada periode terpilih.");
        }

        SupervisionAssignment::create([
            'student_id' => $validated['student_id'],
            'lecturer_id' => $validated['lecturer_id'],
            'academic_period_id' => $validated['academic_period_id'],
            'supervisor_type' => $validated['supervisor_type'],
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        // Ensure supervision record exists for student
        $supervision = Supervision::firstOrCreate(
            ['student_id' => $validated['student_id'], 'academic_period_id' => $validated['academic_period_id']],
            ['title' => 'Judul Skripsi Belum Diisi', 'status' => 'aktif', 'started_at' => now()]
        );

        return back()->with('status', 'Penetapan dosen pembimbing berhasil disimpan.');
    }

    public function deleteAssignment($id)
    {
        $assignment = SupervisionAssignment::findOrFail($id);
        $assignment->delete();

        return back()->with('status', 'Penetapan pembimbing berhasil dihapus.');
    }

    // --- SUPERVISIONS & GUIDANCE MONITORING ---
    public function supervisions()
    {
        $supervisions = Supervision::with(['student', 'academicPeriod', 'guidanceRequests'])->latest()->paginate(15);
        $students = Student::all();
        $periods = AcademicPeriod::where('is_active', true)->get();

        return view('admin.supervisions.index', compact('supervisions', 'students', 'periods'));
    }

    public function storeSupervision(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:500',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $activePeriod = AcademicPeriod::where('is_active', true)->first();

        Supervision::updateOrCreate(
            ['student_id' => $validated['student_id'], 'academic_period_id' => $activePeriod->id ?? 1],
            ['title' => $validated['title'], 'status' => $validated['status'], 'started_at' => now()]
        );

        return back()->with('status', 'Data skripsi mahasiswa berhasil disimpan.');
    }

    public function updateSupervision(Request $request, $id)
    {
        $supervision = Supervision::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $supervision->update($validated);

        return back()->with('status', 'Judul skripsi berhasil diperbarui.');
    }

    public function guidance(Request $request)
    {
        $query = GuidanceRequest::with([
            'supervision.student',
            'lecturer',
            'documents.versions',
            'guidanceNotes.lecturer.user'
        ]);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('supervision.student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lecturer', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.guidance.index', compact('requests'));
    }

    // --- C4.5 DATASETS & CLASSIFICATION ENGINE ---
    public function datasets()
    {
        $datasets = ClassificationDataset::latest()->paginate(20);
        return view('admin.datasets.index', compact('datasets'));
    }

    public function importDataset(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Read header
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) >= 7) {
                ClassificationDataset::create([
                    'guidance_count' => (int) $data[0],
                    'revision_count' => (int) $data[1],
                    'submission_count' => (int) $data[2],
                    'progress_percentage' => (float) $data[3],
                    'late_days' => (int) $data[4],
                    'guidance_frequency' => trim($data[5]),
                    'label' => trim($data[6]),
                    'dataset_period' => $data[7] ?? 'Imported',
                ]);
                $count++;
            }
        }
        fclose($handle);

        return back()->with('status', "Berhasil mengimpor {$count} dataset C4.5.");
    }

    public function classification()
    {
        $studentsResults = Student::with(['latestClassificationResult', 'user'])->get();

        $treeModel = null;
        if (Storage::disk('local')->exists('c45_model.json')) {
            $treeModel = json_decode(Storage::disk('local')->get('c45_model.json'), true);
        }

        // Fetch dataset evaluation metrics
        $datasets = ClassificationDataset::all()->map(function ($row) {
            return [
                'guidance_count' => (int) $row->guidance_count,
                'revision_count' => (int) $row->revision_count,
                'submission_count' => (int) $row->submission_count,
                'progress_percentage' => (float) $row->progress_percentage,
                'late_days' => (int) $row->late_days,
                'guidance_frequency' => (string) $row->guidance_frequency,
                'label' => (string) $row->label,
            ];
        })->toArray();

        $evaluation = (!empty($datasets) && !empty($treeModel))
            ? $this->classificationService->evaluateModel($datasets, $treeModel)
            : null;

        return view('admin.classification.index', compact('studentsResults', 'treeModel', 'evaluation'));
    }

    public function runClassification()
    {
        $result = $this->classificationService->trainModel();

        if ($result['status'] === 'error') {
            return back()->with('error', $result['message']);
        }

        return back()->with('status', "Proses pelatihan C4.5 & klasifikasi seluruh mahasiswa berhasil dijalankan! (Akurasi Model: {$result['evaluation']['accuracy']}%)");
    }

    // --- REPORTS ---
    public function reports()
    {
        $supervisions = Supervision::with(['student', 'academicPeriod'])->get();
        $results = ClassificationResult::with('student')->latest('calculated_at')->get()->unique('student_id');

        return view('admin.reports.index', compact('supervisions', 'results'));
    }
}
