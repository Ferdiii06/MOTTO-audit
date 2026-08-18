@extends('audit.layout')

@section('title', 'Pilih Area - 5S Standard - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">5S Standard</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pilih Area Audit 5S Standard</h1>
        <p class="text-sm text-gray-500 mt-1">Pilih salah satu dari 14 area di bawah ini untuk melanjutkan ke pemilihan proses audit</p>
    </div>

    {{-- Grid of 14 Areas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($areas as $slug => $area)
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    {{-- Top Row: Icon & Badge --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-lg bg-red-50 text-yazaki-red flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area['icon'] ?? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' }}"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-yazaki-red border border-red-100">
                            {{ $area['process_count'] }} Process
                        </span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="text-base font-bold text-gray-900 mb-1.5 line-clamp-1">
                        {{ $area['name'] }}
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">
                        {{ $area['desc'] }}
                    </p>
                </div>

                {{-- Proceed Button inside the card --}}
                <a href="{{ url('audit/5s-standard/' . $slug) }}" 
                   class="w-full inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-colors shadow-sm">
                    <span>Proceed to Process</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection