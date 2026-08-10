<?php

namespace App\Http\Controllers;

use App\Models\AuditArea;
use App\Models\AuditRecord;
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
    public function dashboard()
    {
        $totalAudit = AuditRecord::count();
        $completedAudit = AuditRecord::where('status', 'Selesai')->count();
        $pendingAudit = AuditRecord::where('status', 'Pending')->count();
        $avgScoreValue = AuditRecord::avg('score');

        $stats = [
            'total_audit' => $totalAudit,
            'completed_audit' => $completedAudit,
            'pending_audit' => $pendingAudit,
            'avg_score' => $avgScoreValue ? number_format($avgScoreValue, 1) . '%' : '0%',
        ];

        $recentAudits = AuditRecord::orderBy('audit_date', 'desc')
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
        $totalAudit = AuditRecord::count();
        if ($totalAudit == 0) {
            $totalAudit = 124;
            $lulusOk = 112;
            $temuanNg = 12;
            $kepatuhan = '90%';
        } else {
            $lulusOk = AuditRecord::where('score', '>=', 90)->orWhere('judgement', 'OK')->count();
            $temuanNg = AuditRecord::where(function ($q) {
                $q->where('score', '<', 90)->orWhere('judgement', 'NG');
            })->count();
            $kepatuhan = number_format(($lulusOk / max(1, $totalAudit)) * 100, 0) . '%';
        }

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

        if (AuditRecord::count() > 0) {
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
        } else {
            // Mock records filterable if DB is empty
            $mockData = collect([
                [
                    'id' => 1,
                    'waktu' => '26 Juni 2026 16:45:21',
                    'user' => 'Budi Santoso',
                    'kategori' => '5S Standard',
                    'kategori_code' => '5s_standard',
                    'area' => 'Warehouse',
                    'area_slug' => 'warehouse',
                    'process' => 'Penyimpanan Terminal 1',
                    'no' => 1,
                    'kondisi' => 'NG',
                ],
                [
                    'id' => 2,
                    'waktu' => '26 Juni 2026 16:30:10',
                    'user' => 'Siti Nurhaliza',
                    'kategori' => 'Change Point',
                    'kategori_code' => 'change_point',
                    'area' => 'Change Point Management',
                    'area_slug' => 'change-point-management',
                    'process' => 'Cutting & Crimping 1',
                    'no' => 2,
                    'kondisi' => 'OK',
                ],
                [
                    'id' => 3,
                    'waktu' => '26 Juni 2026 16:15:32',
                    'user' => 'Ahmad Fauzi',
                    'kategori' => 'License System',
                    'kategori_code' => 'license_system',
                    'area' => 'License System',
                    'area_slug' => 'license-system',
                    'process' => 'All Process 1',
                    'no' => 3,
                    'kondisi' => 'OK',
                ],
                [
                    'id' => 4,
                    'waktu' => '26 Juni 2026 16:05:11',
                    'user' => 'Dewi Lestari',
                    'kategori' => '5S Standard',
                    'kategori_code' => '5s_standard',
                    'area' => 'Jalur Jalan',
                    'area_slug' => 'jalur-jalan',
                    'process' => 'Jalur Jalan 1',
                    'no' => 4,
                    'kondisi' => 'NG',
                ],
            ]);

            if ($request->filled('kategori')) {
                $mockData = $mockData->where('kategori_code', $request->input('kategori'));
            }
            if ($request->filled('area')) {
                $mockData = $mockData->where('area_slug', $request->input('area'));
            }
            if ($request->filled('kondisi')) {
                $mockData = $mockData->where('kondisi', $request->input('kondisi'));
            }

            $records = $mockData->values();
            $paginator = null;
        }

        return view('audit.riwayat', compact('stats', 'areas', 'records', 'paginator'));
    }

    public function riwayatDetail($id)
    {
        $record = AuditRecord::with(['area', 'process', 'user'])->find($id);

        if (!$record) {
            // Fallback object jika id mock diklik saat DB masih kosong
            $record = new AuditRecord([
                'id' => $id,
                'audit_date' => now()->toDateString(),
                'area_name' => 'Warehouse',
                'auditor_name' => session('audit_user_name', 'Budi Santoso'),
                'score' => 0.00,
                'status' => 'Selesai',
                'judgement' => 'NG',
                'foto_ng' => null,
                'catatan' => 'Ditemukan part terminal yang tidak menggunakan cover tahan debu pada rak layer 2.',
                'created_at' => now(),
            ]);

            $process = new \App\Models\AuditProcess([
                'name' => 'Penyimpanan Terminal 1',
                'description' => 'Pemeriksaan kerapian dan penyimpanan terminal 1',
                'checkpoint' => 'Cover tahan debu terpasang pada rak penyimpanan terminal dan tertutup rapat.',
                'kriteria_judgement' => 'Evaluasi "OK" apabila terminal tersimpan rapi dalam kardus/tutup. Evaluasi "NG" apabila terkelupas dan exposed.',
            ]);

            $record->setRelation('process', $process);
        }

        return view('audit.detail', compact('record'));
    }

    public function exportRiwayat(Request $request)
    {
        // Build query with same filters as riwayat()
        $query = AuditRecord::with(['area', 'process']);

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
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        // Header row
        $headers = ['No', 'Tanggal', 'Auditor', 'Kategori', 'Area', 'Proses', 'Kondisi', 'Catatan', 'Foto Temuan'];
        foreach ($headers as $colIdx => $header) {
            $sheet->setCellValue($columns[$colIdx] . '1', $header);
        }

        // Style header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C8102E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Column widths
        $widths = [6, 22, 20, 18, 22, 28, 10, 35, 20];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimension($columns[$i])->setWidth($w);
        }

        // Data rows
        $row = 2;
        foreach ($records as $idx => $record) {
            $categoryLabel = '5S Standard';
            if ($record->area) {
                if ($record->area->category === 'change_point') $categoryLabel = 'Change Point';
                elseif ($record->area->category === 'license_system') $categoryLabel = 'License System';
            }
            $kondisi = ($record->judgement === 'OK' || $record->score >= 90) ? 'OK' : 'NG';
            $dateStr = is_string($record->audit_date) ? $record->audit_date : $record->audit_date->format('Y-m-d');

            $sheet->setCellValue("A{$row}", $idx + 1);
            $sheet->setCellValue("B{$row}", $dateStr);
            $sheet->setCellValue("C{$row}", $record->auditor_name);
            $sheet->setCellValue("D{$row}", $categoryLabel);
            $sheet->setCellValue("E{$row}", $record->area_name);
            $sheet->setCellValue("F{$row}", $record->process ? $record->process->name : '-');
            $sheet->setCellValue("G{$row}", $kondisi);
            $sheet->setCellValue("H{$row}", $record->catatan ?? '-');

            // Style kondisi cell color
            if ($kondisi === 'NG') {
                $sheet->getStyle("G{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                ]);
            } else {
                $sheet->getStyle("G{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '065F46']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']],
                ]);
            }

            // Embed foto_ng image
            $rowHeight = 20;
            if ($record->foto_ng) {
                $imagePath = storage_path('app/public/' . $record->foto_ng);
                if (file_exists($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto NG');
                    $drawing->setDescription('Foto Temuan');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(90);
                    $drawing->setCoordinates("I{$row}");
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                    $rowHeight = 75;
                    $sheet->getColumnDimension('I')->setWidth(18);
                }
            }
            $sheet->getRowDimension($row)->setRowHeight($rowHeight);

            // Style data row borders + alignment
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ]);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
        return view('audit.pedoman');
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
