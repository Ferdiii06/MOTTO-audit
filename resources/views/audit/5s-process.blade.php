@extends('audit.layout')

@section('title', 'Pilih Process - ' . $area['name'] . ' - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Header & Breadcrumb --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ url('audit/5s-standard') }}" class="hover:text-yazaki-red">5S Standard</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">{{ $area['name'] }}</span>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Pilih Process Audit: <span class="text-yazaki-red">{{ $area['name'] }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $area['desc'] }}
                </p>
            </div>

            <a href="{{ url('audit/5s-standard') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 px-3.5 py-2 rounded-lg transition-colors shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Area</span>
            </a>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 font-semibold flex items-center space-x-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Process Cards Grid --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">
            Daftar Process ({{ count($processes) }})
        </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($processes as $process)
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-yazaki-red flex items-center justify-center font-bold text-xs shrink-0">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ $process['status'] }}
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-2">
                        {{ $process['name'] }}
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">
                        {{ $process['desc'] }}
                    </p>
                </div>

                <a href="{{ url('audit/process/' . $process['id'] . '/form') }}" 
                   class="w-full inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-colors shadow-sm">
                    <span>Proceed to Audit</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
