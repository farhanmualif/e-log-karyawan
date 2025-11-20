<!-- Error Alert -->
<div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 shadow-lg animate-slide-in-right">
    <div class="flex-shrink-0">
        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
            <i data-lucide="x-circle" class="w-4 h-4 text-white"></i>
        </div>
    </div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-red-900 mb-0.5">Terjadi Kesalahan!</h4>
        <p class="text-sm text-red-700">{{ $message }}</p>
    </div>
    <button class="flex-shrink-0 text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove()">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
</div>
