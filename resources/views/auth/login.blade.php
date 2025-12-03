@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex bg-white">
    <!-- Kolom Kiri - Form Login -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full space-y-8">

            <!-- Login Card -->
            <div class="rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                <div class="px-7">
                    <h1 class="mb-7 text-4xl font-bold text-gray-800">Selamat Datang Kembali di E Log Karyawan 👋</h1>
                    <span class="text-sm text-gray-600">Masukan username dan password untuk megakses akun anda</span>
                </div>

                <div class="px-8 py-4">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Username Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    <!-- {{ __('Username') }}
                                       -->
                                    <h1>ID User</h1>
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input id="username"
                                    name="username"
                                    type="text"
                                    autocomplete="username"
                                    required
                                    value="{{ old('username') }}"
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('username') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('username') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Masukkan ID User">
                            </div>
                            <p class="mt-2 text-xs text-gray-500 flex items-start">
                                <svg class="h-4 w-4 mr-1.5 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Masukkan ID User yang sama dengan yang Anda gunakan saat login di Khanza</span>
                            </p>
                            @error('username')
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800 flex items-start">
                                    <svg class="h-5 w-5 mr-2 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                    <span class="flex-1">{{ $message }}</span>
                            </p>
                            </div>
                            @enderror
                            @if(session('error'))
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800 flex items-start">
                                    <svg class="h-5 w-5 mr-2 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="flex-1">{{ session('error') }}</span>
                                </p>
                            </div>
                            @endif
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
                                    autocomplete="current-password"
                                    required
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('password') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Masukkan password Anda">
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

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember"
                                    name="remember"
                                    type="checkbox"
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded cursor-pointer">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                    {{ __('Ingat Saya') }}
                                </label>
                            </div>

                            <!-- @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-teal-600 hover:text-teal-500 transition-colors duration-150">
                                    {{ __('Lupa Password?') }}
                                </a>
                            </div>
                            @endif -->
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transform transition-all duration-200 hover:scale-105 active:scale-100 shadow-lg hover:shadow-xl">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-teal-200 group-hover:text-teal-100" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                {{ __('Masuk') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Register Link -->
            <!-- @if (Route::has('register'))
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-500 transition-colors duration-150">
                        {{ __('Daftar Sekarang') }}
                    </a>
                </p>
            </div>
            @endif -->

            <!-- Footer Info -->
            <div class="text-center mt-4">
                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} HR Management System. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan - Gambar Ilustrator -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#157371] items-center justify-center p-8 m-6 rounded-xl">
        <div class="w-full h-full flex items-center justify-center">
            <img src="{{ asset('images/hr-ilustrator.png') }}"
                alt="HR Management Illustration"
                class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>
@endsection
