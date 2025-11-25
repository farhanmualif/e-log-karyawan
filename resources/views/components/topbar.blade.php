<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <button id="toggleSidebar" class="p-2 hover:bg-teal-500 hover:text-white focus:outline-none focus:ring-0 rounded-lg transition-colors" title="Toggle Sidebar">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
    </div>
    <div class="flex items-center gap-4">
        <!-- <div class="relative">
            <input type="text" placeholder="Quick Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
        </div> -->
        <!-- <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <i data-lucide="message-square" class="w-5 h-5 text-gray-600"></i>
        </button>
        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors relative">
            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button> -->
        <div class="relative" id="userMenuContainer">
            <!-- User Avatar Button -->
            @include('components.topbar-dropdown-menu')
        </div>

        <!-- Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>
</header>
