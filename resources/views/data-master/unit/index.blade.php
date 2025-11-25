@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Master Unit</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola data unit perusahaan</p>
            </div>

            <!-- Search, Filters & Add Button -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Bar -->
                <div class="relative flex-1 sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text"
                        id="searchInput"
                        placeholder="Cari Unit..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white">
                </div>

                <!-- Departemen Filter -->
                <select id="filterDepartemen" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white cursor-pointer">
                    <option value="">Semua Departemen</option>
                    @foreach($departemen as $dept)
                    <option value="{{ $dept->nama }}">{{ $dept->nama }}</option>
                    @endforeach
                </select>

                <a href="{{ route('unit.create') }}" class="flex items-center gap-2 px-3 py-1.5 bg-teal-600 text-white rounded-md hover:bg-teal-700 hover:no-underline transition-colors text-sm font-medium whitespace-nowrap">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Unit
                </a>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="unitTableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($unit as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                            <div class="font-medium">{{ $item->nama }}</div>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->nama_departemen }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('unit.edit', $item->id) }}" class="text-teal-600 hover:text-teal-900" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('unit.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-sm text-gray-500">
                            Tidak ada data unit
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
<script src="{{ asset('js/scripts/data-master/unit/unit.index.js') }}"></script>
@endsection
