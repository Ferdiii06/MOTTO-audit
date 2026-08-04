@extends('audit.layout')

@section('title', 'Pilih Area - 5S Standard - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <span>Home</span>
                <span>/</span>
                <span class="font-semibold text-yazaki-red">5S Standard</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pilih Area Audit 5S Standard</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih salah satu dari 14 area di bawah ini untuk melanjutkan ke pemilihan proses audit</p>
        </div>

        {{-- Action Bar / Proceed Button --}}
        <div class="shrink-0 flex items-center space-x-3">
            <span id="selected-area-label" class="text-xs font-semibold text-gray-500 hidden md:inline-block">
                Pilih area untuk melanjutkan
            </span>
            <a id="btn-proceed" href="#" 
               class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-gray-300 transition-all cursor-not-allowed pointer-events-none shadow-sm"
               aria-disabled="true">
                <span>Proceed</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Grid of 14 Areas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($areas as $slug => $area)
            <div data-area-card 
                 data-slug="{{ $slug }}"
                 data-name="{{ $area['name'] }}"
                 tabindex="0"
                 role="button"
                 aria-pressed="false"
                 class="group relative bg-white rounded-xl border border-gray-200 p-5 cursor-pointer hover:border-yazaki-red/50 hover:shadow-md transition-all duration-200 flex flex-col justify-between select-none">
                
                {{-- Top Row: Icon & Badge --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div data-area-icon class="w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center transition-colors group-hover:bg-red-50 group-hover:text-yazaki-red">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area['icon'] }}"/>
                            </svg>
                        </div>
                        <span data-selected-indicator class="hidden text-yazaki-red bg-yazaki-red-light p-1 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="text-base font-bold text-gray-900 group-hover:text-yazaki-red transition-colors line-clamp-1">
                        {{ $area['name'] }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                        {{ $area['desc'] }}
                    </p>
                </div>

                {{-- Card Footer --}}
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $area['process_count'] }} Process</span>
                    <span class="group-hover:text-yazaki-red font-medium transition-colors flex items-center space-x-1">
                        <span>Pilih</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection