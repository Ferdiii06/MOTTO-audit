@extends('audit.layout')

@section('title', 'Form Audit - ' . $process->name . ' - Moto Audit System')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">
    {{-- Header & Breadcrumb --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="/audit/dashboard" class="hover:text-gray-700">Home</a>
            <span>/</span>
            @if($area)
                @if($area->category === 'change_point')
                    <a href="/audit/change-point-management" class="hover:text-yazaki-red">Change Point</a>
                @elseif($area->category === 'license_system')
                    <a href="/audit/license-system" class="hover:text-yazaki-red">License System</a>
                @else
                    <a href="/audit/5s-standard" class="hover:text-yazaki-red">5S Standard</a>
                    <span>/</span>
                    <a href="/audit/5s-standard/{{ $area->slug }}" class="hover:text-yazaki-red">{{ $area->name }}</a>
                @endif
            @endif
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Form Audit</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Form Audit: <span class="text-yazaki-red">{{ $process->name }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $area ? $area->name : 'Area Audit' }} &bull; Lakukan penilaian kondisi operasional & keselamatan
                </p>
            </div>

            <a href="{{ url()->previous() }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 px-3.5 py-2 rounded-lg transition-colors shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Batal / Kembali</span>
            </a>
        </div>
    </div>

    {{-- Validation Error Alert --}}
    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 space-y-1 shadow-sm">
            <div class="font-bold flex items-center space-x-1.5 text-sm text-red-900">
                <svg class="w-4 h-4 text-yazaki-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Terdapat kesalahan pada inputan form:</span>
            </div>
            <ul class="list-disc list-inside pl-1 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Audit Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden divide-y divide-gray-100">
        {{-- Metadata Bar (Auditor & Tanggal) --}}
        <div class="bg-gray-50/80 px-6 py-4 flex flex-wrap items-center justify-between gap-4 text-xs">
            <div class="flex items-center space-x-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Auditor: <strong class="text-gray-900 font-bold">{{ $auditorName }}</strong></span>
            </div>
            <div class="flex items-center space-x-2 text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Tanggal Audit: <strong class="text-gray-900 font-bold">{{ $auditDate }}</strong></span>
            </div>
        </div>

        {{-- Checkpoint & Audit Judgement Guidance --}}
        <div class="p-6 space-y-5">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400">Panduan Standar Audit Process</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Checkpoint Box --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-1.5">
                    <div class="flex items-center space-x-2 text-xs font-bold text-slate-700 uppercase tracking-wide">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Checkpoint (Item Yang Diperiksa)</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed pt-1">
                        {{ $process->checkpoint ?? "Checkpoint untuk {$process->name} - menyusul" }}
                    </p>
                </div>

                {{-- Kriteria Audit Judgement Box --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-1.5">
                    <div class="flex items-center space-x-2 text-xs font-bold text-slate-700 uppercase tracking-wide">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Kriteria Audit Judgement (Standar OK / NG)</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed pt-1">
                        {{ $process->kriteria_judgement ?? "Kriteria OK/NG untuk {$process->name} - menyusul" }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <form action="/audit/process/{{ $process->id }}/submit" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-6 space-y-6">
            @csrf

            <input type="hidden" name="judgement" id="judgement_input" value="{{ old('judgement') }}">

            {{-- Penilaian OK / NG Toggle Buttons --}}
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500">
                    Penilaian Judgement <span class="text-red-500">*</span>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    {{-- OK Button --}}
                    <button type="button" 
                            id="btn_ok" 
                            onclick="setJudgement('OK')"
                            class="flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center group bg-white border-gray-200 text-gray-600 hover:border-emerald-500 hover:bg-emerald-50/50">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-lg">OK (LULUS)</span>
                        <span class="text-xs font-normal text-gray-400 mt-0.5">Sesuai dengan standar kriteria</span>
                    </button>

                    {{-- NG Button --}}
                    <button type="button" 
                            id="btn_ng" 
                            onclick="setJudgement('NG')"
                            class="flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center group bg-white border-gray-200 text-gray-600 hover:border-red-500 hover:bg-red-50/50">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <span class="text-lg">NG (TEMUAN)</span>
                        <span class="text-xs font-normal text-gray-400 mt-0.5">Tidak sesuai, perlukah bukti foto</span>
                    </button>
                </div>
            </div>

            {{-- NG Evidence Area (Show/Hide smoothly via JS) --}}
            <div id="ng_section" class="hidden p-5 bg-red-50/60 border border-red-200 rounded-xl space-y-4 transition-all">
                <div class="flex items-center space-x-2 text-xs font-bold text-red-800 uppercase tracking-wide">
                    <svg class="w-4 h-4 text-yazaki-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Bukti Temuan Kategori NG</span>
                </div>

                {{-- Upload Foto NG --}}
                <div class="space-y-1.5">
                    <label for="foto_ng" class="block text-xs font-bold text-gray-700">
                        Upload Foto Temuan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           name="foto_ng" 
                           id="foto_ng" 
                           accept="image/*"
                           onchange="previewImage(event)"
                           class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-yazaki-red file:text-white hover:file:bg-yazaki-red-dark cursor-pointer border border-gray-200 rounded-lg bg-white p-1">
                    <p class="text-[11px] text-gray-400">Format: JPG, PNG, WEBP. Maksimal ukuran file: 2 MB.</p>
                    
                    {{-- Image Preview Container --}}
                    <div id="preview_container" class="hidden mt-3">
                        <p class="text-xs font-semibold text-gray-600 mb-1">Pratinjau Foto Temuan:</p>
                        <img id="image_preview" src="#" alt="Preview" class="max-h-48 rounded-lg border border-gray-300 shadow-sm object-cover">
                    </div>
                </div>

                {{-- Catatan Temuan NG --}}
                <div class="space-y-1.5">
                    <label for="catatan" class="block text-xs font-bold text-gray-700">
                        Catatan & Keterangan Temuan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan" 
                              id="catatan" 
                              rows="3" 
                              placeholder="Tuliskan rincian temuan NG dan lokasi spesifik..." 
                              class="w-full text-xs border border-gray-200 rounded-lg p-3 focus:outline-none focus:ring-1 focus:ring-yazaki-red focus:border-yazaki-red bg-white">{{ old('catatan') }}</textarea>
                </div>
            </div>

            {{-- Submit Action Bar --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ url()->previous() }}" 
                   class="px-4 py-2.5 rounded-lg text-xs font-semibold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        id="btn_submit" 
                        disabled
                        class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-gray-300 transition-all cursor-not-allowed shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Hasil Audit</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function setJudgement(val) {
    document.getElementById('judgement_input').value = val;

    const btnOk = document.getElementById('btn_ok');
    const btnNg = document.getElementById('btn_ng');
    const ngSection = document.getElementById('ng_section');
    const btnSubmit = document.getElementById('btn_submit');
    const fotoInput = document.getElementById('foto_ng');
    const catatanInput = document.getElementById('catatan');

    if (val === 'OK') {
        // Style OK Active
        btnOk.className = 'flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center bg-emerald-50 border-emerald-500 text-emerald-700 shadow-sm';
        btnNg.className = 'flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center group bg-white border-gray-200 text-gray-400 opacity-60 hover:opacity-100';

        // Hide NG area
        ngSection.classList.add('hidden');
        fotoInput.required = false;
        catatanInput.required = false;

        // Enable Submit
        enableSubmit();
    } else if (val === 'NG') {
        // Style NG Active
        btnNg.className = 'flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center bg-red-50 border-yazaki-red text-yazaki-red shadow-sm';
        btnOk.className = 'flex flex-col items-center justify-center py-5 px-4 rounded-xl border-2 transition-all cursor-pointer font-bold text-center group bg-white border-gray-200 text-gray-400 opacity-60 hover:opacity-100';

        // Show NG area
        ngSection.classList.remove('hidden');
        fotoInput.required = true;
        catatanInput.required = true;

        checkNgValidity();
    }
}

function checkNgValidity() {
    const val = document.getElementById('judgement_input').value;
    if (val !== 'NG') return;

    const fotoInput = document.getElementById('foto_ng');
    const catatanInput = document.getElementById('catatan');

    if ((fotoInput.files && fotoInput.files.length > 0) && catatanInput.value.trim() !== '') {
        enableSubmit();
    } else {
        disableSubmit();
    }
}

function enableSubmit() {
    const btnSubmit = document.getElementById('btn_submit');
    btnSubmit.disabled = false;
    btnSubmit.className = 'inline-flex items-center space-x-2 px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-yazaki-red hover:bg-yazaki-red-dark transition-all shadow-md cursor-pointer';
}

function disableSubmit() {
    const btnSubmit = document.getElementById('btn_submit');
    btnSubmit.disabled = true;
    btnSubmit.className = 'inline-flex items-center space-x-2 px-6 py-2.5 rounded-lg text-xs font-bold text-white bg-gray-300 transition-all cursor-not-allowed shadow-sm';
}

function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('preview_container');
    const previewImage = document.getElementById('image_preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.classList.add('hidden');
    }

    checkNgValidity();
}

document.getElementById('catatan').addEventListener('input', checkNgValidity);

// Restore old input state if validation returned errors
document.addEventListener('DOMContentLoaded', function() {
    const initialVal = document.getElementById('judgement_input').value;
    if (initialVal) {
        setJudgement(initialVal);
    }
});
</script>
@endsection
