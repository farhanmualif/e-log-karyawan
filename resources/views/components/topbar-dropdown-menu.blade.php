            <!-- User Avatar Button -->
            <button onclick="toggleUserMenu(event)" class="flex items-center gap-2 focus:outline-none" type="button">
                <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center text-white text-sm font-semibold hover:ring-2 hover:ring-teal-300 transition-all cursor-pointer">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div id="userDropdown" onclick="event.stopPropagation()" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                    <i data-lucide="user" class="w-4 h-4 text-gray-600"></i>
                    <span class="text-sm text-gray-700">Profile</span>
                </a>
                <!-- <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                    <i data-lucide="message-circle" class="w-4 h-4 text-gray-600"></i>
                    <span class="text-sm text-gray-700">Community</span>
                </a>
                <a href="#" class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i data-lucide="credit-card" class="w-4 h-4 text-gray-600"></i>
                        <span class="text-sm text-gray-700">Subscription</span>
                    </div>
                    <span class="bg-purple-600 text-white text-xs font-semibold px-2 py-0.5 rounded flex items-center gap-1">
                        <i data-lucide="zap" class="w-3 h-3"></i>
                        PRO
                    </span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                    <i data-lucide="settings" class="w-4 h-4 text-gray-600"></i>
                    <span class="text-sm text-gray-700">Settings</span>
                </a> -->
                <!-- <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                    <i data-lucide="help-circle" class="w-4 h-4 text-gray-600"></i>
                    <span class="text-sm text-gray-700">Help center</span>
                </a> -->
                <!-- <div class="border-t border-gray-100 my-1"></div> -->
                <button
                    type="button"
                    class="change-password-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2"
                    data-user-id="{{ Auth::id() }}"
                    data-user-name="{{ Auth::user()->username }}">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    <span>Ubah Password</span>
                </button>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-red-600">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Sign out</span>
                </a>

            </div>

            <script src="{{ asset('js/scripts/components/topbar-dropdown-menu.js') }}">

            </script>
