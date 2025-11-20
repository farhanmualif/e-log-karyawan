 <!-- Sidebar Overlay (Mobile Only) -->
 <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

 <aside id="sidebar" class="w-64 bg-teal-700 text-white flex flex-col h-screen fixed left-0 top-0 z-50 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 sidebar-default">

     <!-- Logo Section -->
     <div class="p-4 border-b border-gray-700">
         <div class="flex items-center justify-between gap-3">
             <div class="flex items-center gap-3">
                 <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                     <span class="text-white font-bold text-xl">E</span>
                 </div>
                 <span class="text-lg font-bold">E-Log Karyawan</span>
             </div>
         </div>
     </div>

     <!-- Navigation Menu -->
     <nav class="custom-scrollbar flex-1 overflow-y-auto p-4 space-y-1">
         <!-- Main Menu -->
         <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg  hover:no-underline  {{ request()->routeIs('home') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
             <i data-lucide="house" class="w-5 h-5"></i>
             <span class="text-sm font-medium">Dashboard</span>
         </a>

         <!-- Karyawan -->
         <a href="{{ route('karyawan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('karyawan') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
             <i data-lucide="users" class="w-5 h-5"></i>
             <span class="text-sm font-medium">Karyawan</span>
         </a>

         <!-- Log Aktivitas -->
         <a href="{{ route('log-aktivitas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('log-aktivitas.*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
             <i data-lucide="file-text" class="w-5 h-5"></i>
             <span class="text-sm font-medium">Log Aktivitas</span>
         </a>

         <!-- Data Master (Hanya untuk admin dan sdm) -->
         @if(in_array(Auth::user()->role, ['superadmin','admin', 'sdm']))
         <div>
             <button onclick="toggleDataMaster()" class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg hover:no-underline {{ (request()->routeIs('departemen.*') || request()->routeIs('unit.*')) ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <div class="flex items-center gap-3">
                     <i data-lucide="database" class="w-5 h-5"></i>
                     <span class="text-sm font-medium">Data Master</span>
                 </div>
                 <i data-lucide="chevron-down" id="dataMasterChevron" class="w-4 h-4 transition-transform {{ (request()->routeIs('departemen.*') || request()->routeIs('unit.*')) ? '' : 'rotate-[-90deg]' }}"></i>
             </button>
             <div id="dataMasterSection" class="{{ (request()->routeIs('departemen.*') || request()->routeIs('unit.*')) ? '' : 'hidden' }} mt-1 ml-4 space-y-1 border-l-2 border-teal-600 pl-2">
                 <a href="{{ route('departemen.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline {{ request()->routeIs('departemen.*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                     <i data-lucide="building" class="w-4 h-4"></i>
                     <span class="text-sm font-medium">Departemen</span>
                 </a>
                 <a href="{{ route('unit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline {{ request()->routeIs('unit.*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                     <i data-lucide="briefcase" class="w-4 h-4"></i>
                     <span class="text-sm font-medium">Unit</span>
                 </a>
             </div>
         </div>
         @endif

         <!-- Payroll Section -->
         <!-- <div class="pt-4 mt-4 flex flex-col gap-2 border-t border-teal-600">
             <p class="px-3 text-xs font-semibold text-gray-100 uppercase tracking-wider mb-2">PAYROLL</p>

             <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('payroll.komponen.gapok-*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <i data-lucide="settings" class="w-5 h-5"></i>
                 <span class="text-sm font-medium">Setting Gaji Pokok</span>
             </a>

             <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ (request()->routeIs('payroll.komponen.setting-index') || request()->routeIs('payroll.komponen.deleted') || request()->routeIs('payroll.komponen.show') || request()->routeIs('payroll.komponen.edit') || request()->routeIs('payroll.komponen.update') || request()->routeIs('payroll.komponen.destroy') || request()->routeIs('payroll.komponen.restore') || request()->routeIs('payroll.komponen.store')) && !request()->routeIs('payroll.komponen.gapok-*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <i data-lucide="settings" class="w-5 h-5"></i>
                 <span class="text-sm font-medium">Setting Komponen Payroll</span>
             </a>

             <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('payroll.assign.komponen.*') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <i data-lucide="user-cog" class="w-5 h-5"></i>
                 <span class="text-sm font-medium">Assign Penggajian Karyawan</span>
             </a>

             <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('payroll.periode.penggajian') || request()->routeIs('payroll.periode.detail-penggajian') || request()->routeIs('payroll.periode.slip-gaji') || request()->routeIs('payroll.periode.print-slip') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                 <span class="text-sm font-medium">Penggajian</span>
             </a>

             <a href="" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:no-underline  {{ request()->routeIs('payroll.periode.rekap-kehadiran') ? 'bg-teal-50 text-gray-800' : 'text-gray-300 hover:bg-teal-50 hover:text-gray-800' }} transition-colors">
                 <i data-lucide="calendar-check" class="w-5 h-5"></i>
                 <span class="text-sm font-medium">Rekap Kehadiran</span>
             </a>
         </div> -->
     </nav>
 </aside>
