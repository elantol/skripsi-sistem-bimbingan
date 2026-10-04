<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\ClassificationDataset;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\GuidanceNote;
use App\Models\GuidanceRequest;
use App\Models\GuidanceSchedule;
use App\Models\Lecturer;
use App\Models\ProgressRecord;
use App\Models\Student;
use App\Models\Supervision;
use App\Models\SupervisionAssignment;
use App\Models\User;
use App\Services\C45\ClassificationService;
use App\Services\ProgressService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $adminUser = User::create([
            'name' => 'Administrator Sistem',
            'email' => 'admin@skripsi.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Create Active Academic Period
        $period = AcademicPeriod::create([
            'name' => 'Semester Ganjil 2026/2027',
            'academic_year' => '2026/2027',
            'semester' => 'ganjil',
            'start_date' => '2026-09-01',
            'end_date' => '2027-02-28',
            'is_active' => true,
        ]);

        // 3. Create 5 Lecturers
        $lecturers = [];
        $lecturerData = [
            ['name' => 'Dr. Ir. Budi Santoso, M.Kom.', 'nidn' => '0412057801', 'degree' => 'Dr. Ir., M.Kom.'],
            ['name' => 'Siti Nurhaliza, S.T., M.T.', 'nidn' => '0415088202', 'degree' => 'S.T., M.T.'],
            ['name' => 'Prof. Ahmad Dahlan, Ph.D.', 'nidn' => '0402016503', 'degree' => 'Prof., Ph.D.'],
            ['name' => 'Dewi Lestari, S.Kom., M.C.S.', 'nidn' => '0420118904', 'degree' => 'S.Kom., M.C.S.'],
            ['name' => 'Eko Prasetyo, M.T.', 'nidn' => '0408038505', 'degree' => 'M.T.'],
        ];

        foreach ($lecturerData as $index => $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => "dosen" . ($index + 1) . "@skripsi.ac.id",
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'is_active' => true,
            ]);

            $lecturers[] = Lecturer::create([
                'user_id' => $user->id,
                'nidn' => $data['nidn'],
                'name' => $data['name'],
                'academic_degree' => $data['degree'],
                'phone' => '0812' . rand(10000000, 99999999),
            ]);
        }

        // 4. Create 20 Students & Assignments
        $sampleTitles = [
            'Rancang Bangun Sistem Informasi Bimbingan Skripsi Berbasis Web dengan Algoritma C4.5',
            'Penerapan Machine Learning untuk Klasifikasi Kelulusan Mahasiswa Berbasis Decision Tree',
            'Sistem Pendukung Keputusan Pemilihan Dosen Pembimbing Menggunakan Metode SAW',
            'Pengembangan Aplikasi E-Learning Berbasis Microservices dengan Node.js dan Vue.js',
            'Analisis Sentimen Opini Publik pada Media Sosial Twitter Menggunakan Algoritma Naive Bayes',
            'Implementasi Algoritma A-Star pada Pencarian Rute Terpendek EV Charging Station',
            'Sistem Deteksi Hama Tanaman Padi Menggunakan Convolutional Neural Network (CNN)',
            'Rancang Bangun Marketplace UMKM Lokal Berbasis Progressive Web Apps (PWA)',
            'Sistem Keamanan Smart Home Berbasis Internet of Things (IoT) dan Telegram Bot',
            'Optimasi Rute Distribusi Logistik Menggunakan Genetic Algorithm (GA)',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $nim = '2022010' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $name = "Mahasiswa " . $i;

            $user = User::create([
                'name' => $name,
                'email' => "mahasiswa{$i}@skripsi.ac.id",
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_active' => true,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'name' => $name,
                'study_program' => 'Teknik Informatika',
                'generation' => 2022,
                'phone' => '0857' . rand(10000000, 99999999),
            ]);

            // Assign Pembimbing 1 & Pembimbing 2
            $p1 = $lecturers[($i - 1) % count($lecturers)];
            $p2 = $lecturers[$i % count($lecturers)];

            SupervisionAssignment::create([
                'student_id' => $student->id,
                'lecturer_id' => $p1->id,
                'academic_period_id' => $period->id,
                'supervisor_type' => 'pembimbing_1',
                'assigned_at' => now()->subDays(60),
                'is_active' => true,
            ]);

            SupervisionAssignment::create([
                'student_id' => $student->id,
                'lecturer_id' => $p2->id,
                'academic_period_id' => $period->id,
                'supervisor_type' => 'pembimbing_2',
                'assigned_at' => now()->subDays(60),
                'is_active' => true,
            ]);

            // Create Supervision
            $title = $sampleTitles[($i - 1) % count($sampleTitles)];
            $supervision = Supervision::create([
                'student_id' => $student->id,
                'academic_period_id' => $period->id,
                'title' => $title,
                'description' => 'Penelitian tugas akhir skripsi mahasiwa.',
                'started_at' => now()->subDays(60),
                'status' => 'aktif',
            ]);

            // Create 2 Guidance Requests & Documents per student
            $reqTypes = ['proposal', 'bab_1', 'bab_2', 'bab_3'];
            $reqType = $reqTypes[($i - 1) % count($reqTypes)];
            $status = ($i % 3 === 0) ? 'PERLU_REVISI' : (($i % 3 === 1) ? 'DISETUJUI' : 'SELESAI');

            $guidanceReq = GuidanceRequest::create([
                'supervision_id' => $supervision->id,
                'lecturer_id' => $p1->id,
                'request_type' => $reqType,
                'title' => "Pengajuan " . ucfirst($reqType) . " - " . substr($title, 0, 30) . "...",
                'description' => "Mohon petunjuk dan review berkas naskah " . $reqType,
                'status' => $status,
                'submitted_at' => now()->subDays(rand(5, 30)),
                'reviewed_at' => now()->subDays(rand(1, 4)),
            ]);

            // Create Document
            $doc = Document::create([
                'guidance_request_id' => $guidanceReq->id,
                'uploaded_by' => $user->id,
                'original_name' => "draft_{$reqType}_v1.pdf",
                'file_path' => "thesis/{$student->id}/{$supervision->id}/{$guidanceReq->id}/v1/document_v1.pdf",
                'file_type' => 'pdf',
                'file_size' => 1024 * rand(500, 3000),
            ]);

            DocumentVersion::create([
                'document_id' => $doc->id,
                'version_number' => 1,
                'file_path' => $doc->file_path,
                'uploaded_by' => $user->id,
                'notes' => 'Draft pengajuan awal v1',
            ]);

            // Create Note
            $progressPct = ($i * 5) % 95 + 10;
            GuidanceNote::create([
                'guidance_request_id' => $guidanceReq->id,
                'lecturer_id' => $p1->id,
                'note' => "Revisi beberapa poin sistematika penulisan dan perjelas latar belakang pada " . $reqType,
                'result' => $status === 'PERLU_REVISI' ? 'lanjut_revisi' : 'disetujui',
                'progress_percentage' => $progressPct,
            ]);

            // Create Schedule
            GuidanceSchedule::create([
                'guidance_request_id' => $guidanceReq->id,
                'lecturer_id' => $p1->id,
                'scheduled_date' => now()->addDays(rand(1, 10))->toDateString(),
                'start_time' => '10:00',
                'end_time' => '11:30',
                'method' => ($i % 2 === 0) ? 'tatap_muka' : 'online',
                'location' => ($i % 2 === 0) ? 'Ruang Dosen Gedung B.201' : null,
                'meeting_url' => ($i % 2 !== 0) ? 'https://meet.google.com/abc-defg-hij' : null,
                'status' => 'terjadwal',
            ]);
        }

        // 5. Create 50 C4.5 Historical Dataset Records
        $datasetSamples = [
            ['guidance' => 10, 'revision' => 1, 'submission' => 6, 'progress' => 85.00, 'late' => 0, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 9,  'revision' => 2, 'submission' => 7, 'progress' => 80.00, 'late' => 2, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 8,  'revision' => 1, 'submission' => 5, 'progress' => 75.00, 'late' => 1, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 12, 'revision' => 3, 'submission' => 8, 'progress' => 90.00, 'late' => 0, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 11, 'revision' => 2, 'submission' => 7, 'progress' => 95.00, 'late' => 0, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 8,  'revision' => 2, 'submission' => 5, 'progress' => 70.00, 'late' => 4, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 10, 'revision' => 1, 'submission' => 6, 'progress' => 88.00, 'late' => 0, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 9,  'revision' => 1, 'submission' => 6, 'progress' => 82.00, 'late' => 3, 'freq' => 'Tinggi', 'label' => 'LANCAR'],
            ['guidance' => 8,  'revision' => 0, 'submission' => 4, 'progress' => 78.00, 'late' => 1, 'freq' => 'Tinggi', 'label' => 'LANCAR'],

            ['guidance' => 5,  'revision' => 4, 'submission' => 4, 'progress' => 55.00, 'late' => 8,  'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 6,  'revision' => 5, 'submission' => 4, 'progress' => 60.00, 'late' => 7,  'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 4,  'revision' => 3, 'submission' => 3, 'progress' => 45.00, 'late' => 10, 'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 7,  'revision' => 6, 'submission' => 5, 'progress' => 65.00, 'late' => 6,  'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 5,  'revision' => 3, 'submission' => 4, 'progress' => 50.00, 'late' => 12, 'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 6,  'revision' => 4, 'submission' => 5, 'progress' => 58.00, 'late' => 9,  'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],
            ['guidance' => 4,  'revision' => 5, 'submission' => 3, 'progress' => 42.00, 'late' => 11, 'freq' => 'Sedang', 'label' => 'PERLU_PERHATIAN'],

            ['guidance' => 2,  'revision' => 7, 'submission' => 2, 'progress' => 30.00, 'late' => 25, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
            ['guidance' => 1,  'revision' => 8, 'submission' => 1, 'progress' => 20.00, 'late' => 35, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
            ['guidance' => 3,  'revision' => 6, 'submission' => 2, 'progress' => 35.00, 'late' => 20, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
            ['guidance' => 0,  'revision' => 5, 'submission' => 1, 'progress' => 10.00, 'late' => 40, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
            ['guidance' => 2,  'revision' => 9, 'submission' => 2, 'progress' => 25.00, 'late' => 30, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
            ['guidance' => 1,  'revision' => 6, 'submission' => 1, 'progress' => 15.00, 'late' => 45, 'freq' => 'Rendah', 'label' => 'TERLAMBAT'],
        ];

        // Replicate samples to generate 50 training rows
        for ($k = 0; $k < 50; $k++) {
            $sample = $datasetSamples[$k % count($datasetSamples)];
            ClassificationDataset::create([
                'guidance_count' => $sample['guidance'],
                'revision_count' => $sample['revision'],
                'submission_count' => $sample['submission'],
                'progress_percentage' => $sample['progress'],
                'late_days' => $sample['late'],
                'guidance_frequency' => $sample['freq'],
                'label' => $sample['label'],
                'dataset_period' => 'Historis 2025/2026',
            ]);
        }

        // Run initial progress calculation & C4.5 training
        $progressService = app(ProgressService::class);
        $classificationService = app(ClassificationService::class);

        foreach (Student::all() as $st) {
            $progressService->recordProgress($st);
        }

        $classificationService->trainModel();

        // 6. Seed Sample Thesis Defense & BAP for Student 1
        $student1 = Student::first();
        $supervision1 = Supervision::where('student_id', $student1->id)->first();
        if ($supervision1) {
            $defense1 = \App\Models\Defense::create([
                'student_id' => $student1->id,
                'supervision_id' => $supervision1->id,
                'defense_type' => 'skripsi_akhir',
                'title' => $supervision1->title,
                'abstract' => 'Penelitian ini bertujuan mengembangkan sistem bimbingan skripsi berbasis web dengan integrasi algoritma C4.5.',
                'status' => 'LULUS',
                'scheduled_date' => now()->subDays(2),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'method' => 'tatap_muka',
                'room' => 'Ruang Sidang Utama 301',
                'final_score' => 86.50,
                'grade' => 'A',
                'submitted_at' => now()->subDays(10),
                'completed_at' => now()->subDays(2),
            ]);

            // Assign Examiners
            $examinersData = [
                ['lecturer' => $lecturers[0]->id, 'role' => 'pembimbing_1'],
                ['lecturer' => $lecturers[1]->id, 'role' => 'pembimbing_2'],
                ['lecturer' => $lecturers[2]->id, 'role' => 'penguji_1'],
                ['lecturer' => $lecturers[3]->id, 'role' => 'penguji_2'],
            ];

            foreach ($examinersData as $exData) {
                \App\Models\DefenseExaminer::create([
                    'defense_id' => $defense1->id,
                    'lecturer_id' => $exData['lecturer'],
                    'role' => $exData['role'],
                    'is_present' => true,
                ]);

                \App\Models\DefenseScore::create([
                    'defense_id' => $defense1->id,
                    'lecturer_id' => $exData['lecturer'],
                    'presentation_score' => 88.00,
                    'manuscript_score' => 85.00,
                    'mastery_score' => 87.00,
                    'product_score' => 90.00,
                    'total_score' => 87.25,
                    'revision_notes' => 'Perbaiki penulisan daftar pustaka berformat IEEE.',
                ]);
            }
        }
    }
}
