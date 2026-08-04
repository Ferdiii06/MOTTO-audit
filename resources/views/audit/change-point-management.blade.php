@extends('audit.layout')

@section('title', 'Change Point Management - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="/audit/dashboard" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Change Point Management</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Change Point Management Audit</h1>
        <p class="text-sm text-gray-500 mt-1">Audit kesiapan & verifikasi perubahan pada lini produksi (Man, Machine, Material, Method)</p>
    </div>

    {{-- 2 Process Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
        @foreach($processes as $process)
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-red-50 text-yazaki-red flex items-center justify-center font-bold text-base">
                            0{{ $loop->iteration }}
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ $process['status'] }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        {{ $process['name'] }}
                    </h3>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6">
                        {{ $process['desc'] }}
                    </p>
                </div>

                <a href="/audit/placeholder" 
                   class="w-full inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-colors shadow-sm">
                    <span>Proceed to Audit</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection