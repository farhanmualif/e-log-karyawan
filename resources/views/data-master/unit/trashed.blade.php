@extends('layouts.main')

@section('page-content')
<div class="p-6 pb-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <!-- Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Unit Terhapus</h1>
                <p class="text-sm text-gray-600 mt-1">Daftar unit yang sudah dihapus</p>
            </div>

            <!-- Back Button -->
            <div class="flex items-center gap-3">
                <a href="{{ route('unit.index') }}" class="flex items-center gap-2 px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 hover:no-underline transition-colors text-sm font-medium whitespace-nowrap">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
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
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dihapus</th>
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
                            {{ $item->nama_departemen ?? 'Departemen sudah dihapus' }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('unit.restore', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan unit ini?');">
                                @csrf
                                <button type="submit" class="text-teal-600 hover:text-teal-900" title="Kembalikan">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">
                            Tidak ada unit yang dihapus
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

