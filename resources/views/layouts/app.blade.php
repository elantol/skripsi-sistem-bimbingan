<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SkripsiGuidance</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#047857',
                            700: '#065f46',
                            900: '#022c22',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-500 selection:text-white">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col justify-between shadow-xl">
            <div>
                <!-- Brand Header (Universitas Malikussaleh) -->
                <div class="p-4 flex items-center justify-between border-b border-emerald-900/60 bg-emerald-950/40">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 via-amber-400 to-emerald-700 p-0.5 shadow-lg shadow-emerald-900/50 flex-shrink-0">
                            <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center overflow-hidden p-0.5">
                                <img src="{{ asset('images/logo-unimal.png') }}" alt="Logo UNIMAL" class="w-7 h-7 object-contain">
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <h1 class="font-extrabold text-sm tracking-tight text-white leading-snug truncate">UNIMAL Skripsi</h1>
                            <p class="text-[10px] font-bold text-amber-400 truncate">Universitas Malikussaleh</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="px-3 py-4 space-y-1">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Menu Utama</div>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                            </a>
                            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400 mt-4">Master Data</div>
                            <a href="{{ route('admin.students.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.students.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-user-graduate w-6"></i> Data Mahasiswa
                            </a>
                            <a href="{{ route('admin.lecturers.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.lecturers.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-chalkboard-user w-6"></i> Data Dosen
                            </a>
                            <a href="{{ route('admin.periods.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.periods.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-calendar-days w-6"></i> Periode Akademik
                            </a>
                            <a href="{{ route('admin.assignments.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.assignments.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-people-arrows w-6"></i> Penetapan Pembimbing
                            </a>

                            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400 mt-4">Monitoring & C4.5</div>
                            <a href="{{ route('admin.supervisions.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.supervisions.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-book-bookmark w-6"></i> Monitoring Skripsi
                            </a>
                            <a href="{{ route('admin.guidance.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.guidance.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-comments w-6"></i> Seluruh Bimbingan
                            </a>
                            <a href="{{ route('admin.datasets.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.datasets.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-database w-6"></i> Dataset C4.5
                            </a>
                            <a href="{{ route('admin.classification.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.classification.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-diagram-project w-6"></i> Klasifikasi C4.5
                            </a>
                            <a href="{{ route('admin.defenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.defenses.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-graduation-cap w-6"></i> Sidang & BAP
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-file-contract w-6"></i> Laporan
                            </a>

                        @elseif(auth()->user()->isStudent())
                            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Mahasiswa</div>
                            <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.dashboard') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-house w-6"></i> Dashboard
                            </a>
                            <a href="{{ route('student.supervision') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.supervision') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-user-check w-6"></i> Pembimbing Saya
                            </a>
                            <a href="{{ route('student.guidance.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.guidance.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-paper-plane w-6"></i> Pengajuan Bimbingan
                            </a>
                            <a href="{{ route('student.documents') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.documents') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-file-pdf w-6"></i> Dokumen & Versi
                            </a>
                            <a href="{{ route('student.schedules') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.schedules') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-calendar-check w-6"></i> Jadwal Bimbingan
                            </a>
                            <a href="{{ route('student.defenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.defenses.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-graduation-cap w-6"></i> Sidang & BAP
                            </a>
                            <a href="{{ route('student.history') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.history') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-clock-rotate-left w-6"></i> Riwayat & Catatan
                            </a>
                            <a href="{{ route('student.progress') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.progress') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-chart-line w-6"></i> Monitoring Progres
                            </a>
                            <a href="{{ route('student.classification') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.classification') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-wand-magic-sparkles w-6"></i> Klasifikasi C4.5
                            </a>
                            <a href="{{ route('student.profile') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('student.profile') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-user-gear w-6"></i> Profil Saya
                            </a>

                        @elseif(auth()->user()->isLecturer())
                            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Dosen Pembimbing</div>
                            <a href="{{ route('lecturer.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.dashboard') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-house-user w-6"></i> Dashboard
                            </a>
                            <a href="{{ route('lecturer.students') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.students') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-users-rectangle w-6"></i> Mahasiswa Bimbingan
                            </a>
                            <a href="{{ route('lecturer.requests.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.requests.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-inbox w-6"></i> Pengajuan Review
                            </a>
                            <a href="{{ route('lecturer.defenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.defenses.*') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-graduation-cap w-6"></i> Penilaian Sidang
                            </a>
                            <a href="{{ route('lecturer.history') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.history') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-folder-open w-6"></i> Riwayat Bimbingan
                            </a>
                            <a href="{{ route('lecturer.progress') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.progress') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-chart-column w-6"></i> Monitoring Progres
                            </a>
                            <a href="{{ route('lecturer.classification') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.classification') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-brain w-6"></i> Klasifikasi C4.5
                            </a>
                            <a href="{{ route('lecturer.profile') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('lecturer.profile') ? 'bg-brand-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <i class="fa-solid fa-id-card w-6"></i> Profil Dosen
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- User Footer & Logout -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                @auth
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 overflow-hidden">
                            <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-bold text-slate-200">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <p class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name }}</p>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-semibold uppercase rounded-full bg-slate-800 text-slate-400">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Keluar Sistem" class="p-2 text-slate-400 hover:text-red-400 transition">
                                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar Header -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
                <div class="flex items-center space-x-3">
                    <h2 class="text-lg font-bold text-slate-800">@yield('title')</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notification Bell Button -->
                    @auth
                        @php
                            $unreadCount = auth()->user()->appNotifications()->whereNull('read_at')->count();
                            $notifRoute = auth()->user()->isStudent() ? route('student.notifications') : (auth()->user()->isLecturer() ? route('lecturer.notifications') : '#');
                        @endphp
                        <a href="{{ $notifRoute }}" class="relative p-2 text-slate-500 hover:text-brand-600 transition rounded-lg hover:bg-slate-100">
                            <i class="fa-solid fa-bell text-xl"></i>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @endauth

                    <div class="h-6 w-px bg-slate-200"></div>
                    <span class="text-xs text-slate-500 font-medium">
                        {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                    </span>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <!-- Status Alerts -->
                @if(session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                            <span class="text-sm font-medium">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 text-xl"></i>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- ITEM 2: MODAL LIVE PDF DOCUMENT VIEWER -->
    <div id="pdfViewerModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-3xl w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="p-4 bg-slate-950 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div>
                        <h3 id="pdfModalTitle" class="text-sm font-bold text-white">Pratinjau Dokumen Naskah</h3>
                        <p id="pdfModalSubtitle" class="text-[11px] text-slate-400">Live Viewer Private File Storage</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a id="pdfModalDownloadBtn" href="#" target="_blank" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i> Unduh File
                    </a>
                    <button type="button" onclick="closePdfViewer()" class="p-2 text-slate-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>
            <!-- Iframe Container -->
            <div class="flex-1 bg-slate-950 relative overflow-hidden">
                <iframe id="pdfFrame" class="w-full h-full border-0" src="about:blank"></iframe>
            </div>
        </div>
    </div>

    <!-- ITEM 3: WHATSAPP AUTO-REMINDER GATEWAY SIMULATOR MODAL -->
    <div id="waSimulatorModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-emerald-500/40 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
            <!-- Smartphone Header -->
            <div class="p-4 bg-emerald-800 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-white text-emerald-700 font-bold flex items-center justify-center text-lg">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold">WhatsApp Gateway UNIMAL</h4>
                        <p class="text-[10px] text-emerald-200">Auto-Reminder Gateway Output</p>
                    </div>
                </div>
                <button type="button" onclick="closeWaSimulator()" class="text-white hover:text-emerald-200 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <!-- WhatsApp Chat Body -->
            <div class="p-4 bg-[#efeae2] text-slate-800 space-y-3 min-h-[260px] flex flex-col justify-between">
                <div class="p-3 bg-white rounded-2xl rounded-tl-none shadow-md border border-slate-200 space-y-2 text-xs leading-relaxed">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <span class="font-extrabold text-emerald-700">📢 UNIMAL SKRIPSI NOTIFICATION</span>
                        <span class="text-[10px] text-slate-400">Sekarang</span>
                    </div>
                    <p id="waRecipientText" class="font-bold text-slate-900">Kepada: Mhs UNIMAL (0812-3456-7890)</p>
                    <div id="waPayloadText" class="text-slate-700 whitespace-pre-line bg-slate-50 p-2.5 rounded-xl border border-slate-200 font-mono text-[11px]">
                        [UNIMAL SKRIPSI] Halo Mahasiswa, status bimbingan Anda telah diperbarui oleh Dosen Pembimbing.
                    </div>
                </div>
                <div class="text-center pt-2">
                    <button type="button" onclick="closeWaSimulator()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md transition">
                        <i class="fa-solid fa-check mr-1"></i> Tutup Simulator WA Gateway
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ITEM 2: PDF Viewer Functions
        function openPdfViewer(url, title) {
            document.getElementById('pdfModalTitle').innerText = title || 'Pratinjau Dokumen Naskah';
            document.getElementById('pdfModalDownloadBtn').href = url;
            document.getElementById('pdfFrame').src = url;
            document.getElementById('pdfViewerModal').classList.remove('hidden');
        }

        function closePdfViewer() {
            document.getElementById('pdfFrame').src = 'about:blank';
            document.getElementById('pdfViewerModal').classList.add('hidden');
        }

        // ITEM 3: WhatsApp Simulator Functions
        function openWaSimulator(phone, recipient, payload) {
            document.getElementById('waRecipientText').innerText = 'Kepada: ' + recipient + ' (' + phone + ')';
            document.getElementById('waPayloadText').innerText = payload;
            document.getElementById('waSimulatorModal').classList.remove('hidden');
        }

        function closeWaSimulator() {
            document.getElementById('waSimulatorModal').classList.add('hidden');
        }
    </script>
</body>
</html>
