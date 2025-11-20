<!-- Warning Alert -->
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3 shadow-lg animate-slide-in-right">
    <div class="flex-shrink-0">
        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-white"></i>
        </div>
    </div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-yellow-900 mb-0.5">Peringatan!</h4>
        <p class="text-sm text-yellow-700">{{ $message }}</p>
    </div>
    <button class="flex-shrink-0 text-yellow-600 hover:text-yellow-800 transition-colors" onclick="this.parentElement.remove()">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
</div>