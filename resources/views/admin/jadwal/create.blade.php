@extends('audit.layout')

@section('title', 'Buat Jadwal Audit Baru - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12 max-w-4xl">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ route('admin.jadwal.index') }}" class="hover:text-gray-700">Jadwal Audit</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Buat (Bulk)</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Jadwal Audit Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Alokasikan tugas audit ke auditor atau instruktur untuk satu atau beberapa proses/area sekaligus</p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm shadow-sm">
            <div class="font-semibold mb-1 flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Gagal Menyimpan Jadwal:</span>
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
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Auditor --}}
            <div>
                <label for="audit_user_id" class="block text-sm font-semibold text-gray-700 mb-1">
                    Pilih Auditor / Instruktur <span class="text-rose-500">*</span>
                </label>
                <select name="audit_user_id" 
                        id="audit_user_id" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border @error('audit_user_id') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                    <option value="">-- Pilih Auditor --</option>
                    @foreach($auditors as $auditor)
                        <option value="{{ $auditor->id }}" {{ old('audit_user_id') == $auditor->id ? 'selected' : '' }}>
                            {{ $auditor->name }} (NIK: {{ $auditor->nik }} - Tipe: {{ ucfirst($auditor->tipe_auditor) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Target Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Target Penjadwalan <span class="text-rose-500">*</span>
                </label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" 
                               name="target_type" 
                               value="process" 
                               {{ old('target_type', 'process') === 'process' ? 'checked' : '' }}
                               onchange="toggleTargetFields()"
                               class="w-4 h-4 text-yazaki-red focus:ring-yazaki-red border-gray-300">
                        <span class="ml-2 text-sm font-medium text-gray-800">Proses Audit Spesifik</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" 
                               name="target_type" 
                               value="area" 
                               {{ old('target_type') === 'area' ? 'checked' : '' }}
                               onchange="toggleTargetFields()"
                               class="w-4 h-4 text-yazaki-red focus:ring-yazaki-red border-gray-300">
                        <span class="ml-2 text-sm font-medium text-gray-800">Seluruh Area Audit</span>
                    </label>
                </div>
            </div>

            {{-- Selected Counter Header --}}
            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-700">
                <span>Pilih Item Target Audit</span>
                <span id="selected_count_badge" class="px-2.5 py-1 rounded-full bg-yazaki-red text-white font-bold text-xs shadow-sm">
                    0 item dipilih
                </span>
            </div>

            {{-- Target Process (Multi-Select Checkboxes) --}}
            <div id="field_process" class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <input type="text" 
                           id="search_process_input" 
                           oninput="filterProcesses()" 
                           placeholder="Cari nama proses audit..." 
                           class="w-full sm:w-80 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:ring-2 focus:ring-yazaki-red outline-none">
                    <button type="button" 
                            onclick="toggleAllProcesses(true)" 
                            class="text-xs text-yazaki-red hover:underline font-semibold self-end sm:self-auto">
                        Pilih Semua Proses
                    </button>
                </div>

                <div class="border border-gray-200 rounded-xl max-h-96 overflow-y-auto divide-y divide-gray-100 p-2 bg-gray-50/50">
                    @foreach($areas as $area)
                        @php
                            $areaProcesses = $processesGrouped->get($area->id, collect());
                        @endphp
                        @if($areaProcesses->isNotEmpty())
                            <details open class="group bg-white rounded-lg border border-gray-200 my-1 overflow-hidden">
                                <summary class="flex items-center justify-between px-3 py-2 text-xs font-bold text-gray-800 bg-gray-100/70 hover:bg-gray-100 cursor-pointer select-none">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-500 transition-transform group-open:rotate-90">►</span>
                                        <span>Area {{ $area->name }} ({{ $areaProcesses->count() }} Proses)</span>
                                    </div>
                                    <label class="inline-flex items-center text-[11px] font-semibold text-yazaki-red hover:underline cursor-pointer" onclick="event.stopPropagation()">
                                        <input type="checkbox" 
                                               class="check-all-area rounded border-gray-300 text-yazaki-red focus:ring-yazaki-red mr-1" 
                                               onchange="toggleAreaProcesses(this, 'area-group-{{ $area->id }}')">
                                        <span>Pilih Semua di Area Ini</span>
                                    </label>
                                </summary>
                                <div id="area-group-{{ $area->id }}" class="p-3 grid grid-cols-1 md:grid-cols-2 gap-2 bg-white">
                                    @foreach($areaProcesses as $proc)
                                        <label class="process-item-label flex items-start space-x-2 text-xs p-1.5 rounded-md hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200" data-name="{{ strtolower($proc->name) }}">
                                            <input type="checkbox" 
                                                   name="audit_process_id[]" 
                                                   value="{{ $proc->id }}" 
                                                   {{ is_array(old('audit_process_id')) && in_array($proc->id, old('audit_process_id')) ? 'checked' : '' }}
                                                   onchange="updateSelectedCount()"
                                                   class="process-checkbox mt-0.5 rounded border-gray-300 text-yazaki-red focus:ring-yazaki-red shrink-0">
                                            <span class="text-gray-700 leading-tight">{{ $proc->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Target Area (Multi-Select Checkboxes) --}}
            <div id="field_area" class="hidden space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-medium">Pilih satu atau beberapa area audit:</span>
                    <button type="button" 
                            onclick="toggleAllAreas(true)" 
                            class="text-xs text-yazaki-red hover:underline font-semibold">
                        Pilih Semua Area
                    </button>
                </div>
                <div class="border border-gray-200 rounded-xl p-4 bg-white grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 max-h-80 overflow-y-auto">
                    @foreach($areas as $area)
                        <label class="flex items-center space-x-2 text-xs p-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" 
                                   name="audit_area_id[]" 
                                   value="{{ $area->id }}" 
                                   {{ is_array(old('audit_area_id')) && in_array($area->id, old('audit_area_id')) ? 'checked' : '' }}
                                   onchange="updateSelectedCount()"
                                   class="area-checkbox rounded border-gray-300 text-yazaki-red focus:ring-yazaki-red shrink-0">
                            <span class="font-medium text-gray-800">{{ $area->name }}</span>
                        </label>
                    @endforeach
                </div>
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
                           value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
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
                           value="{{ old('tanggal_selesai', date('Y-m-d')) }}"
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
                    Simpan Jadwal (bisa lebih dari satu)
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
        updateSelectedCount();
    }

    function toggleAreaProcesses(masterCheckbox, groupContainerId) {
        const container = document.getElementById(groupContainerId);
        if (!container) return;
        const checkboxes = container.querySelectorAll('.process-checkbox');
        checkboxes.forEach(cb => {
            if (cb.closest('.process-item-label').style.display !== 'none') {
                cb.checked = masterCheckbox.checked;
            }
        });
        updateSelectedCount();
    }

    function toggleAllProcesses(state) {
        document.querySelectorAll('.process-checkbox').forEach(cb => {
            if (cb.closest('.process-item-label').style.display !== 'none') {
                cb.checked = state;
            }
        });
        document.querySelectorAll('.check-all-area').forEach(cb => cb.checked = state);
        updateSelectedCount();
    }

    function toggleAllAreas(state) {
        document.querySelectorAll('.area-checkbox').forEach(cb => cb.checked = state);
        updateSelectedCount();
    }

    function filterProcesses() {
        const query = document.getElementById('search_process_input').value.toLowerCase().trim();
        const labels = document.querySelectorAll('.process-item-label');
        
        labels.forEach(label => {
            const name = label.getAttribute('data-name');
            if (!query || name.includes(query)) {
                label.style.display = 'flex';
            } else {
                label.style.display = 'none';
            }
        });

        document.querySelectorAll('details').forEach(details => {
            if (query) {
                const visibleItems = details.querySelectorAll('.process-item-label:not([style*="display: none"])');
                if (visibleItems.length > 0) {
                    details.style.display = 'block';
                    details.open = true;
                } else {
                    details.style.display = 'none';
                }
            } else {
                details.style.display = 'block';
            }
        });

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const isProcess = document.querySelector('input[name="target_type"][value="process"]').checked;
        let count = 0;

        if (isProcess) {
            count = document.querySelectorAll('.process-checkbox:checked').length;
        } else {
            count = document.querySelectorAll('.area-checkbox:checked').length;
        }

        const badge = document.getElementById('selected_count_badge');
        if (badge) {
            badge.textContent = count + ' item dipilih';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleTargetFields();
        updateSelectedCount();
    });
</script>
@endsection
