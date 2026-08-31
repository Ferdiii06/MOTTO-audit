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
    <form method="GET" action="{{ route('riwayat') }}" id="filter-form" 
          class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-30">
        
        {{-- Hidden Inputs for Dropdown Filters --}}
        <input type="hidden" name="kategori" id="input-kategori" value="{{ request('kategori') }}">
        <input type="hidden" name="area" id="input-area" value="{{ request('area') }}">
        <input type="hidden" name="kondisi" id="input-kondisi" value="{{ request('kondisi') }}">

        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
            {{-- Date Range Filter --}}
            <div class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 w-full sm:w-auto justify-between sm:justify-start">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="bg-transparent focus:outline-none text-xs text-gray-700 font-medium cursor-pointer"
                       onchange="document.getElementById('filter-form').submit()">
                <span class="text-gray-400 font-bold">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="bg-transparent focus:outline-none text-xs text-gray-700 font-medium cursor-pointer"
                       onchange="document.getElementById('filter-form').submit()">
            </div>

            {{-- Filter Kategori --}}
            @php
                $kategoriMap = [
                    '' => 'Semua Kategori',
                    '5s_standard' => '5S Standard',
                    'change_point' => 'Change Point',
                    'license_system' => 'License System',
                ];
                $selectedKategoriLabel = $kategoriMap[request('kategori', '')] ?? 'Semua Kategori';
            @endphp
            <div class="relative inline-block text-left w-full sm:w-auto">
                <button type="button" id="kategori-filter-btn" 
                        class="w-full sm:w-auto inline-flex items-center justify-between min-w-[130px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-kategori-text">{{ $selectedKategoriLabel }}</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="kategori-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1 text-xs">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kategori" data-value="">Semua Kategori</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kategori" data-value="5s_standard">5S Standard</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kategori" data-value="change_point">Change Point</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kategori" data-value="license_system">License System</div>
                </div>
            </div>

            {{-- Filter Area (Guaranteed Downward Pop with Scrollbar) --}}
            @php
                $selectedAreaLabel = 'Semua Area';
                if (request('area')) {
                    $foundArea = $areas->firstWhere('slug', request('area'));
                    if ($foundArea) {
                        $selectedAreaLabel = $foundArea->name;
                    }
                }
            @endphp
            <div class="relative inline-block text-left w-full sm:w-auto">
                <button type="button" id="area-filter-btn" 
                        class="w-full sm:w-auto inline-flex items-center justify-between min-w-[160px] sm:max-w-[220px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-area-text" class="truncate">{{ $selectedAreaLabel }}</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="area-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-64 max-h-56 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl z-50 py-1 text-xs divide-y divide-gray-50">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-area" data-value="">Semua Area</div>
                    @foreach($areas as $area)
                        <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red transition-colors" data-input="input-area" data-value="{{ $area->slug }}">
                            {{ $area->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Filter Kondisi --}}
            @php
                $kondisiMap = [
                    '' => 'Semua Kondisi',
                    'OK' => 'OK (Lulus)',
                    'NG' => 'NG (Temuan)',
                ];
                $selectedKondisiLabel = $kondisiMap[request('kondisi', '')] ?? 'Semua Kondisi';
            @endphp
            <div class="relative inline-block text-left w-full sm:w-auto">
                <button type="button" id="kondisi-filter-btn" 
                        class="w-full sm:w-auto inline-flex items-center justify-between min-w-[120px] px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium text-gray-700 bg-gray-50 hover:bg-white focus:outline-none focus:ring-1 focus:ring-yazaki-red cursor-pointer">
                    <span id="selected-kondisi-text">{{ $selectedKondisiLabel }}</span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="kondisi-filter-menu" 
                     class="hidden absolute top-full left-0 mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1 text-xs">
                    <div class="px-3 py-2 font-semibold text-gray-800 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kondisi" data-value="">Semua Kondisi</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kondisi" data-value="OK">OK</div>
                    <div class="px-3 py-2 text-gray-700 cursor-pointer hover:bg-red-50 hover:text-yazaki-red" data-input="input-kondisi" data-value="NG">NG</div>
                </div>
            </div>

            {{-- Reset Filter Button --}}
            @if(request()->hasAny(['kategori', 'area', 'kondisi', 'start_date', 'end_date']) && (request('kategori') || request('area') || request('kondisi') || request('start_date') || request('end_date')))
                <a href="{{ route('riwayat') }}" class="px-3 py-2 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                    ✕ Reset Filter
                </a>
            @endif
        </div>

        {{-- Export Button --}}
        <a href="#" id="export-btn" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-colors shadow-sm cursor-pointer no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span>Export Excel</span>
        </a>
    </form>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 min-w-[700px]">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-5 py-4">WAKTU</th>
                        <th scope="col" class="px-5 py-4">USER / AUDITOR</th>
                        <th scope="col" class="px-5 py-4">KATEGORI</th>
                        <th scope="col" class="px-5 py-4">AREA AUDIT</th>
                        <th scope="col" class="px-5 py-4">PROSES</th>
                        <th scope="col" class="px-5 py-4 text-center">KONDISI</th>
                        <th scope="col" class="px-5 py-4 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($records as $row)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $row['waktu'] }}</td>
                            <td class="px-5 py-4 text-xs font-bold text-gray-900 whitespace-nowrap">{{ $row['user'] }}</td>
                            <td class="px-5 py-4 text-xs whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $row['kategori'] ?? '5S Standard' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-800 font-semibold whitespace-nowrap">{{ $row['area'] }}</td>
                            <td class="px-5 py-4 text-xs font-bold text-red-800 uppercase tracking-wide font-mono whitespace-nowrap">{{ $row['process'] }}</td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
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
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('audit.riwayat.detail', $row['id']) }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-yazaki-red border border-yazaki-red hover:bg-yazaki-red hover:text-white transition-colors">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-xs">
                                Tidak ada data riwayat audit yang sesuai dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Pagination --}}
        @if($paginator && $paginator->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
            </span>
            <div class="flex items-center space-x-1">
                {{-- Previous --}}
                @if($paginator->onFirstPage())
                    <span class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center text-gray-300 text-xs">&lt;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors text-xs font-semibold">&lt;</a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $last = $paginator->lastPage();
                    $current = $paginator->currentPage();
                    $pages = [];
                    for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++) {
                        $pages[] = $i;
                    }
                    if ($pages[0] > 2) array_unshift($pages, 1, '...');
                    elseif ($pages[0] == 2) array_unshift($pages, 1);
                    if (end($pages) < $last - 1) { $pages[] = '...'; $pages[] = $last; }
                    elseif (end($pages) == $last - 1) { $pages[] = $last; }
                @endphp
                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="px-1 text-xs text-gray-400 font-semibold">…</span>
                    @elseif($page == $current)
                        <span class="w-8 h-8 rounded-lg bg-yazaki-red text-white flex items-center justify-center text-xs font-bold shadow-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-xs font-semibold">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors text-xs font-semibold">&gt;</a>
                @else
                    <span class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center text-gray-300 text-xs">&gt;</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
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
                const inputId = this.getAttribute('data-input');
                const val = this.getAttribute('data-value');
                if (inputId) {
                    document.getElementById(inputId).value = val;
                }
                menu.classList.add('hidden');
                document.getElementById('filter-form').submit();
            });
        });
    }

    setupDropdown('kategori-filter-btn', 'kategori-filter-menu');
    setupDropdown('area-filter-btn', 'area-filter-menu');
    setupDropdown('kondisi-filter-btn', 'kondisi-filter-menu');

    document.addEventListener('click', function() {
        document.querySelectorAll('[id$="-menu"]').forEach(m => m.classList.add('hidden'));
    });

    // Export button: build URL with current filters
    const exportBtn = document.getElementById('export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const params = new URLSearchParams();
            const kategori = document.getElementById('input-kategori').value;
            const area = document.getElementById('input-area').value;
            const kondisi = document.getElementById('input-kondisi').value;
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;
            if (kategori) params.set('kategori', kategori);
            if (area) params.set('area', area);
            if (kondisi) params.set('kondisi', kondisi);
            if (startDate) params.set('start_date', startDate);
            if (endDate) params.set('end_date', endDate);
            const url = "{{ route('riwayat.export') }}" + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        });
    }
});
</script>
@endsection