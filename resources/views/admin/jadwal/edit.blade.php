@extends('audit.layout')

@section('title', 'Edit Jadwal Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12 max-w-3xl">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ route('admin.jadwal.index') }}" class="hover:text-gray-700">Jadwal Audit</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Jadwal Audit</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui alokasi jadwal audit untuk {{ optional($schedule->auditor)->name }}</p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm shadow-sm">
            <div class="font-semibold mb-1 flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Gagal Perbarui Jadwal:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.jadwal.update', $schedule->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Auditor --}}
            <div>
                <label for="audit_user_id" class="block text-sm font-semibold text-gray-700 mb-1">
                    Pilih Auditor / Instruktur <span class="text-rose-500">*</span>
                </label>
                <select name="audit_user_id" 
                        id="audit_user_id" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                    @foreach($auditors as $auditor)
                        <option value="{{ $auditor->id }}" {{ old('audit_user_id', $schedule->audit_user_id) == $auditor->id ? 'selected' : '' }}>
                            {{ $auditor->name }} (NIK: {{ $auditor->nik }} - Tipe: {{ ucfirst($auditor->tipe_auditor) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Target Type --}}
            @php
                $defaultTargetType = $schedule->audit_process_id ? 'process' : 'area';
            @endphp
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Target Penjadwalan <span class="text-rose-500">*</span>
                </label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" 
                               name="target_type" 
                               value="process" 
                               {{ old('target_type', $defaultTargetType) === 'process' ? 'checked' : '' }}
                               onchange="toggleTargetFields()"
                               class="w-4 h-4 text-yazaki-red focus:ring-yazaki-red border-gray-300">
                        <span class="ml-2 text-sm font-medium text-gray-800">Proses Audit Spesifik</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" 
                               name="target_type" 
                               value="area" 
                               {{ old('target_type', $defaultTargetType) === 'area' ? 'checked' : '' }}
                               onchange="toggleTargetFields()"
                               class="w-4 h-4 text-yazaki-red focus:ring-yazaki-red border-gray-300">
                        <span class="ml-2 text-sm font-medium text-gray-800">Seluruh Area Audit</span>
                    </label>
                </div>
            </div>

            {{-- Target Process --}}
            <div id="field_process">
                <label for="audit_process_id" class="block text-sm font-semibold text-gray-700 mb-1">
                    Pilih Proses Audit <span class="text-rose-500">*</span>
                </label>
                <select name="audit_process_id" 
                        id="audit_process_id" 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                    <option value="">-- Pilih Proses Audit --</option>
                    @foreach($processes as $process)
                        <option value="{{ $process->id }}" {{ old('audit_process_id', $schedule->audit_process_id) == $process->id ? 'selected' : '' }}>
                            {{ $process->name }} (Area: {{ optional($process->area)->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Target Area --}}
            <div id="field_area" class="hidden">
                <label for="audit_area_id" class="block text-sm font-semibold text-gray-700 mb-1">
                    Pilih Area Audit <span class="text-rose-500">*</span>
                </label>
                <select name="audit_area_id" 
                        id="audit_area_id" 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                    <option value="">-- Pilih Area Audit --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('audit_area_id', $schedule->audit_area_id) == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dates Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tanggal Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal_mulai" 
                           id="tanggal_mulai" 
                           value="{{ old('tanggal_mulai', $schedule->tanggal_mulai ? $schedule->tanggal_mulai->format('Y-m-d') : '') }}"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tanggal Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal_selesai" 
                           id="tanggal_selesai" 
                           value="{{ old('tanggal_selesai', $schedule->tanggal_selesai ? $schedule->tanggal_selesai->format('Y-m-d') : '') }}"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                </div>
            </div>

            {{-- Form Action Buttons --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.jadwal.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-yazaki-red hover:bg-red-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTargetFields() {
        const isProcess = document.querySelector('input[name="target_type"][value="process"]').checked;
        const fieldProcess = document.getElementById('field_process');
        const fieldArea = document.getElementById('field_area');

        if (isProcess) {
            fieldProcess.classList.remove('hidden');
            fieldArea.classList.add('hidden');
        } else {
            fieldProcess.classList.add('hidden');
            fieldArea.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', toggleTargetFields);
</script>
@endsection
