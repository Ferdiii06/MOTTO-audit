@extends('audit.layout')

@section('title', 'Kelola Akun Auditor - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
                <span>/</span>
                <span class="font-semibold text-yazaki-red">Kelola Auditor</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Akun Auditor & User</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar seluruh akun pengguna sistem audit dan hak aksesnya</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.auditor.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-yazaki-red text-white text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Tambah Akun Baru</span>
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
                        <th class="py-3.5 px-6">NIK</th>
                        <th class="py-3.5 px-6">Nama Lengkap</th>
                        <th class="py-3.5 px-6 text-center">Role Akses</th>
                        <th class="py-3.5 px-6 text-center">Tipe Auditor</th>
                        <th class="py-3.5 px-6 text-right w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 font-mono text-xs font-semibold text-gray-900">{{ $u->nik }}</td>
                            <td class="py-4 px-6 font-medium text-gray-900">
                                {{ $u->name }}
                                @if((int) $u->id === (int) session('audit_user_id'))
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Anda
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        Admin QA
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        Auditor
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($u->tipe_auditor === 'instruktur')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Instruktur
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        Auditor
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.auditor.edit', $u->id) }}" 
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-colors">
                                        Edit
                                    </a>
                                    @if((int) $u->id !== (int) session('audit_user_id'))
                                        <form action="{{ route('admin.auditor.destroy', $u->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-400 bg-gray-50 border border-gray-100 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                            Hapus
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
