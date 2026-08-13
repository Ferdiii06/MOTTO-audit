<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditArea;
use App\Models\AuditProcess;
use App\Models\AuditSchedule;
use App\Models\AuditUser;
use Exception;
use Illuminate\Http\Request;

class AuditScheduleController extends Controller
{
    private function checkAdmin(): void
    {
        $userId = session('audit_user_id');
        $user = $userId ? AuditUser::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin QA yang dapat mengelola jadwal audit.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        $auditors = AuditUser::orderBy('name')->get();

        $query = AuditSchedule::with(['auditor', 'process', 'area', 'creator']);

        if ($request->filled('auditor_id')) {
            $query->where('audit_user_id', $request->input('auditor_id'));
        }

        $schedules = $query->orderBy('tanggal_mulai', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.jadwal.index', compact('schedules', 'auditors'));
    }

    public function create()
    {
        $this->checkAdmin();

        $auditors = AuditUser::orderBy('name')->get();
        $areas = AuditArea::orderBy('sort_order')->get();
        $processes = AuditProcess::with('area')->orderBy('name')->get();
        $processesGrouped = $processes->groupBy('audit_area_id');

        return view('admin.jadwal.create', compact('auditors', 'areas', 'processes', 'processesGrouped'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'audit_user_id' => 'required|exists:audit_users,id',
            'target_type' => 'required|in:process,area',
            'audit_process_id' => 'required_if:target_type,process|nullable|array|min:1',
            'audit_process_id.*' => 'exists:audit_processes,id',
            'audit_area_id' => 'required_if:target_type,area|nullable|array|min:1',
            'audit_area_id.*' => 'exists:audit_areas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'audit_user_id.required' => 'Auditor wajib dipilih.',
            'audit_user_id.exists' => 'Auditor tidak ditemukan.',
            'target_type.required' => 'Target audit (Proses atau Area) wajib dipilih.',
            'audit_process_id.required_if' => 'Pilih minimal 1 proses audit.',
            'audit_process_id.min' => 'Pilih minimal 1 proses audit.',
            'audit_area_id.required_if' => 'Pilih minimal 1 area audit.',
            'audit_area_id.min' => 'Pilih minimal 1 area audit.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $targetType = $request->input('target_type');
        $targetIds = $targetType === 'process'
            ? (array) $request->input('audit_process_id', [])
            : (array) $request->input('audit_area_id', []);

        $auditor = AuditUser::findOrFail($request->input('audit_user_id'));
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $success = [];
        $failed = [];

        foreach ($targetIds as $id) {
            $processId = $targetType === 'process' ? $id : null;
            $areaId = $targetType === 'area' ? $id : null;

            if ($targetType === 'process') {
                $targetItem = AuditProcess::find($id);
                $targetName = $targetItem ? 'Proses: ' . $targetItem->name : "Proses #{$id}";
            } else {
                $targetItem = AuditArea::find($id);
                $targetName = $targetItem ? 'Area: ' . $targetItem->name : "Area #{$id}";
            }

            $overlap = AuditSchedule::overlapping(
                $tanggalMulai,
                $tanggalSelesai,
                $auditor->tipe_auditor,
                $processId,
                $areaId
            )->first();

            if ($overlap) {
                $existingTarget = $processId
                    ? 'proses ' . optional($overlap->process)->name
                    : 'area ' . optional($overlap->area)->name;
                $existingAuditor = optional($overlap->auditor)->name ?? 'Auditor lain';
                $startDate = $overlap->tanggal_mulai ? $overlap->tanggal_mulai->format('d/m/Y') : '-';
                $endDate = $overlap->tanggal_selesai ? $overlap->tanggal_selesai->format('d/m/Y') : '-';

                $failed[] = [
                    'name' => $targetName,
                    'reason' => "Bentrok dengan auditor {$existingAuditor} (tipe '{$auditor->tipe_auditor}') pada {$existingTarget} ({$startDate} s/d {$endDate})",
                ];
                continue;
            }

            try {
                AuditSchedule::create([
                    'audit_user_id' => $auditor->id,
                    'audit_process_id' => $processId,
                    'audit_area_id' => $areaId,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'created_by' => session('audit_user_id'),
                ]);
                $success[] = $id;
            } catch (Exception $e) {
                $failed[] = [
                    'name' => $targetName,
                    'reason' => 'Gagal menyimpan: ' . $e->getMessage(),
                ];
            }
        }

        session()->flash('bulk_success_count', count($success));
        session()->flash('bulk_failed', $failed);

        if (count($success) > 0 && count($failed) === 0) {
            return redirect()->route('admin.jadwal.index')->with('success', count($success) . ' jadwal audit berhasil ditambahkan.');
        } elseif (count($success) > 0) {
            return redirect()->route('admin.jadwal.index')->with('success', count($success) . ' jadwal audit berhasil ditambahkan, namun ' . count($failed) . ' item gagal/bentrok.');
        } else {
            return redirect()->route('admin.jadwal.index')->with('error', 'Seluruh jadwal (' . count($failed) . ' item) gagal disimpan karena bentrok atau kesalahan lain.');
        }
    }

    public function edit($id)
    {
        $this->checkAdmin();

        $schedule = AuditSchedule::findOrFail($id);
        $auditors = AuditUser::orderBy('name')->get();
        $areas = AuditArea::orderBy('sort_order')->get();
        $processes = AuditProcess::with('area')->orderBy('name')->get();

        return view('admin.jadwal.edit', compact('schedule', 'auditors', 'areas', 'processes'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $schedule = AuditSchedule::findOrFail($id);

        $request->validate([
            'audit_user_id' => 'required|exists:audit_users,id',
            'target_type' => 'required|in:process,area',
            'audit_process_id' => 'required_if:target_type,process|nullable|exists:audit_processes,id',
            'audit_area_id' => 'required_if:target_type,area|nullable|exists:audit_areas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'audit_user_id.required' => 'Auditor wajib dipilih.',
            'audit_user_id.exists' => 'Auditor tidak ditemukan.',
            'target_type.required' => 'Target audit (Proses atau Area) wajib dipilih.',
            'audit_process_id.required_if' => 'Proses audit wajib dipilih.',
            'audit_area_id.required_if' => 'Area audit wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $targetType = $request->input('target_type');
        $processId = $targetType === 'process' ? $request->input('audit_process_id') : null;
        $areaId = $targetType === 'area' ? $request->input('audit_area_id') : null;

        $auditor = AuditUser::findOrFail($request->input('audit_user_id'));

        $overlap = AuditSchedule::overlapping(
            $request->input('tanggal_mulai'),
            $request->input('tanggal_selesai'),
            $auditor->tipe_auditor,
            $processId,
            $areaId,
            $schedule->id
        )->first();

        if ($overlap) {
            $targetName = $processId
                ? 'proses ' . optional($overlap->process)->name
                : 'area ' . optional($overlap->area)->name;

            return redirect()->back()->withInput()->withErrors([
                'tanggal_mulai' => "Jadwal bentrok! Sudah ada jadwal untuk tipe auditor '{$auditor->tipe_auditor}' pada {$targetName} dalam periode tanggal tersebut.",
            ]);
        }

        try {
            $schedule->update([
                'audit_user_id' => $auditor->id,
                'audit_process_id' => $processId,
                'audit_area_id' => $areaId,
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_selesai' => $request->input('tanggal_selesai'),
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'target_type' => 'Gagal menyimpan jadwal: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $schedule = AuditSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal audit berhasil dihapus.');
    }
}
