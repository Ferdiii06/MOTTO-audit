<?php

namespace App\Http\Controllers;

use App\Models\AuditArea;
use App\Models\AuditProcess;
use App\Models\AuditRecord;
use Illuminate\Http\Request;

class AuditProcessController extends Controller
{
    public function standard5s()
    {
        $areas = AuditArea::withCount('processes')
            ->where('category', '5s_standard')
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug')
            ->map(function ($area) {
                return [
                    'name' => $area->name,
                    'slug' => $area->slug,
                    'desc' => $area->description,
                    'icon' => $area->icon_svg,
                    'process_count' => $area->processes_count,
                ];
            });

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

        $processes = $areaModel->processes->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        });

        return view('audit.5s-process', compact('area', 'processes'));
    }

    public function changePointManagement()
    {
        $areaModel = AuditArea::with('processes')
            ->where('slug', 'change-point-management')
            ->first();

        $processes = $areaModel ? $areaModel->processes->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        }) : collect();

        return view('audit.change-point-management', compact('processes'));
    }

    public function licenseSystem()
    {
        $areaModel = AuditArea::with('processes')
            ->where('slug', 'license-system')
            ->first();

        $processes = $areaModel ? $areaModel->processes->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'desc' => $p->description,
                'status' => $p->status,
            ];
        }) : collect();

        return view('audit.license-system', compact('processes'));
    }

    public function showAuditForm(AuditProcess $process)
    {
        $process->load('area');
        $area = $process->area;
        $auditorName = session('audit_user_name', 'Auditor QA');
        $auditDate = date('d F Y');

        return view('audit.form', compact('process', 'area', 'auditorName', 'auditDate'));
    }

    public function submitAuditForm(Request $request, AuditProcess $process)
    {
        $process->load('area');

        $isNg = $request->input('judgement') === 'NG';

        $rules = [
            'judgement' => 'required|in:OK,NG',
            'foto_ng' => $isNg ? 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'catatan' => $isNg ? 'required|string' : 'nullable|string',
        ];

        $request->validate($rules, [
            'judgement.required' => 'Penilaian OK / NG wajib dipilih.',
            'foto_ng.required' => 'Foto temuan wajib diunggah jika penilaian NG.',
            'foto_ng.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, WEBP).',
            'foto_ng.max' => 'Ukuran file foto maksimal 2 MB.',
            'catatan.required' => 'Catatan temuan wajib diisi jika penilaian NG.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_ng')) {
            $fotoPath = $request->file('foto_ng')->store('audit_photos', 'public');
        }

        $userId = session('audit_user_id');
        $auditorName = session('audit_user_name', 'Auditor QA');
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
}
