<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Moto Audit System')</title>
    @vite(['resources/css/audit.css', 'resources/js/audit.js'])
</head>
<body class="bg-gray-50 text-gray-800 h-screen overflow-hidden flex">

    {{-- Sidebar --}}
    <aside class="w-52 bg-yazaki-red flex flex-col justify-between shrink-0">
        <div>
            {{-- Sidebar Header --}}
            <div class="px-5 py-5 border-b border-white/20">
                <h2 class="text-white font-extrabold text-base tracking-tight">Audit System</h2>
                <p class="text-white/60 text-xs mt-0.5">PT Jatim Autocomp</p>
            </div>

            {{-- Navigation --}}
            <nav class="p-3 space-y-1">
                @php
                    $homeGroup = ['audit/dashboard', 'audit/5s-standard*', 'audit/change-point-management*', 'audit/license-system*'];
                    $isHomeGroupActive = request()->is($homeGroup);
                @endphp

                {{-- Home Header / Main Item --}}
                <div class="flex items-center justify-between rounded-lg transition-all {{ request()->is('audit/dashboard') ? 'bg-white text-yazaki-red shadow-sm font-bold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <a href="/audit/dashboard" class="flex-1 flex items-center space-x-3 px-3 py-2.5 text-sm font-semibold">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Home</span>
                    </a>
                    <button type="button" id="home-toggle-btn" class="px-2.5 py-2.5 text-current opacity-70 hover:opacity-100 transition-opacity focus:outline-none" aria-label="Toggle Home Submenu">
                        <svg id="home-chevron-icon" class="w-4 h-4 transition-transform duration-200 {{ $isHomeGroupActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                {{-- Sub-items: visible when inside Home group or toggled --}}
                <div id="home-submenu" class="pl-3 space-y-1 mt-1 {{ $isHomeGroupActive ? '' : 'hidden' }}">
                    @php
                        $subItems = [
                            [
                                'label' => '5S Standard',
                                'route' => '/audit/5s-standard',
                                'path' => 'audit/5s-standard*',
                                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                            ],
                            [
                                'label' => 'Change Point',
                                'route' => '/audit/change-point-management',
                                'path' => 'audit/change-point-management*',
                                'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                            ],
                            [
                                'label' => 'License System',
                                'route' => '/audit/license-system',
                                'path' => 'audit/license-system*',
                                'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                            ],
                        ];
                    @endphp
                    @foreach($subItems as $sub)
                        <a href="{{ $sub['route'] }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold transition-all
                            {{ request()->is($sub['path']) ? 'bg-white text-yazaki-red shadow-sm font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sub['icon'] }}"/>
                            </svg>
                            <span>{{ $sub['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                {{-- Riwayat --}}
                <a href="/audit/riwayat"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all
                    {{ request()->is('audit/riwayat') ? 'bg-white text-yazaki-red shadow-sm font-bold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Riwayat</span>
                </a>

                {{-- Pedoman --}}
                <a href="/audit/pedoman"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-all
                    {{ request()->is('audit/pedoman') ? 'bg-white text-yazaki-red shadow-sm font-bold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Pedoman</span>
                </a>
            </nav>
        </div>

        {{-- Logout --}}
        <div class="p-3 border-t border-white/20">
            <form method="POST" action="/audit/logout">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-white/90 hover:bg-white/10 hover:text-white transition-all">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto px-8 py-6">
        @yield('content')
    </main>

</body>
</html>