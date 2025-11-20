<div class="bg-green-50 border border-green-200 w-96 rounded-xl p-4 flex items-start gap-3 shadow-lg animate-slide-in-right">
    <div class="flex-shrink-0">
        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
            <i data-lucide="check" class="w-4 h-4 text-white"></i>
        </div>
    </div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-green-900 mb-0.5">Berhasil!</h4>
        @if(isset($message) && $message)
        <p class="text-sm text-green-700">{{ $message }}</p>
        @endif
    </div>
    <button class="flex-shrink-0 text-green-600 hover:text-green-800 transition-colors" onclick="this.parentElement.remove()">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
</div>
