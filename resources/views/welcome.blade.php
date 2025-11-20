<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HR Ludira') }} - Human Resources Management System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-teal {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 50%, #0f766e 100%);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-teal-50 via-teal-100/50 to-white">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">H</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Sistem E-Log Karyawan</span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                    <a href="{{ url('/home') }}" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">
                        Dashboard
                    </a>
                    @else
                    <!-- <a href="{{ route('login') }}" class="text-gray-700 hover:text-teal-600 transition-colors font-medium">
                        Login
                    </a> -->
                    @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-6 py-2 rounded-lg font-medium hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        Login
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-20 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="animate-slide-up">
                    <div class="inline-block mb-4">
                        <span class="bg-teal-100 text-teal-700 text-sm font-semibold px-4 py-2 rounded-full">
                            ✨E-Log Ludira Husada Tama
                        </span>
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                        Catat Kegiatan Anda
                        <span class="bg-gradient-to-r from-teal-600 to-teal-500 bg-clip-text text-transparent">
                            Lebih Mudah
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        <!-- Platform HRMS untuk mengelola data karyawan, absensi, payroll, dan kinerja tim dengan efisien dan terintegrasi. -->
                        Platform untuk mencatat ketiatan karyawan dengan efisien dan dan mudah.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @guest
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                            Masuk
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                        <!-- <a href="{{ route('login') }}" class="border-2 border-teal-500 text-teal-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-teal-50 transition-all flex items-center justify-center gap-2">
                            Masuk
                        </a> -->
                        @else
                        <a href="{{ url('/home') }}" class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                            Buka Dashboard
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                        @endguest
                    </div>
                </div>

                <!-- Right Illustration -->
                <div class="relative animate-float">
                    <div class="relative z-10">
                        <div class="bg-gradient-to-br from-teal-500/20 to-teal-600/20 rounded-3xl p-8 backdrop-blur-sm border border-teal-200/50">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded-xl p-6 shadow-lg">
                                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                                        <i data-lucide="users" class="w-6 h-6 text-teal-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">150+</h3>
                                    <p class="text-sm text-gray-600">Total Karyawan</p>
                                </div>
                                <div class="rounded-xl p-6">
                                    <!-- <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                                        <i data-lucide="calendar-check" class="w-6 h-6 text-teal-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">98%</h3>
                                    <p class="text-sm text-gray-600">Absensi</p> -->
                                </div>
                                <div class="rounded-xl p-6">
                                    <!-- <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                                        <i data-lucide="trending-up" class="w-6 h-6 text-teal-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">90%</h3>
                                    <p class="text-sm text-gray-600">Kinerja</p> -->
                                </div>
                                <div class="bg-white rounded-xl p-6 shadow-lg">
                                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                                        <i data-lucide="user" class="w-6 h-6 text-teal-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">12</h3>
                                    <p class="text-sm text-gray-600"> Log aktifitas karyawan Tercatat
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative circles -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-teal-300/30 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-400/30 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <!-- <section class="py-20 bg-white/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Semua yang Anda butuhkan untuk mengelola sumber daya manusia dengan efisien
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="users" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Karyawan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola data karyawan, informasi personal, kontrak kerja, dan dokumen penting dalam satu platform terintegrasi.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="calendar-clock" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Absensi & Cuti</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem absensi digital dengan tracking real-time, pengajuan cuti online, dan manajemen jadwal kerja yang fleksibel.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Payroll & Gaji</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Perhitungan gaji otomatis, pengelolaan tunjangan, potongan, dan slip gaji digital yang terintegrasi.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="trending-up" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Evaluasi Kinerja</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem penilaian kinerja karyawan dengan KPI tracking, review berkala, dan laporan analisis performa.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="file-text" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekrutmen & Hiring</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Manajemen proses rekrutmen dari posting lowongan, screening kandidat, hingga onboarding karyawan baru.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="bar-chart-3" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics & Report</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dashboard analytics lengkap dengan laporan HR yang dapat diekspor, visualisasi data, dan insights untuk pengambilan keputusan.
                    </p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- CTA Section -->
    <!-- <section class="py-20 bg-gradient-to-r from-teal-500 to-teal-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-extrabold text-white mb-6">
                Siap Meningkatkan Manajemen HR Anda?
            </h2>
            <p class="text-xl text-teal-100 mb-8">
                Mulai gunakan HR Ludira hari ini dan rasakan kemudahan dalam mengelola sumber daya manusia.
            </p>
            @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-teal-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-teal-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                    Daftar Sekarang
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                    Masuk ke Akun
                </a>
            </div>
            @else
            <a href="{{ url('/home') }}" class="inline-block bg-white text-teal-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-teal-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                Buka Dashboard
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            @endguest
        </div>
    </section> -->

    <!-- Footer -->
    <!-- <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">H</span>
                        </div>
                        <span class="text-xl font-bold text-white">HR Ludira</span>
                    </div>
                    <p class="text-sm text-gray-400">
                        Sistem Manajemen Sumber Daya Manusia yang modern dan terpercaya.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Fitur</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Security</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Kontak</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Karir</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition-colors">Status</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} HR Ludira. All rights reserved.</p>
            </div>
        </div>
    </footer> -->

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
