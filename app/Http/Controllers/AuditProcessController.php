<?php

namespace App\Http\Controllers;

use App\Models\AuditArea;
use App\Models\AuditProcess;
use App\Models\AuditRecord;
use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditProcessController extends Controller
{
    private function getAuthUser(): ?AuditUser
    {
        $userId = session('audit_user_id');
        if (! $userId) {
            return null;
        }

        return AuditUser::find($userId);
    }

    public function standard5s()
    {
        $user = $this->getAuthUser();
        $allowedIds = $user ? $user->getAllowedProcessIds() : [];

        $areas = AuditArea::where('category', '5s_standard')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($area) use ($user, $allowedIds) {
                if ($user && $user->isAdmin()) {
                    $count = $area->processes()->count();
                } else {
                    $count = $area->processes()->whereIn('id', $allowedIds ?? [])->count();
                }

                return [
                    'name' => $area->name,
                    'slug' => $area->slug,
                    'desc' => $area->description,
                    'icon' => $area->icon_svg,
                    'process_count' => $count,
                ];
            })
            ->when($user && ! $user->isAdmin(), function ($collection) {
                return $collection->filter(fn ($area) => $area['process_count'] > 0);
            })
            ->keyBy('slug');

        return view('audit.5s-standard', compact('areas'));
    }

    public function standard5sProcess($areaSlug)
    {
        $areaModel = AuditArea::with('processes')
            ->where('category', '5s_standard')
            ->where('slug', $areaSlug)
            ->first();

        if (! $areaModel) {
            abort(404, 'Area 5S tidak ditemukan.');
        }

        $area = [
            'name' => $areaModel->name,
            'slug' => $areaModel->slug,
            'desc' => $areaModel->description,
            'icon' => $areaModel->icon_svg,
        ];

        $user = $this->getAuthUser();
        $processesQuery = $areaModel->processes;
        $auditedProcessIds = [];

        if ($user && ! $user->isAdmin()) {
            $allowedIds = $user->getAllowedProcessIds() ?? [];
            $processesQuery = $processesQuery->filter(function ($p) use ($allowedIds) {
                return in_array($p->id, $allowedIds);
            });
            $auditedProcessIds = $user->getAuditedProcessIds();
        }

        $processes = $processesQuery->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        })->values();

        return view('audit.5s-process', compact('area', 'processes', 'auditedProcessIds'));
    }

    public function changePointManagement()
    {
        $areaModel = AuditArea::with('processes')
            ->where('slug', 'change-point-management')
            ->first();

        $user = $this->getAuthUser();
        $auditedProcessIds = [];

        $allProcesses = $areaModel ? $areaModel->processes : collect();

        if ($user && ! $user->isAdmin()) {
            $allowedIds = $user->getAllowedProcessIds() ?? [];
            $allProcesses = $allProcesses->filter(function ($p) use ($allowedIds) {
                return in_array($p->id, $allowedIds);
            });
            $auditedProcessIds = $user->getAuditedProcessIds();
        }

        $processes = $allProcesses->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        })->values();

        return view('audit.change-point-management', compact('processes', 'auditedProcessIds'));
    }

    public function licenseSystem()
    {
        $areaModel = AuditArea::with('processes')
            ->where('slug', 'license-system')
            ->first();

        $user = $this->getAuthUser();
        $auditedProcessIds = [];

        $allProcesses = $areaModel ? $areaModel->processes : collect();

        if ($user && ! $user->isAdmin()) {
            $allowedIds = $user->getAllowedProcessIds() ?? [];
            $allProcesses = $allProcesses->filter(function ($p) use ($allowedIds) {
                return in_array($p->id, $allowedIds);
            });
            $auditedProcessIds = $user->getAuditedProcessIds();
        }

        $processes = $allProcesses->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        })->values();

        return view('audit.license-system', compact('processes', 'auditedProcessIds'));
    }

    public function showAuditForm(AuditProcess $process)
    {
        $user = $this->getAuthUser();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($user->isAdmin()) {
            abort(403, 'Admin QA hanya memiliki akses view-only dan tidak dapat menginput audit.');
        }

        $allowedIds = $user->getAllowedProcessIds() ?? [];
        if (! in_array($process->id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengaudit item check ini.');
        }

        if (in_array($process->id, $user->getAuditedProcessIds())) {
            return redirect()->back()->with('error', 'Proses ini sudah diaudit dalam jadwal aktif Anda.');
        }

        $process->load('area');
        $area = $process->area;
        $auditorName = session('audit_user_name', $user->name);
        $auditDate = date('d F Y');

        return view('audit.form', compact('process', 'area', 'auditorName', 'auditDate'));
    }

    public function submitAuditForm(Request $request, AuditProcess $process)
    {
        $user = $this->getAuthUser();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($user->isAdmin()) {
            abort(403, 'Admin QA hanya memiliki akses view-only dan tidak dapat menginput audit.');
        }

        $allowedIds = $user->getAllowedProcessIds() ?? [];
        if (! in_array($process->id, $allowedIds)) {
            abort(403, 'Anda tidak memiliki akses untuk mengaudit item check ini.');
        }

        if (in_array($process->id, $user->getAuditedProcessIds())) {
            return redirect()->back()->with('error', 'Proses ini sudah diaudit dalam jadwal aktif Anda.');
        }

        $process->load('area');

        $isNg = $request->input('judgement') === 'NG';

        $rules = [
            'judgement' => 'required|in:OK,NG',
            'foto_ng' => $isNg ? 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240' : 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'catatan' => $isNg ? 'required|string' : 'nullable|string',
        ];

        $request->validate($rules, [
            'judgement.required' => 'Penilaian OK / NG wajib dipilih.',
            'foto_ng.required' => 'Foto temuan wajib diunggah jika penilaian NG.',
            'foto_ng.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, WEBP).',
            'foto_ng.max' => 'Ukuran file foto maksimal 10 MB.',
            'catatan.required' => 'Catatan temuan wajib diisi jika penilaian NG.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_ng')) {
            $fotoPath = $request->file('foto_ng')->store('audit_photos', 'public');
        }

        $userId = session('audit_user_id');
        $auditorName = session('audit_user_name', $user->name);
        $areaName = $process->area ? $process->area->name : 'Area Audit';
        $judgement = $request->input('judgement');
        $score = ($judgement === 'OK') ? 100.00 : 0.00;

        AuditRecord::create([
            'audit_area_id' => $process->audit_area_id,
            'audit_process_id' => $process->id,
            'audit_user_id' => $userId,
            'audit_date' => now()->toDateString(),
            'area_name' => $areaName,
            'auditor_name' => $auditorName,
            'score' => $score,
            'status' => 'Selesai',
            'judgement' => $judgement,
            'foto_ng' => $fotoPath,
            'catatan' => $request->input('catatan'),
        ]);

        $areaSlug = $process->area ? $process->area->slug : '';
        $areaCategory = $process->area ? $process->area->category : '';

        if ($areaCategory === 'change_point' || $areaSlug === 'change-point-management') {
            $redirectUrl = '/audit/change-point-management';
        } elseif ($areaCategory === 'license_system' || $areaSlug === 'license-system') {
            $redirectUrl = '/audit/license-system';
        } else {
            $redirectUrl = "/audit/5s-standard/{$areaSlug}";
        }

        return redirect($redirectUrl)->with('success', "Form Audit untuk process '{$process->name}' berhasil disimpan dengan hasil {$judgement}!");
    }

    public function uploadPedoman(Request $request, AuditProcess $process)
    {
        $user = AuditUser::find(session('audit_user_id'));
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Hanya super admin yang dapat mengunggah pedoman SOP.');
        }

        $request->validate([
            'pedoman_file' => 'required|mimes:pdf|max:10240',
        ], [
            'pedoman_file.required' => 'File PDF pedoman wajib dipilih.',
            'pedoman_file.mimes' => 'File pedoman harus berformat PDF.',
            'pedoman_file.max' => 'Ukuran file PDF maksimal 10 MB.',
        ]);

        $newPath = $request->file('pedoman_file')->store('audit_pedoman', 'public');
        $oldPath = $process->pedoman_path;

        $process->pedoman_path = $newPath;
        $process->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->back()->with('success', "File SOP Pedoman untuk '{$process->name}' berhasil diperbarui!");
    }
}
