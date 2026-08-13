@extends('audit.layout')

@section('title', 'Edit Jenis Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12 max-w-3xl">
    {{-- Page Header --}}
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a>
            <span>/</span>
            <a href="{{ route('admin.jenis-audit.index') }}" class="hover:text-gray-700">Jenis Audit</a>
            <span>/</span>
            <span class="font-semibold text-yazaki-red">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Jenis Audit</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui data jenis audit {{ $type->nama }}</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.jenis-audit.update', $type->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">
                    Nama Jenis Audit <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="nama" 
                       id="nama" 
                       value="{{ old('nama', $type->nama) }}"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('nama') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                @error('nama')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">
                    Slug Identifikasi <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="slug" 
                       id="slug" 
                       value="{{ old('slug', $type->slug) }}"
                       required
                       class="w-full px-4 py-2.5 rounded-xl border font-mono @error('slug') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">
                <p class="text-xs text-gray-500 mt-1">Gunakan huruf kecil, angka, strip (-), atau underscore (_). Tanpa spasi.</p>
                @error('slug')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4" 
                          class="w-full px-4 py-2.5 rounded-xl border @error('deskripsi') border-rose-400 bg-rose-50/30 @else border-gray-300 @enderror text-sm focus:ring-2 focus:ring-yazaki-red focus:border-yazaki-red outline-none transition-all">{{ old('deskripsi', $type->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Action Buttons --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.jenis-audit.index') }}" 
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
@endsection
