@extends('audit.layout')

@section('title', 'Manajemen Jadwal Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
                <span>/</span>
                <span class="font-semibold text-yazaki-red">Jadwal Audit</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Jadwal Audit</h1>
            <p class="text-sm text-gray-500 mt-1">Pengaturan alokasi dan penjadwalan tugas auditor & instruktur</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.jadwal.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-yazaki-red text-white text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Buat Jadwal Baru</span>
            </a>
        </div>
    </div>

    {{-- Notification Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-2 text-sm shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-center space-x-2 text-sm shadow-sm">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Bulk Assign Summary Block --}}
    @if(session()->has('bulk_failed') && is_array(session('bulk_failed')) && count(session('bulk_failed')) > 0)
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-xl p-4 space-y-2 text-sm shadow-sm">
            <div class="font-bold flex items-center space-x-2 text-amber-800">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Detail Item Gagal / Bentrok ({{ count(session('bulk_failed')) }} item):</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-amber-800">
                @foreach(session('bulk_failed') as $fail)
                    <li>
                        <strong class="font-semibold">{{ $fail['name'] }}:</strong> {{ $fail['reason'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-3 w-full sm:w-auto">
            <label for="auditor_id" class="text-xs font-semibold text-gray-600 shrink-0">Filter Auditor:</label>
            <select name="auditor_id" 
                    id="auditor_id" 
                    onchange="this.form.submit()"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-medium text-gray-800 bg-white focus:ring-2 focus:ring-yazaki-red outline-none transition-all">
                <option value="">Semua Auditor</option>
                @foreach($auditors as $auditor)
                    <option value="{{ $auditor->id }}" {{ request('auditor_id') == $auditor->id ? 'selected' : '' }}>
                        {{ $auditor->name }} (NIK: {{ $auditor->nik }})
                    </option>
                @endforeach
            </select>
        </form>

        @if(request()->filled('auditor_id'))
            <a href="{{ route('admin.jadwal.index') }}" 
               class="inline-flex items-center space-x-1 text-xs font-semibold text-yazaki-red hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Reset Filter</span>
            </a>
        @endif
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6 w-16 text-center">No</th>
                        <th class="py-3.5 px-6">Auditor</th>
                        <th class="py-3.5 px-6">Target Audit</th>
                        <th class="py-3.5 px-6 text-center">Tipe Target</th>
                        <th class="py-3.5 px-6 text-center">Periode Jadwal</th>
                        <th class="py-3.5 px-6">Dibuat Oleh</th>
                        <th class="py-3.5 px-6 text-right w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($schedules as $index => $s)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-900">
                                {{ optional($s->auditor)->name ?? '-' }}
                                <span class="block text-xs font-normal text-gray-500">
                                    NIK: {{ optional($s->auditor)->nik ?? '-' }}
                                    @if(optional($s->auditor)->tipe_auditor === 'instruktur')
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Instruktur
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-900">
                                @if($s->audit_process_id)
                                    <div>{{ optional($s->process)->name }}</div>
                                    <span class="text-xs text-gray-500 font-normal">
                                        Area: {{ optional(optional($s->process)->area)->name ?? '-' }}
                                    </span>
                                @else
                                    <div>{{ optional($s->area)->name }}</div>
                                    <span class="text-xs text-gray-500 font-normal">Seluruh area</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($s->audit_process_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        Proses
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        Area
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-xs text-gray-700">
                                <div class="font-semibold">{{ $s->tanggal_mulai ? $s->tanggal_mulai->format('d M Y') : '-' }}</div>
                                <div class="text-gray-400 text-[10px]">s/d</div>
                                <div class="font-semibold">{{ $s->tanggal_selesai ? $s->tanggal_selesai->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-600">
                                {{ optional($s->creator)->name ?? 'System' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.jadwal.edit', $s->id) }}" 
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $s->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 text-sm">
                                Belum ada jadwal audit yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
