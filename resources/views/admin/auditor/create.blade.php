@extends('audit.layout')

@section('title', 'Tambah Akun Auditor - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12 max-w-3xl">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ route('admin.auditor.index') }}" class="hover:text-gray-700">Kelola Auditor</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Tambah</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Akun Pengguna Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Buat akun pengguna baru untuk Admin QA, Auditor, atau Instruktur</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.auditor.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- NIK --}}
            <div>
                <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">
                    NIK / Kode Pengguna <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="nik" 
                       id="nik" 
                       value="{{ old('nik') }}"
                       placeholder="Contoh: 12345 atau TWI"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('nik') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                @error('nik')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       placeholder="Contoh: Budi Santoso"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('name') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                    Password <span class="text-rose-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       placeholder="Minimal 6 karakter"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('password') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid Role & Tipe --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">
                        Role Akses <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" 
                            id="role" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border @error('role') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                        <option value="auditor" {{ old('role') === 'auditor' ? 'selected' : '' }}>Auditor (Standard User)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin QA (Super Admin)</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Auditor --}}
                <div>
                    <label for="tipe_auditor" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tipe Auditor <span class="text-rose-500">*</span>
                    </label>
                    <select name="tipe_auditor" 
                            id="tipe_auditor" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border @error('tipe_auditor') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all bg-white">
                        <option value="auditor" {{ old('tipe_auditor') === 'auditor' ? 'selected' : '' }}>Auditor</option>
                        <option value="instruktur" {{ old('tipe_auditor') === 'instruktur' ? 'selected' : '' }}>Instruktur</option>
                    </select>
                    @error('tipe_auditor')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Action Buttons --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.auditor.index') }}" 
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-yazaki-red hover:bg-red-700 transition-colors shadow-sm">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
