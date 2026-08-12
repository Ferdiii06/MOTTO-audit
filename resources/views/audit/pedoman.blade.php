@extends('audit.layout')

@section('title', 'Pedoman SOP Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
                <span>/</span>
                <span class="font-semibold text-yazaki-red">Pedoman SOP</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pedoman Standard Operating Procedure (SOP)</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar dokumen pedoman dan SOP audit per kategori/proses audit</p>
        </div>
        @if($isAdmin)
            <div class="shrink-0">
                <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Mode Super Admin (Akses Edit SOP)</span>
                </span>
            </div>
        @endif
    </div>

    {{-- Notification Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center justify-between text-sm shadow-sm">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm shadow-sm">
            <div class="font-semibold mb-1 flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Gagal Mengunggah PDF:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Areas Loop --}}
    <div class="space-y-8">
        @foreach($areas as $area)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- Area Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-yazaki-red flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area->icon_svg ?? 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' }}"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">{{ $area->name }}</h2>
                            <p class="text-xs text-gray-500">{{ $area->description ?? 'Daftar proses audit dalam area ini' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200/70 text-gray-700">
                        {{ $area->processes->count() }} Proses
                    </span>
                </div>

                {{-- Processes List --}}
                <div class="divide-y divide-gray-100">
                    @forelse($area->processes as $process)
                        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                            <div class="space-y-1 max-w-2xl">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $process->name }}</h3>
                                    @if($process->pedoman_path)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            SOP Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 text-gray-500">
                                            Belum Ada SOP
                                        </span>
                                    @endif
                                </div>
                                @if($process->description)
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $process->description }}</p>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0">
                                {{-- View SOP Button --}}
                                @if($process->pedoman_path)
                                    <a href="{{ Storage::url($process->pedoman_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-yazaki-red bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span>Lihat SOP</span>
                                    </a>
                                @endif

                                {{-- Super Admin Upload Form --}}
                                @if($isAdmin)
                                    <form action="{{ route('audit.pedoman.upload', $process->id) }}" 
                                          method="POST" 
                                          enctype="multipart/form-data" 
                                          class="flex items-center space-x-2">
                                        @csrf
                                        <label class="cursor-pointer inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <span>{{ $process->pedoman_path ? 'Ganti PDF' : 'Upload PDF' }}</span>
                                            <input type="file" name="pedoman_file" accept=".pdf" class="sr-only" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-500">
                            Belum ada proses audit pada area ini.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection