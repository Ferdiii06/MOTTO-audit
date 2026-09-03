@extends('audit.layout')

@section('title', 'Edit Jenis Audit - Moto Audit System')

@section('content')
<div class="space-y-6 pb-12">
    <div class="border-b border-gray-200 pb-5">
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
            <a href="{{ url('audit/dashboard') }}" class="hover:text-gray-700">Home</a><span>/</span>
            <a href="{{ route('admin.jenis-audit.index') }}" class="hover:text-gray-700">Jenis Audit</a><span>/</span>
            <span class="font-semibold text-yazaki-red">Edit</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Jenis Audit</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui {{ $type->nama }}, area, dan proses audit.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.jenis-audit.update', $type->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Jenis Audit *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $type->nama) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">
            </div>
            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">Slug Identifikasi *</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $type->slug) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 font-mono text-sm">
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">{{ old('deskripsi', $type->deskripsi) }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.jenis-audit.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-yazaki-red">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Area &amp; Proses</h2>
                <p class="text-sm text-gray-500">Accordion tertutup default. Area: {{ $type->areas->count() }}.</p>
            </div>
            <button type="button" onclick="document.getElementById('add-area-form').classList.toggle('hidden')" class="px-4 py-2 rounded-xl bg-yazaki-red text-white text-sm font-semibold transition-colors duration-150 hover:bg-red-700 active:bg-red-800 active:scale-95">+ Tambah Area</button>
        </div>

        <form id="add-area-form" action="{{ route('admin.area.store', $type->id) }}" method="POST" class="hidden bg-white border border-gray-200 rounded-xl p-5 space-y-3">
            @csrf
            <h3 class="font-bold text-gray-900">Tambah Area</h3>
            <input name="name" required placeholder="Nama area" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
            <textarea name="description" placeholder="Deskripsi" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"></textarea>
            <div class="flex justify-end gap-2"><button type="button" onclick="this.closest('form').classList.add('hidden')" class="px-3 py-2 bg-gray-100 rounded-lg text-sm">Batal</button><button class="px-3 py-2 bg-yazaki-red text-white rounded-lg text-sm">Simpan Area</button></div>
        </form>

        <div class="space-y-3">
            @forelse($type->areas as $area)
                <details class="bg-white border border-gray-200 rounded-xl shadow-sm" id="area-{{ $area->id }}">
                    <summary class="cursor-pointer list-none px-5 py-4 flex items-center justify-between gap-3">
                        <div><h3 class="font-bold text-gray-900">{{ $area->name }}</h3><p class="text-xs text-gray-500">{{ $area->processes->count() }} proses</p></div>
                        <div class="flex gap-2" onclick="event.stopPropagation(); event.preventDefault()">
                            <button type="button" onclick="document.getElementById('edit-area-{{ $area->id }}').classList.toggle('hidden')" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs font-semibold transition-colors duration-150 hover:bg-gray-200 active:bg-gray-300 active:scale-95">Edit</button>
                            <form action="{{ route('admin.area.destroy', $area->id) }}" method="POST" onsubmit="return confirm('Hapus area {{ addslashes($area->name) }}? Semua proses dan schedule terkait bisa ikut terhapus.')">@csrf @method('DELETE')<button class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold transition-colors duration-150 hover:bg-rose-100 active:bg-rose-200 active:scale-95">Hapus</button></form>
                        </div>
                    </summary>
                    <div class="border-t border-gray-100 p-5 space-y-4">
                        <form id="edit-area-{{ $area->id }}" action="{{ route('admin.area.update', $area->id) }}" method="POST" class="hidden bg-gray-50 rounded-lg p-4 space-y-2">
                            @csrf @method('PUT')
                            <input name="name" value="{{ $area->name }}" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                            <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">{{ $area->description }}</textarea>
                            <button class="px-3 py-2 rounded-lg bg-yazaki-red text-white text-xs font-semibold">Simpan Area</button>
                        </form>
                        <div class="flex justify-between items-center"><h4 class="font-semibold text-gray-800">Daftar Proses</h4><button type="button" onclick="document.getElementById('add-process-{{ $area->id }}').classList.toggle('hidden')" class="text-xs font-semibold text-yazaki-red transition-colors duration-150 hover:text-red-700 hover:underline active:text-red-800">+ Tambah Proses</button></div>
                        <form id="add-process-{{ $area->id }}" action="{{ route('admin.process.store', $area->id) }}" method="POST" class="hidden bg-gray-50 rounded-lg p-4 space-y-2">@csrf
                            <input name="name" required placeholder="Nama proses" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"><textarea name="description" placeholder="Deskripsi" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"></textarea><textarea name="checkpoint" placeholder="Checkpoint" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"></textarea><textarea name="kriteria_judgement" placeholder="Kriteria judgement" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"></textarea><textarea name="audit_intention" placeholder="Audit Intention" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"></textarea><button class="px-3 py-2 rounded-lg bg-yazaki-red text-white text-xs font-semibold">Simpan Proses</button>
                        </form>
                        <div class="divide-y divide-gray-100">
                            @forelse($area->processes as $process)
                                <div class="py-3 flex justify-between gap-3"><div class="min-w-0"><p class="font-semibold text-sm text-gray-900">{{ $process->name }}</p><p class="text-xs text-gray-500 truncate">{{ $process->checkpoint ?: 'Tidak ada checkpoint' }}</p></div><div class="flex gap-2 shrink-0"><button type="button" onclick="document.getElementById('edit-process-{{ $process->id }}').classList.toggle('hidden')" class="px-2 py-1 rounded bg-gray-100 text-xs transition-colors duration-150 hover:bg-gray-200 active:bg-gray-300 active:scale-95">Edit</button><form action="{{ route('admin.process.destroy', $process->id) }}" method="POST" onsubmit="return confirm('Hapus proses {{ addslashes($process->name) }}? {{ \App\Models\AuditSchedule::where('audit_process_id', $process->id)->count() }} schedule dapat ikut terhapus.')">@csrf @method('DELETE')<button class="px-2 py-1 rounded bg-rose-50 text-rose-700 text-xs transition-colors duration-150 hover:bg-rose-100 active:bg-rose-200 active:scale-95">Hapus</button></form></div></div>
                                <form id="edit-process-{{ $process->id }}" action="{{ route('admin.process.update', $process->id) }}" method="POST" class="hidden pb-3 space-y-2">@csrf @method('PUT')<input name="name" value="{{ $process->name }}" required class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"><textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">{{ $process->description }}</textarea><textarea name="checkpoint" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">{{ $process->checkpoint }}</textarea><textarea name="kriteria_judgement" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">{{ $process->kriteria_judgement }}</textarea><textarea name="audit_intention" placeholder="Audit Intention" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">{{ $process->audit_intention }}</textarea><button class="px-3 py-2 rounded-lg bg-yazaki-red text-white text-xs font-semibold">Simpan Proses</button></form>
                            @empty <p class="py-4 text-xs text-gray-500">Belum ada proses.</p> @endforelse
                        </div>
                    </div>
                </details>
            @empty <div class="bg-white border border-gray-200 rounded-xl p-8 text-center text-sm text-gray-500">Belum ada area.</div> @endforelse
        </div>
    </section>
</div>
@endsection
