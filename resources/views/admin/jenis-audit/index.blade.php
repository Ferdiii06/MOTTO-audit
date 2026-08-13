@extends('audit.layout')

@section('title', 'Manajemen Jenis Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
                <span>/</span>
                <span class="font-semibold text-yazaki-red">Jenis Audit</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Jenis Audit</h1>
            <p class="text-sm text-gray-500 mt-1">Pengelolaan jenis-jenis audit dinamis sistem MOTTO-audit</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.jenis-audit.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-yazaki-red text-white text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Jenis Audit</span>
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

    {{-- Data Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6 w-16 text-center">No</th>
                        <th class="py-3.5 px-6">Nama Jenis Audit</th>
                        <th class="py-3.5 px-6">Slug</th>
                        <th class="py-3.5 px-6">Deskripsi</th>
                        <th class="py-3.5 px-6 text-center">Jumlah Area</th>
                        <th class="py-3.5 px-6 text-right w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($types as $index => $type)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-semibold text-gray-900">{{ $type->nama }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $type->slug }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-600 text-xs max-w-md">
                                {{ $type->deskripsi ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $type->areas_count }} Area
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.jenis-audit.edit', $type->id) }}" 
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.jenis-audit.destroy', $type->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis audit ini?')">
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
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                                Belum ada data jenis audit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
