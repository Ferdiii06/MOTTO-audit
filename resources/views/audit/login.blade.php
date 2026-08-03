<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Moto Audit System</title>
    <meta name="description" content="Login page for Moto Audit System - PT Jatim Autocomp Indonesia">
    @vite(['resources/css/audit.css', 'resources/js/audit.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md flex flex-col items-center">

        {{-- YAZAKI Logo Placeholder --}}
        <div class="flex items-center space-x-2 mb-8">
            {{-- Red chevron icon as logo placeholder --}}
            <svg class="w-10 h-10 text-yazaki-red" viewBox="0 0 40 40" fill="currentColor">
                <path d="M8 8 L20 20 L8 32 L14 32 L26 20 L14 8 Z"/>
                <path d="M16 8 L28 20 L16 32 L22 32 L34 20 L22 8 Z"/>
            </svg>
            <span class="text-3xl font-extrabold text-yazaki-red tracking-tight">YAZAKI</span>
        </div>

        {{-- Login Card --}}
        <div class="w-full bg-white rounded-xl shadow-lg border-t-4 border-yazaki-red p-8">

            <div class="text-center mb-8">
                <h1 class="text-xl font-bold text-gray-900">Moto Audit System</h1>
                <p class="text-sm text-yazaki-red mt-1">Sign in to access your dashboard</p>
            </div>

            <form method="POST" action="/audit/login" class="space-y-5">
                @csrf

                {{-- Error Alert --}}
                @if($errors->any())
                    <div class="bg-red-50 text-red-700 text-sm font-semibold p-3 rounded-lg border border-red-200 text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Username / NIK --}}
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Username / NIK
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-yazaki-red/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yazaki-red/30 focus:border-yazaki-red transition"
                            placeholder="Enter your NIK" required>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Password / PIN
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password"
                            class="block w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yazaki-red/30 focus:border-yazaki-red transition"
                            placeholder="••••••••" required>
                        {{-- Show/Hide Toggle --}}
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            {{-- Eye icon (visible when password hidden) --}}
                            <svg class="icon-eye w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Eye-off icon (visible when password shown) --}}
                            <svg class="icon-eye-off w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Login Button --}}
                <button type="submit" class="w-full bg-yazaki-red hover:bg-yazaki-red-dark text-white font-bold py-3 rounded-lg flex items-center justify-center space-x-2 transition duration-200 shadow-sm text-sm cursor-pointer">
                    <span>Login</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</body>
</html>
