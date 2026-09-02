<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditProcess;
use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditProcessController extends Controller
{
    private function checkAdmin(): void
    {
        $userId = session('audit_user_id');
        $user = $userId ? AuditUser::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin QA yang dapat mengelola proses audit.');
        }
    }

    public function store(Request $request, $areaId)
    {
        $this->checkAdmin();
        $area = \App\Models\AuditArea::findOrFail($areaId);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'checkpoint' => 'nullable|string',
            'kriteria_judgement' => 'nullable|string',
            'audit_intention' => 'nullable|string',
        ]);

        $data['audit_area_id'] = $area->id;
        $data['sort_order'] = (int) AuditProcess::where('audit_area_id', $area->id)->max('sort_order') + 1;
        $data['status'] = 'Ready';

        AuditProcess::create($data);

        return back()->with('success', 'Proses audit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $process = AuditProcess::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'checkpoint' => 'nullable|string',
            'kriteria_judgement' => 'nullable|string',
            'audit_intention' => 'nullable|string',
        ]);

        $process->update($data);

        return back()->with('success', 'Proses audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $process = AuditProcess::findOrFail($id);

        if ($process->records()->exists()) {
            return back()->with('error', 'Proses tidak bisa dihapus, masih ada histori audit.');
        }

        $scheduleCount = \App\Models\AuditSchedule::where('audit_process_id', $process->id)->count();
        $pedomanPath = $process->pedoman_path;
        $process->delete();

        if ($pedomanPath) {
            Storage::disk('public')->delete($pedomanPath);
        }

        $message = 'Proses audit berhasil dihapus.';
        if ($scheduleCount > 0) {
            $message .= " {$scheduleCount} jadwal audit ikut terhapus.";
        }

        return back()->with('success', $message);
    }
}
