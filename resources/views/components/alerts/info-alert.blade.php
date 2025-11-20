<!-- Info Alert -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3 shadow-lg animate-slide-in-right">
    <div class="flex-shrink-0">
        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
            <i data-lucide="info" class="w-4 h-4 text-white"></i>
        </div>
    </div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-blue-900 mb-0.5">Informasi</h4>
        <p class="text-sm text-blue-700">{{ $message }}</p>
    </div>
    <button class="flex-shrink-0 text-blue-600 hover:text-blue-800 transition-colors" onclick="this.parentElement.remove()">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
</div>
