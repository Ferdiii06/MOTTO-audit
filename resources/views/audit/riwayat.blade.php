@extends('audit.layout')

@section('title', 'Riwayat Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Riwayat Audit</h1>
        <p class="text-sm text-gray-500 mt-1">Menampilkan semua catatan audit keselamatan dan operasional lapangan.</p>
    </div>

    {{-- 4 Metric Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: TOTAL AUDIT --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">TOTAL AUDIT</span>
            <p class="text-3xl font-extrabold text-gray-900 mt-3">{{ $stats['total_audit'] }}</p>
        </div>

        {{-- Card 2: LULUS (OK) --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">LULUS (OK)</span>
            <p class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $stats['lulus_ok'] }}</p>
        </div>

        {{-- Card 3: TEMUAN (NG) --}}
        <div class="bg-red-100/70 border border-red-200 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-red-800">TEMUAN (NG)</span>
            <p class="text-3xl font-extrabold text-red-600 mt-3">{{ $stats['temuan_ng'] }}</p>
        </div>

        {{-- Card 4: KEPATUHAN --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">KEPATUHAN</span>
            <p class="text-3xl font-extrabold text-yazaki-red mt-3">{{ $stats['kepatuhan'] }}</p>
        </div>
    </div>

    {{-- Filters & Export Action Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex flex-wrap items-center justify-between gap-3 relative z-30">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Date Range --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="text" value="24/05/2026 - 24/05/2026" 
                       class="pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
            </div>

            {{-- Filter Kategori --}}
            <div class="relative inline-block text-left">
                <button type="button" id="kategori-filter-btn" 
                        class="inline-flex items-center justify-between min-w-[130px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-kategori-text">Semua Kategori</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="kategori-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1 text-xs">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="">Semua Kategori</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="5s_standard">5S Standard</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="change_point">Change Point</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="license_system">License System</div>
                </div>
            </div>

            {{-- Filter Area (Guaranteed Downward Pop with Scrollbar) --}}
            <div class="relative inline-block text-left">
                <button type="button" id="area-filter-btn" 
                        class="inline-flex items-center justify-between min-w-[160px] max-w-[220px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-area-text" class="truncate">Semua Area</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="area-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-64 max-h-56 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl z-50 py-1 text-xs divide-y divide-gray-50">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="">Semua Area</div>
                    @foreach($areas as $area)
                        <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red transition-colors" data-value="{{ $area->slug }}">
                            {{ $area->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Filter Kondisi --}}
            <div class="relative inline-block text-left">
                <button type="button" id="kondisi-filter-btn" 
                        class="inline-flex items-center justify-between min-w-[120px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-kondisi-text">Semua Kondisi</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="kondisi-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1 text-xs">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="">Semua Kondisi</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="OK">OK</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-value="NG">NG</div>
                </div>
            </div>
        </div>

        {{-- Export Button --}}
        <button type="button" class="inline-flex items-center space-x-2 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Export</span>
        </button>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">WAKTU</th>
                        <th scope="col" class="px-6 py-4">USER</th>
                        <th scope="col" class="px-6 py-4">AREA</th>
                        <th scope="col" class="px-6 py-4">PROCESS</th>
                        <th scope="col" class="px-6 py-4 text-center">KONDISI</th>
                        <th scope="col" class="px-6 py-4 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($records as $row)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $row['waktu'] }}</td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-900 whitespace-nowrap">{{ $row['user'] }}</td>
                            <td class="px-6 py-4 text-xs text-gray-600 font-medium whitespace-nowrap">{{ $row['area'] }}</td>
                            <td class="px-6 py-4 text-xs font-bold text-red-800 uppercase tracking-wide font-mono whitespace-nowrap">{{ $row['process'] }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($row['kondisi'] === 'NG')
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-extrabold bg-red-800 text-white shadow-xs">
                                        NG
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ url('audit/placeholder') }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-yazaki-red border border-yazaki-red hover:bg-yazaki-red hover:text-white transition-colors">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Table Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center sm:justify-end space-x-1">
            <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors text-xs font-semibold">
                &lt;
            </button>
            <button class="w-8 h-8 rounded-lg bg-yazaki-red text-white flex items-center justify-center text-xs font-bold shadow-xs">
                1
            </button>
            <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-xs font-semibold">
                2
            </button>
            <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-xs font-semibold">
                3
            </button>
            <span class="px-2 text-xs text-gray-400 font-semibold">...</span>
            <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-xs font-semibold">
                12
            </button>
            <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors text-xs font-semibold">
                &gt;
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupDropdown(btnId, menuId, textId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        const text = document.getElementById(textId);
        if (!btn || !menu) return;

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('[id$="-menu"]').forEach(m => {
                if (m.id !== menuId) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        });

        menu.querySelectorAll('[data-value]').forEach(item => {
            item.addEventListener('click', function() {
                if (text) text.textContent = this.textContent.trim();
                menu.classList.add('hidden');
            });
        });
    }

    setupDropdown('kategori-filter-btn', 'kategori-filter-menu', 'selected-kategori-text');
    setupDropdown('area-filter-btn', 'area-filter-menu', 'selected-area-text');
    setupDropdown('kondisi-filter-btn', 'kondisi-filter-menu', 'selected-kondisi-text');

    document.addEventListener('click', function() {
        document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
    });
});
</script>
@endsection