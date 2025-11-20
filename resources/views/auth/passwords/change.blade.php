@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex bg-white">
    <!-- Kolom Kiri - Form Ubah Password -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full space-y-8">

            <!-- Change Password Card -->
            <div class="rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                <div class="px-7">
                    <h1 class="mb-7 text-4xl font-bold text-gray-800">Ubah Password 🔒</h1>
                    <span class="text-sm text-gray-600">Anda menggunakan password default. Silakan ubah password Anda untuk keamanan akun</span>
                </div>

                <div class="px-8 py-4">
                    @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-2"></i>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-2"></i>
                            <p class="text-sm font-medium text-red-800">Terjadi kesalahan</p>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.change') }}" class="space-y-6">
                        @csrf

                        <!-- Password Baru Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <i data-lucide="lock" class="w-5 h-5 text-teal-500 mr-2"></i>
                                    {{ __('Password Baru') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="key" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('password') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('password') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Masukkan password baru (min. 6 karakter)">
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <i data-lucide="lock" class="w-5 h-5 text-teal-500 mr-2"></i>
                                    {{ __('Konfirmasi Password') }}
                                </div>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="key-round" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    class="appearance-none block w-full pl-10 pr-3 py-3 border @error('password_confirmation') border-red-300 @else border-gray-300 @enderror rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm @error('password_confirmation') bg-red-50 @else bg-gray-50 @enderror"
                                    placeholder="Ulangi password baru">
                            </div>
                            @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i data-lucide="info" class="w-5 h-5 text-teal-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-teal-800">
                                    <p class="font-medium mb-1">Tips Password yang Aman:</p>
                                    <ul class="list-disc list-inside space-y-1 text-teal-700">
                                        <li>Gunakan minimal 6 karakter</li>
                                        <li>Kombinasikan huruf dan angka</li>
                                        <li>Jangan gunakan password yang mudah ditebak</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transform transition-all duration-200 hover:scale-105 active:scale-100 shadow-lg hover:shadow-xl">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i data-lucide="save" class="w-5 h-5 text-teal-200 group-hover:text-teal-100"></i>
                                </span>
                                {{ __('Simpan Password') }}
                            </button>
                        </div>
                    </form>
                </div>
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
    <div class="hidden lg:flex lg:w-1/2 bg-[#157371] items-center justify-center p-8 m-6 rounded-xl">
        <div class="w-full h-full flex items-center justify-center">
            <img src="{{ asset('images/hr-ilustrator.png') }}"
                alt="HR Management Illustration"
                class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>
@endsection
