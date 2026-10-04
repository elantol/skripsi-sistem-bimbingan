<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Bimbingan Skripsi Universitas Malikussaleh (UNIMAL)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        unimal: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#022c22',
                            gold: '#f59e0b',
                            goldHover: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float3D {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float-3d { animation: float3D 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-emerald-50/50 to-slate-100 text-slate-800 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-x-hidden antialiased selection:bg-emerald-600 selection:text-white">

    <!-- AMBIENT DECORATIVE BACKGROUND -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-emerald-200/40 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-amber-200/30 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#059669_1px,transparent_1px)] [background-size:32px_32px] opacity-10"></div>
    </div>

    <!-- MAIN CONTAINER (UNIMAL GREEN & WHITE THEME) -->
    <div class="relative z-10 w-full max-w-5xl bg-white rounded-3xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 shadow-2xl shadow-emerald-950/10 border border-slate-200/80 my-auto">

        <!-- LEFT COLUMN: UNIMAL GREEN HERO BRANDING -->
        <div class="lg:col-span-5 bg-gradient-to-br from-emerald-800 via-emerald-900 to-teal-900 p-8 flex flex-col justify-between relative overflow-hidden text-white">
            
            <!-- Background Watermark Pattern -->
            <div class="absolute -right-16 -bottom-16 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo-unimal.png') }}" alt="UNIMAL Watermark" class="w-96 h-96 object-contain filter brightness-200">
            </div>

            <!-- UNIMAL BRANDING HEADER -->
            <div class="space-y-4 relative z-10">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md p-1 border border-white/20 shadow-xl flex-shrink-0 flex items-center justify-center">
                        <img src="{{ asset('images/logo-unimal.png') }}" alt="Logo UNIMAL" class="w-11 h-11 object-contain drop-shadow-md">
                    </div>
                    <div>
                        <h1 class="text-lg font-extrabold text-white tracking-tight leading-snug">UNIVERSITAS MALIKUSSALEH</h1>
                        <p class="text-xs font-bold text-amber-300">Teknik Informatika &bull; Reuleut</p>
                        <p class="text-[10px] text-emerald-200 font-semibold tracking-wider uppercase mt-0.5">SkripsiGuidance C4.5 System</p>
                    </div>
                </div>
            </div>

            <!-- 3D ISOMETRIC ILLUSTRATION -->
            <div class="my-6 relative z-10 flex flex-col items-center justify-center animate-float-3d">
                <svg class="w-full max-w-[280px] h-auto drop-shadow-xl" viewBox="0 0 450 360" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="cubeTop" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#34d399"/>
                            <stop offset="100%" stop-color="#059669"/>
                        </linearGradient>
                        <linearGradient id="cubeFront" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#047857"/>
                            <stop offset="100%" stop-color="#065f46"/>
                        </linearGradient>
                        <linearGradient id="cubeSide" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#022c22"/>
                            <stop offset="100%" stop-color="#0f172a"/>
                        </linearGradient>
                        <linearGradient id="goldPlate" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#fbbf24"/>
                            <stop offset="100%" stop-color="#b45309"/>
                        </linearGradient>
                    </defs>

                    <!-- 3D PLATFORM -->
                    <g transform="translate(45, 120)">
                        <polygon points="180,20 360,110 180,200 0,110" fill="url(#cubeTop)"/>
                        <polygon points="180,200 360,110 360,135 180,225" fill="url(#cubeFront)"/>
                        <polygon points="0,110 180,200 180,225 0,135" fill="url(#cubeSide)"/>

                        <!-- Laptop Screen 3D -->
                        <g transform="translate(80, 20)">
                            <polygon points="100,20 200,70 100,120 0,70" fill="#1e293b"/>
                            <polygon points="100,120 200,70 200,80 100,130" fill="#0f172a"/>
                            <polygon points="0,70 100,120 100,130 0,80" fill="#334155"/>
                            <polygon points="100,20 200,70 200,-30 100,-80" fill="#064e3b" stroke="#34d399" stroke-width="3"/>
                            <polygon points="108,12 192,54 192,-24 108,-66" fill="#022c22"/>
                            <rect x="120" y="-45" width="50" height="6" transform="skewY(24)" fill="#fbbf24"/>
                            <rect x="120" y="-30" width="65" height="5" transform="skewY(24)" fill="#34d399"/>
                            <rect x="120" y="-18" width="40" height="5" transform="skewY(24)" fill="#38bdf8"/>
                        </g>

                        <!-- Thesis Document 3D -->
                        <g transform="translate(-10, -50)">
                            <polygon points="60,0 120,30 60,60 0,30" fill="#ffffff"/>
                            <polygon points="60,60 120,30 120,110 60,140" fill="#f1f5f9"/>
                            <polygon points="0,30 60,60 60,140 0,110" fill="#e2e8f0"/>
                            <rect x="15" y="45" width="30" height="4" transform="skewY(24)" fill="#047857"/>
                            <rect x="15" y="55" width="38" height="3" transform="skewY(24)" fill="#94a3b8"/>
                            <rect x="15" y="63" width="35" height="3" transform="skewY(24)" fill="#94a3b8"/>
                            <g transform="translate(45, 75)">
                                <circle cx="20" cy="20" r="14" fill="#f59e0b" stroke="#ffffff" stroke-width="2"/>
                                <text x="13" y="25" fill="#ffffff" font-size="11" font-weight="900">v3</text>
                            </g>
                        </g>

                        <!-- C4.5 Decision Tree Nodes 3D -->
                        <g transform="translate(230, -30)">
                            <circle cx="40" cy="20" r="14" fill="url(#goldPlate)" stroke="#ffffff" stroke-width="2"/>
                            <line x1="40" y1="34" x2="15" y2="60" stroke="#fbbf24" stroke-width="3"/>
                            <line x1="40" y1="34" x2="65" y2="60" stroke="#fbbf24" stroke-width="3"/>
                            <circle cx="15" cy="60" r="10" fill="#10b981"/>
                            <circle cx="65" cy="60" r="10" fill="#ef4444"/>
                        </g>

                        <!-- Checkmark Shield Badge -->
                        <g transform="translate(140, 110)">
                            <polygon points="20,0 40,10 40,30 20,40 0,30 0,10" fill="url(#goldPlate)"/>
                            <path d="M10 18 L16 24 L28 12" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </g>
                </svg>

                <div class="mt-3 text-center">
                    <p class="text-sm font-extrabold text-white tracking-wide">Portal Bimbingan Skripsi UNIMAL</p>
                    <p class="text-xs text-emerald-200 font-medium mt-0.5">Fakultas Teknik &bull; Universitas Malikussaleh</p>
                </div>
            </div>

            <!-- UNIMAL FOOTER INFO -->
            <div class="space-y-2 relative z-10 pt-4 border-t border-emerald-700/60 text-xs text-emerald-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-amber-300"></i>
                    <span>Sistem Bimbingan Digital Fakultas Teknik</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-amber-300"></i>
                    <span>Mesin Prediksi Kelulusan Decision Tree C4.5</span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: CLEAN WHITE LOGIN FORM -->
        <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between bg-white">
            <div>
                <!-- ACTIVE SESSION WARNING BANNER -->
                @auth
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-3 shadow-sm">
                        <div class="flex items-center gap-2 font-bold text-sm text-amber-900">
                            <i class="fa-solid fa-shield-halved text-amber-600 text-lg"></i>
                            <span>Sesi Login Aktif Ditemukan!</span>
                        </div>
                        <p>Saat ini masuk sebagai: <strong class="text-slate-900">{{ auth()->user()->name }}</strong> (<span class="uppercase font-bold text-amber-700">{{ auth()->user()->role }}</span>)</p>
                        <div class="flex items-center gap-2 pt-1">
                            @if(auth()->user()->isStudent())
                                <a href="{{ route('student.dashboard') }}" class="flex-1 py-2.5 px-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-center transition text-xs shadow-md">Ke Portal Mahasiswa &rarr;</a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex-1 py-2.5 px-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-center transition text-xs shadow-md">Ke Portal Admin &rarr;</a>
                            @else
                                <a href="{{ route('lecturer.dashboard') }}" class="flex-1 py-2.5 px-3 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-xl text-center transition text-xs shadow-md">Ke Portal Dosen &rarr;</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="py-2.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition text-xs flex items-center gap-1 shadow-md">
                                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- ROLE SELECTOR TABS -->
                <div class="mb-6">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-layer-group"></i> Pilih Portal Akses UNIMAL
                    </p>
                    <div class="grid grid-cols-2 gap-2 p-1.5 bg-slate-100 rounded-2xl border border-slate-200">
                        <button type="button" id="tab-student" onclick="switchPortal('student')" class="py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 bg-emerald-700 text-white shadow-md text-xs font-extrabold border border-emerald-800">
                            <i class="fa-solid fa-graduation-cap text-base text-amber-300"></i>
                            <span>Portal Mahasiswa</span>
                        </button>
                        <button type="button" id="tab-admin" onclick="switchPortal('admin')" class="py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 text-xs font-bold">
                            <i class="fa-solid fa-user-shield text-base text-amber-600"></i>
                            <span>Portal Admin / Dosen</span>
                        </button>
                    </div>
                </div>



                @if(session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm text-center font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm text-center font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- FORM INPUT LOGIN -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email UNIMAL / Skripsi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-700">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email', 'mahasiswa1@skripsi.ac.id') }}" required autofocus
                                placeholder="nama@skripsi.ac.id atau nim@mhs.unimal.ac.id"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:bg-white transition text-sm">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi / Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-700">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" value="password" required
                                placeholder="••••••••"
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:bg-white transition text-sm">
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                            <span>Ingat sesi saya</span>
                        </label>
                    </div>

                    <button type="submit" id="btn-submit" class="w-full py-3.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-700/25 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition transform active:scale-[0.98] text-sm flex items-center justify-center gap-2">
                        <span>Masuk ke Portal Mahasiswa UNIMAL</span>
                        <i class="fa-solid fa-arrow-right-to-bracket text-amber-300"></i>
                    </button>
                </form>
            </div>

            <!-- DEMO QUICK LOGIN PRESETS -->
            <div class="mt-6 pt-5 border-t border-slate-200">
                <p class="text-center text-[11px] font-extrabold text-amber-700 uppercase tracking-wider mb-2.5 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-bolt"></i> Uji Coba Cepat (Click 1x to Auto Login)
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillAndLogin('mahasiswa1@skripsi.ac.id', 'student')" class="py-2.5 px-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-xl text-emerald-900 text-[11px] font-bold text-center transition flex flex-col items-center justify-center gap-1 group shadow-sm">
                        <i class="fa-solid fa-graduation-cap text-base text-emerald-700 group-hover:scale-110 transition"></i>
                        <span>Mahasiswa</span>
                    </button>
                    <button type="button" onclick="fillAndLogin('dosen1@skripsi.ac.id', 'admin')" class="py-2.5 px-2 bg-teal-50 hover:bg-teal-100 border border-teal-300 rounded-xl text-teal-900 text-[11px] font-bold text-center transition flex flex-col items-center justify-center gap-1 group shadow-sm">
                        <i class="fa-solid fa-user-tie text-base text-teal-700 group-hover:scale-110 transition"></i>
                        <span>Dosen</span>
                    </button>
                    <button type="button" onclick="fillAndLogin('admin@skripsi.ac.id', 'admin')" class="py-2.5 px-2 bg-amber-50 hover:bg-amber-100 border border-amber-300 rounded-xl text-amber-900 text-[11px] font-bold text-center transition flex flex-col items-center justify-center gap-1 group shadow-sm">
                        <i class="fa-solid fa-user-gear text-base text-amber-700 group-hover:scale-110 transition"></i>
                        <span>Admin</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT ROLE SWITCHING & AUTO LOGIN -->
    <script>
        function switchPortal(role) {
            const tabStudent = document.getElementById('tab-student');
            const tabAdmin = document.getElementById('tab-admin');
            const emailInput = document.getElementById('email');
            const btnSubmit = document.getElementById('btn-submit');

            if (role === 'student') {
                tabStudent.className = 'py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 bg-emerald-700 text-white shadow-md text-xs font-extrabold border border-emerald-800';
                tabAdmin.className = 'py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 text-xs font-bold';
                if (emailInput.value === 'admin@skripsi.ac.id' || emailInput.value === 'dosen1@skripsi.ac.id') {
                    emailInput.value = 'mahasiswa1@skripsi.ac.id';
                }
                btnSubmit.innerHTML = '<span>Masuk ke Portal Mahasiswa UNIMAL</span> <i class="fa-solid fa-arrow-right-to-bracket text-amber-300"></i>';
            } else {
                tabAdmin.className = 'py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 bg-amber-600 text-white shadow-md text-xs font-extrabold border border-amber-700';
                tabStudent.className = 'py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 text-xs font-bold';
                if (emailInput.value === 'mahasiswa1@skripsi.ac.id') {
                    emailInput.value = 'dosen1@skripsi.ac.id';
                }
                btnSubmit.innerHTML = '<span>Masuk ke Portal Admin / Dosen UNIMAL</span> <i class="fa-solid fa-arrow-right-to-bracket text-amber-300"></i>';
            }
        }

        function fillAndLogin(email, role) {
            switchPortal(role);
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            document.forms[0].submit();
        }
    </script>
</body>
</html>
