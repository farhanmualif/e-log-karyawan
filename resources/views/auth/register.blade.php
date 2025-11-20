@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex bg-white">
    <!-- Kolom Kiri - Form Register -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full space-y-8">

            <!-- Register Card -->
            <div class="rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                <div class="px-7">
                    <h1 class="mb-2 text-4xl font-bold text-gray-800">Buat Akun Baru 🎉</h1>
                    <span class="text-sm text-gray-600">Daftarkan diri Anda untuk mengakses sistem HR Management</span>
                </div>

                <div class="px-8 py-4">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('Nama Lengkap') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input id="name"
                                    name="name"
                                    type="text"
                                    autocomplete="name"
                                    required
                                    autofocus
                                    value="{{ old('name') }}"
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('name') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('name') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Masukkan nama lengkap Anda">
                            </div>
                            @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    {{ __('Username') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input id="username"
                                    name="username"
                                    type="text"
                                    autocomplete="username"
                                    required
                                    value="{{ old('username') }}"
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('username') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('username') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Pilih username yang unik">
                            </div>
                            @error('username')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    {{ __('Password') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('password') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Password Confirmation Field -->
                        <div>
                            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    {{ __('Konfirmasi Password') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <input id="password-confirm"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm bg-gray-50"
                                    placeholder="Ulangi password Anda">
                            </div>
                        </div>

                        <!-- Password Requirements Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-xs font-medium text-blue-800 mb-1">Ketentuan Password:</p>
                            <ul class="text-xs text-blue-700 space-y-1 ml-4 list-disc">
                                <li>Minimal 8 karakter</li>
                                <li>Gunakan kombinasi huruf dan angka</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transform transition-all duration-200 hover:scale-105 active:scale-100 shadow-lg hover:shadow-xl">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-teal-200 group-hover:text-teal-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </span>
                                {{ __('Daftar Sekarang') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500 transition-colors duration-150">
                        {{ __('Masuk Sekarang') }}
                    </a>
                </p>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-4">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} HR Management System. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan - Gambar Ilustrator -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#157371] items-center justify-center rounded-xl">
        <div class="w-full h-full flex items-center justify-center">
            <div class="text-center space-y-6">
                <div class="transform transition-all duration-300 hover:scale-105">
                    <svg class="w-64 h-64 mx-auto text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="space-y-3">
                    <h2 class="text-3xl font-bold text-white">Bergabung Bersama Kami</h2>
                    <p class="text-teal-100 text-lg max-w-md mx-auto">
                        Daftarkan akun Anda sekarang dan nikmati kemudahan mengelola sumber daya manusia di perusahaan Anda.
                    </p>
                    <div class="flex justify-center space-x-4 mt-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
