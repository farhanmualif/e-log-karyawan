<div id="changePasswordModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeChangePasswordModal()"></div>

        <!-- Modal Panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-white" id="changePasswordModalTitle">Ubah Password</h3>
                    <button type="button" onclick="closeChangePasswordModal()" class="text-white hover:text-teal-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <form id="changePasswordForm" method="post" action="" class="px-4 sm:px-6 py-4">
                @method('put')
                @csrf
                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password"
                            id="changePasswordNew"
                            name="password"
                            class="w-full px-3 sm:px-4 py-2 pr-10 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            placeholder="Masukkan password baru"
                            required>
                        <button type="button"
                            onclick="togglePasswordVisibility('changePasswordNew', 'changePasswordToggleIcon1')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" id="changePasswordToggleIcon1" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password"
                            id="changePasswordConfirm"
                            name="password_confirmation"
                            class="w-full px-3 sm:px-4 py-2 pr-10 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                            placeholder="Konfirmasi password baru"
                            required>
                        <button type="button"
                            onclick="togglePasswordVisibility('changePasswordConfirm', 'changePasswordToggleIcon2')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" id="changePasswordToggleIcon2" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </button>
                    </div>
                </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeChangePasswordModal()" class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm font-medium">
                    Batal
                </button>
                    <button type="submit" class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-xs sm:text-sm font-medium">
                    Simpan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Lempar change password route ke change-password-modal.js
    window.changePasswordRoute = "{{ route('karyawan.update-password', Auth::user()->id) }}"
</script>
<script src="{{ asset('js/scripts/components/change-password-modal.js') }}"></script>
