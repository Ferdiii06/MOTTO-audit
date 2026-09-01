<?php

namespace App\Http\Controllers;

use App\Models\AuditArea;
use App\Models\AuditRecord;
use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AuditDashboardController extends Controller
{
    private function getAuthUser(): ?AuditUser
    {
        $userId = session('audit_user_id');
        if (! $userId) {
            return null;
        }

        return AuditUser::find($userId);
    }

    public function dashboard()
    {
        $user = $this->getAuthUser();
        $isAuditor = $user && ! $user->isAdmin();
        $userId = session('audit_user_id');

        $baseQuery = AuditRecord::query();
        if ($isAuditor) {
            $baseQuery->where('audit_user_id', $userId);
        }

        $totalAudit = (clone $baseQuery)->count();
        $completedAudit = (clone $baseQuery)->where('status', 'Selesai')->count();
        $pendingAudit = (clone $baseQuery)->where('status', 'Pending')->count();
        $avgScoreValue = (clone $baseQuery)->avg('score');

        $stats = [
            'total_audit' => $totalAudit,
            'completed_audit' => $completedAudit,
            'pending_audit' => $pendingAudit,
            'avg_score' => $avgScoreValue ? number_format($avgScoreValue, 1) . '%' : '0%',
        ];

        $recentAuditsQuery = AuditRecord::query();
        if ($isAuditor) {
            $recentAuditsQuery->where('audit_user_id', $userId);
        }

        $recentAudits = $recentAuditsQuery->orderBy('audit_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($record) {
                return [
                    'tanggal' => is_string($record->audit_date) ? $record->audit_date : $record->audit_date->format('Y-m-d'),
                    'area' => $record->area_name,
                    'auditor' => $record->auditor_name,
                    'kondisi' => $record->score >= 90 ? 'OK' : 'NG',
                    'status' => $record->status,
                ];
            });

        return view('audit.dashboard', compact('stats', 'recentAudits'));
    }

    public function riwayat(Request $request)
    {
        $user = $this->getAuthUser();
        $isAuditor = $user && ! $user->isAdmin();
        $userId = session('audit_user_id');

        $baseQuery = AuditRecord::query();
        if ($isAuditor) {
            $baseQuery->where('audit_user_id', $userId);
        }

        $totalAudit = (clone $baseQuery)->count();
        $lulusOk = (clone $baseQuery)->where(function ($q) {
            $q->where('score', '>=', 90)->orWhere('judgement', 'OK');
        })->count();

        $temuanNg = (clone $baseQuery)->where(function ($q) {
            $q->where('score', '<', 90)->orWhere('judgement', 'NG');
        })->count();

        $kepatuhan = $totalAudit > 0 ? number_format(($lulusOk / $totalAudit) * 100, 0) . '%' : '0%';

        $stats = [
            'total_audit' => $totalAudit,
            'lulus_ok' => $lulusOk,
            'temuan_ng' => $temuanNg,
            'kepatuhan' => $kepatuhan,
        ];

        // Fetch all areas for area filter dropdown
        $areas = AuditArea::orderBy('category')->orderBy('sort_order')->get();

        // Build query for AuditRecords
        $query = AuditRecord::with(['area', 'process']);

        if ($isAuditor) {
            $query->where('audit_user_id', $userId);
        }

        if ($request->filled('kategori')) {
            $kategori = $request->input('kategori');
            $query->whereHas('area', function ($q) use ($kategori) {
                $q->where('category', $kategori);
            });
        }

        if ($request->filled('area')) {
            $areaSlug = $request->input('area');
            $query->whereHas('area', function ($q) use ($areaSlug) {
                $q->where('slug', $areaSlug)->orWhere('name', $areaSlug);
            });
        }

        if ($request->filled('kondisi')) {
            $kondisi = $request->input('kondisi');
            if ($kondisi === 'OK') {
                $query->where(function ($q) {
                    $q->where('judgement', 'OK')->orWhere('score', '>=', 90);
                });
            } elseif ($kondisi === 'NG') {
                $query->where(function ($q) {
                    $q->where('judgement', 'NG')->orWhere('score', '<', 90);
                });
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('audit_date', [$request->input('start_date'), $request->input('end_date')]);
        }

        $paginator = $query->orderBy('audit_date', 'desc')->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $records = collect($paginator->items())->map(function ($record, $index) use ($paginator) {
            $formattedDate = is_string($record->audit_date) ? $record->audit_date . ' 16:45:21' : $record->audit_date->format('d F Y H:i:s');

            $categoryLabel = '5S Standard';
            if ($record->area) {
                if ($record->area->category === 'change_point') {
                    $categoryLabel = 'Change Point';
                } elseif ($record->area->category === 'license_system') {
                    $categoryLabel = 'License System';
                }
            }

            return [
                'id' => $record->id,
                'waktu' => $formattedDate,
                'user' => $record->auditor_name,
                'kategori' => $categoryLabel,
                'area' => $record->area_name,
                'process' => $record->process ? $record->process->name : 'Penyimpanan Terminal 1',
                'no' => ($paginator->currentPage() - 1) * $paginator->perPage() + $index + 1,
                'kondisi' => ($record->judgement === 'OK' || $record->score >= 90) ? 'OK' : 'NG',
            ];
        });

        return view('audit.riwayat', compact('stats', 'areas', 'records', 'paginator'));
    }

    public function riwayatDetail($id)
    {
        $record = AuditRecord::with(['area', 'process', 'user'])->findOrFail($id);

        return view('audit.detail', compact('record'));
    }

    public function exportRiwayat(Request $request)
    {
        $user = $this->getAuthUser();
        $isAuditor = $user && ! $user->isAdmin();
        $userId = session('audit_user_id');

        // Build query with same filters as riwayat()
        $query = AuditRecord::with(['area', 'process']);

        if ($isAuditor) {
            $query->where('audit_user_id', $userId);
        }

        if ($request->filled('kategori')) {
            $kategori = $request->input('kategori');
            $query->whereHas('area', function ($q) use ($kategori) {
                $q->where('category', $kategori);
            });
        }
        if ($request->filled('area')) {
            $areaSlug = $request->input('area');
            $query->whereHas('area', function ($q) use ($areaSlug) {
                $q->where('slug', $areaSlug)->orWhere('name', $areaSlug);
            });
        }
        if ($request->filled('kondisi')) {
            $kondisi = $request->input('kondisi');
            if ($kondisi === 'OK') {
                $query->where(function ($q) {
                    $q->where('judgement', 'OK')->orWhere('score', '>=', 90);
                });
            } elseif ($kondisi === 'NG') {
                $query->where(function ($q) {
                    $q->where('judgement', 'NG')->orWhere('score', '<', 90);
                });
            }
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('audit_date', [$request->input('start_date'), $request->input('end_date')]);
        }

        $records = $query->orderBy('audit_date', 'desc')->orderBy('id', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Audit');

        // Column letters map
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        // Header row
        $headers = ['No', 'Tanggal', 'Auditor', 'Kategori', 'Area', 'Proses', 'Checkpoint', 'Kriteria Judgement', 'Kondisi', 'Catatan', 'Foto Temuan'];
        foreach ($headers as $colIdx => $header) {
            $sheet->setCellValue($columns[$colIdx] . '1', $header);
        }

        // Style header
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C8102E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Column widths
        $widths = [6, 18, 18, 16, 20, 24, 42, 42, 10, 32, 20];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension($columns[$i])->setWidth($w);
        }

        // Data rows
        $row = 2;
        foreach ($records as $idx => $record) {
            $categoryLabel = '5S Standard';
            if ($record->area) {
                if ($record->area->category === 'change_point') {
                    $categoryLabel = 'Change Point';
                } elseif ($record->area->category === 'license_system') {
                    $categoryLabel = 'License System';
                }
            }
            $kondisi = ($record->judgement === 'OK' || $record->score >= 90) ? 'OK' : 'NG';
            $dateStr = is_string($record->audit_date) ? $record->audit_date : $record->audit_date->format('Y-m-d');
            $checkpointText = $record->process ? ($record->process->checkpoint ?? '-') : '-';
            $kriteriaText = $record->process ? ($record->process->kriteria_judgement ?? '-') : '-';
            $catatanText = $record->catatan ?? '-';

            $sheet->setCellValue("A{$row}", $idx + 1);
            $sheet->setCellValue("B{$row}", $dateStr);
            $sheet->setCellValue("C{$row}", $record->auditor_name);
            $sheet->setCellValue("D{$row}", $categoryLabel);
            $sheet->setCellValue("E{$row}", $record->area_name);
            $sheet->setCellValue("F{$row}", $record->process ? $record->process->name : '-');
            $sheet->setCellValue("G{$row}", $checkpointText);
            $sheet->setCellValue("H{$row}", $kriteriaText);
            $sheet->setCellValue("I{$row}", $kondisi);
            $sheet->setCellValue("J{$row}", $catatanText);

            // Style data row borders + alignment (Vertical TOP for clean look)
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Style kondisi cell color
            if ($kondisi === 'NG') {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                ]);
            } else {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '065F46']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']],
                ]);
            }

            // Estimate dynamic row height based on longest wrapped text
            $linesG = max(1, (int) ceil(strlen($checkpointText) / 40));
            $linesH = max(1, (int) ceil(strlen($kriteriaText) / 40));
            $linesJ = max(1, (int) ceil(strlen($catatanText) / 30));
            $maxLines = max($linesG, $linesH, $linesJ);
            $estimatedHeight = min(120, max(22, $maxLines * 16));

            // Embed foto_ng image
            if ($record->foto_ng) {
                $imagePath = storage_path('app/public/' . $record->foto_ng);
                if (file_exists($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto NG');
                    $drawing->setDescription('Foto Temuan');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(90);
                    $drawing->setCoordinates("K{$row}");
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                    $estimatedHeight = max($estimatedHeight, 75);
                    $sheet->getColumnDimension('K')->setWidth(18);
                }
            }

            $sheet->getRowDimension($row)->setRowHeight($estimatedHeight);

            $row++;
        }

        // Write to temp file and stream download
        $fileName = 'Riwayat_Audit_' . date('Y-m-d_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'audit_export_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function pedoman()
    {
        $areas = AuditArea::with(['processes' => function ($q) {
            $q->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        $user = $this->getAuthUser();
        $isAdmin = $user ? $user->isAdmin() : false;

        return view('audit.pedoman', compact('areas', 'isAdmin'));
    }

    public function placeholder()
    {
        return view('audit.placeholder');
    }

    public function riwayatEdit($id)
    {
        $record = AuditRecord::with(['area', 'process'])->findOrFail($id);

        if ((int) $record->audit_user_id !== (int) session('audit_user_id')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit audit ini');
        }

        return view('audit.edit', compact('record'));
    }

    public function riwayatUpdate(Request $request, $id)
    {
        $record = AuditRecord::findOrFail($id);

        if ((int) $record->audit_user_id !== (int) session('audit_user_id')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit audit ini');
        }

        $isNg = $request->input('judgement') === 'NG';
        $fotoRequired = $isNg && ! $record->foto_ng;

        $rules = [
            'judgement' => 'required|in:OK,NG',
            'status' => 'required|in:Selesai,Pending',
            'foto_ng' => $fotoRequired ? 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'catatan' => $isNg ? 'required|string' : 'nullable|string',
        ];

        $request->validate($rules, [
            'judgement.required' => 'Penilaian OK / NG wajib dipilih.',
            'status.required' => 'Status audit wajib dipilih.',
            'foto_ng.required' => 'Foto temuan wajib diunggah jika penilaian NG.',
            'foto_ng.image' => 'File yang diunggah harus berupa gambar (JPG, PNG, WEBP).',
            'foto_ng.max' => 'Ukuran file foto maksimal 2 MB.',
            'catatan.required' => 'Catatan temuan wajib diisi jika penilaian NG.',
        ]);

        $oldPhotoToDelete = null;

        DB::transaction(function () use ($request, $record, &$oldPhotoToDelete) {
            $judgement = $request->input('judgement');
            $newFotoPath = $record->foto_ng;

            if ($judgement === 'OK') {
                if ($record->foto_ng) {
                    $oldPhotoToDelete = $record->foto_ng;
                    $newFotoPath = null;
                }
            } else {
                if ($request->hasFile('foto_ng')) {
                    if ($record->foto_ng) {
                        $oldPhotoToDelete = $record->foto_ng;
                    }
                    $newFotoPath = $request->file('foto_ng')->store('audit_photos', 'public');
                }
            }

            $record->update([
                'judgement' => $judgement,
                'status' => $request->input('status', $record->status),
                'catatan' => $request->input('catatan'),
                'foto_ng' => $newFotoPath,
            ]);
        });

        if ($oldPhotoToDelete) {
            Storage::disk('public')->delete($oldPhotoToDelete);
        }

        return redirect()->route('audit.riwayat.detail', $record->id)->with('success', 'Data audit berhasil diperbarui!');
    }
}
