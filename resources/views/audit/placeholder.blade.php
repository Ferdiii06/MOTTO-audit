@extends('audit.layout')

@section('title', 'Scoring Audit - Moto Audit System')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">
    <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-6 shadow-sm border border-amber-100">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
    </div>

    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
        Halaman scoring audit belum dibuat
    </h1>
    
    <p class="text-sm text-gray-500 max-w-md mb-8 leading-relaxed">
        Fitur penilaian dan lembar kerja audit sedang dalam tahap pengembangan berikutnya. Silakan kembali ke menu utama.
    </p>

    <div class="flex items-center space-x-3">
        <a href="{{ url('audit/dashboard') }}" 
           class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>
@endsection
