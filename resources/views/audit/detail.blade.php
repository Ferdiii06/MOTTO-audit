@extends('audit.layout')

@section('title', 'Detail Riwayat Audit #' . $record->id . ' - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Breadcrumb Navigation --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ route('riwayat') }}" class="hover:text-gray-700">Riwayat Audit</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Detail Audit #{{ $record->id }}</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Detail Submission Audit</h1>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                    <span>Audit ID: #{{ $record->id }}</span>
                    <span>•</span>
                    <span>Tanggal: {{ is_string($record->audit_date) ? $record->audit_date : $record->audit_date->format('d F Y') }}</span>
                    <span>•</span>
                    <span class="inline-flex items-center text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded font-medium">
                        Status: {{ $record->status ?? 'Selesai' }}
                    </span>
                </p>
            </div>

            {{-- PROMINENT STATUS BADGE (OK / NG) --}}
            <div class="flex items-center space-x-3">
                @if(($record->judgement ?? ($record->score >= 90 ? 'OK' : 'NG')) === 'NG')
                    <div class="px-5 py-2.5 rounded-xl bg-red-800 text-white shadow-md flex items-center space-x-3 border border-red-900">
                        <div class="w-4 h-4 rounded-full bg-white flex items-center justify-center shrink-0">
                            <span class="text-red-800 font-extrabold text-xs">✕</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-red-200 block leading-tight">HASIL AUDIT</span>
                            <span class="text-xl font-extrabold tracking-wide">TEMUAN (NG)</span>
                        </div>
                    </div>
                @else
                    <div class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white shadow-md flex items-center space-x-3 border border-emerald-700">
                        <div class="w-4 h-4 rounded-full bg-white flex items-center justify-center shrink-0">
                            <span class="text-emerald-600 font-extrabold text-xs">✓</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-100 block leading-tight">HASIL AUDIT</span>
                            <span class="text-xl font-extrabold tracking-wide">LULUS (OK)</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Info Cards Grid (Header Submission Metadata) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Area Audit --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">AREA AUDIT</span>
            <div class="mt-2">
                <p class="text-lg font-bold text-gray-900 leading-snug">{{ $record->area_name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $record->area ? $record->area->category : 'Standard 5S' }}</p>
            </div>
        </div>

        {{-- Card 2: Process Audit --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">PROSES AUDIT</span>
            <div class="mt-2">
                <p class="text-lg font-bold text-yazaki-red font-mono leading-snug">
                    {{ $record->process ? $record->process->name : '-' }}
                </p>
                <p class="text-xs text-gray-500 mt-0.5">Order / No Urut Process</p>
            </div>
        </div>

        {{-- Card 3: Auditor QA --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">AUDITOR / PETUGAS</span>
            <div class="mt-2">
                <p class="text-lg font-bold text-gray-900 leading-snug">{{ $record->auditor_name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Departemen QA / Auditor</p>
            </div>
        </div>

        {{-- Card 4: Tanggal Submission --}}
        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">WAKTU SUBMISSION</span>
            <div class="mt-2">
                <p class="text-base font-bold text-gray-900 leading-snug">
                    {{ $record->created_at ? $record->created_at->format('d F Y, H:i') : (is_string($record->audit_date) ? $record->audit_date : $record->audit_date->format('d F Y')) }}
                </p>
                <p class="text-xs text-gray-500 mt-0.5">Waktu Lengkap Audit</p>
            </div>
        </div>
    </div>

    {{-- Detail Standard & Criteria Guidance --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-yazaki-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Panduan Standar & Kriteria Pemeriksaan</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Checkpoint --}}
            <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                <span class="text-xs font-bold text-blue-800 uppercase tracking-wider block mb-2">📌 CHECKPOINT PEMERIKSAAN</span>
                <p class="text-sm font-medium text-gray-800 whitespace-pre-line leading-relaxed">
                    {{ $record->process && $record->process->checkpoint ? $record->process->checkpoint : 'Pemeriksaan standar operasional sesuai panduan 5S / Change Point / License System.' }}
                </p>
            </div>

            {{-- Kriteria Judgement --}}
            <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100">
                <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block mb-2">⚡ KRITERIA JUDGEMENT OK / NG</span>
                <p class="text-sm font-medium text-gray-800 whitespace-pre-line leading-relaxed">
                    {{ $record->process && $record->process->kriteria_judgement ? $record->process->kriteria_judgement : 'Kriteria standar OK jika kondisi terpenuhi. Kriteria NG jika ditemukan deviasi.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Detail Result & Findings --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yazaki-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Hasil Observasi & Catatan Temuan</span>
            </span>
        </h2>

        @if(($record->judgement ?? ($record->score >= 90 ? 'OK' : 'NG')) === 'NG')
            {{-- NG STATUS CONTAINER --}}
            <div class="space-y-6">
                {{-- Catatan Temuan --}}
                <div class="bg-red-50/60 rounded-xl p-5 border border-red-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-red-800 uppercase tracking-wide flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Catatan Temuan Auditor
                        </span>
                    </div>
                    <p class="text-sm text-red-950 font-medium whitespace-pre-line leading-relaxed">
                        {{ $record->catatan ?? 'Tidak ada catatan tertulis.' }}
                    </p>
                </div>

                {{-- Bukti Foto Temuan --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bukti Foto Temuan (NG)
                    </h3>

                    @if($record->foto_ng)
                        <div class="relative group max-w-md bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $record->foto_ng) }}" 
                                 alt="Foto Temuan NG" 
                                 class="w-full h-64 object-cover cursor-pointer transition-transform duration-200 group-hover:scale-105"
                                 onclick="openImageModal(this.src)">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs font-semibold bg-black/60 px-3 py-1.5 rounded-full">Klik untuk memperbesar</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-8 text-center max-w-md">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-500 font-medium">Foto bukti temuan tidak diunggah atau menggunakan data lama.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- OK STATUS CONTAINER --}}
            <div class="bg-emerald-50/70 rounded-xl p-5 border border-emerald-200 flex items-start space-x-3">
                <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                    ✓
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900">Pemeriksaan Sesuai Standar (OK)</h3>
                    <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                        {{ $record->catatan ? $record->catatan : 'Seluruh kriteria pemeriksaan pada proses ini telah memenuhi standar keselamatan dan kualitas operasional pabrik.' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Navigation Actions --}}
    <div class="flex items-center justify-between pt-4">
        <a href="{{ route('riwayat') }}" 
           class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Riwayat Audit</span>
        </a>
    </div>
</div>

{{-- Image Zoom Modal --}}
<div id="imageModal" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 cursor-pointer" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-[90vh] overflow-hidden rounded-xl bg-black" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="Zoom Foto Temuan" class="max-w-full max-h-[85vh] object-contain mx-auto">
        <button type="button" onclick="closeImageModal()" class="absolute top-3 right-3 text-white bg-black/60 hover:bg-black/90 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">
            ✕
        </button>
    </div>
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}
</script>
@endsection
