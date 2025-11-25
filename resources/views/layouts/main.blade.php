@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-gradient-to-br from-teal-50/20 via-teal-50/10 to-gray-50">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content Area -->
    <div id="mainContent" class="flex-1 flex flex-col overflow-hidden md:ml-64 transition-all duration-300 ease-in-out sidebar-visible">
        <!-- Top Bar -->
        @include('components.topbar')

        <!-- Floating Alert Container (Top Right) -->
        <div class="fixed top-4 right-4 z-50 space-y-3 max-w-md" id="alertContainer">
            @if(session('success'))
            @include('components.alerts.success-alert',['message'=>session('success')])
            @endif
            @if(session('info'))
            @include('components.alerts.info-alert',['message'=>session('info')])
            @endif
            @if(session('warning'))
            @include('components.alerts.warning-alert',['message'=>session('warning')])
            @endif
            @if(session('error'))
            @if(session('redirectToDeleted'))
            <!-- Custom Error Alert with Link to Deleted Page -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-lg animate-slide-in-right">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4 text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-red-900 mb-0.5">Terjadi Kesalahan!</h4>
                    <p class="text-sm text-red-700 mb-2">{{ session('error') }}</p>
                    <a href="{{ route('payroll.komponen.gapok-deleted') }}" class="inline-flex items-center gap-1 text-sm font-medium text-red-700 hover:text-red-900 underline transition-colors">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        Lihat Gaji Pokok Terhapus
                    </a>
                </div>
                <button class="flex-shrink-0 text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove()">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            @else
            @include('components.alerts.error-alert',['message'=>session('error')])
            @endif
            @endif

            @if(session('validation_errors'))
            @include('components.alerts.validation-alert', [
            'errors' => session('validation_errors')
            ])
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gradient-to-br from-teal-50/15 via-teal-50/8 to-gray-50/50" style="height: 0; min-height: 0;">
            <div style="min-height: 100%; padding-bottom: 2rem;">
                @yield('page-content')
            </div>
        </main>
    </div>
</div>


@include('components.change-password-modal')


@endsection
