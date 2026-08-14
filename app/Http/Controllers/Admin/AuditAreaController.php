<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditArea;
use App\Models\AuditProcess;
use App\Models\AuditType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditAreaController extends Controller
{
    private function checkAdmin(): void
    {
        $userId = session('audit_user_id');
        $user = $userId ? \App\Models\AuditUser::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin QA yang dapat mengelola area audit.');
        }
    }

    public function store(Request $request, $typeId)
    {
        $this->checkAdmin();
        $type = AuditType::findOrFail($typeId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_svg' => 'nullable|string',
        ]);

        $data['audit_type_id'] = $type->id;
        $data['category'] = $type->slug;
        $data['sort_order'] = (int) AuditArea::where('audit_type_id', $type->id)->max('sort_order') + 1;
        $data['slug'] = $this->makeSlug($data['name']);

        AuditArea::create($data);

        return back()->with('success', 'Area audit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $area = AuditArea::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_svg' => 'nullable|string',
        ]);

        $area->update($data);

        return back()->with('success', 'Area audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $area = AuditArea::with('processes')->findOrFail($id);

        if ($area->records()->exists() || $area->processes->contains(fn ($process) => $process->records()->exists())) {
            return back()->with('error', 'Area tidak bisa dihapus, masih ada histori audit di dalamnya.');
        }

        $pedomanPaths = $area->processes->pluck('pedoman_path')->filter()->values()->all();
        $area->delete();

        foreach ($pedomanPaths as $path) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Area audit berhasil dihapus.');
    }

    private function makeSlug(string $name): string
    {
        $base = str($name)->slug()->value();
        $slug = $base ?: 'area';
        $suffix = 2;

        while (AuditArea::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}
