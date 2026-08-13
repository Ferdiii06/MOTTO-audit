<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditArea;
use App\Models\AuditType;
use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuditTypeController extends Controller
{
    private function checkAdmin(): void
    {
        $userId = session('audit_user_id');
        $user = $userId ? AuditUser::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin QA yang dapat mengelola jenis audit.');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $types = AuditType::withCount('areas')->orderBy('id')->get();

        return view('admin.jenis-audit.index', compact('types'));
    }

    public function create()
    {
        $this->checkAdmin();

        return view('admin.jenis-audit.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|regex:/^[a-z0-9_-]+$/|unique:audit_types,slug',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis audit wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, strip (-), dan underscore (_). Tidak boleh ada spasi.',
            'slug.unique' => 'Slug sudah digunakan oleh jenis audit lain.',
        ]);

        AuditType::create([
            'nama' => $request->input('nama'),
            'slug' => $request->input('slug'),
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()->route('admin.jenis-audit.index')->with('success', 'Jenis audit berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->checkAdmin();

        $type = AuditType::findOrFail($id);

        return view('admin.jenis-audit.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $type = AuditType::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_-]+$/',
                Rule::unique('audit_types', 'slug')->ignore($type->id),
            ],
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis audit wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, strip (-), dan underscore (_). Tidak boleh ada spasi.',
            'slug.unique' => 'Slug sudah digunakan oleh jenis audit lain.',
        ]);

        $type->update([
            'nama' => $request->input('nama'),
            'slug' => $request->input('slug'),
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()->route('admin.jenis-audit.index')->with('success', 'Jenis audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $type = AuditType::findOrFail($id);

        $areaCount = AuditArea::where('audit_type_id', $type->id)->count();

        if ($areaCount > 0) {
            return redirect()->back()->with('error', "Jenis audit '{$type->nama}' masih digunakan oleh {$areaCount} area, tidak bisa dihapus.");
        }

        $type->delete();

        return redirect()->route('admin.jenis-audit.index')->with('success', 'Jenis audit berhasil dihapus.');
    }
}
