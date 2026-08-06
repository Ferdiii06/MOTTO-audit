<?php

namespace App\Http\Controllers;

use App\Models\AuditArea;
use App\Models\AuditRecord;
use Illuminate\Http\Request;

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
            $lulusOk = AuditRecord::where('score', '>=', 90)->count();
            $temuanNg = AuditRecord::where('score', '<', 90)->count();
            $kepatuhan = number_format(($lulusOk / max(1, $totalAudit)) * 100, 0) . '%';
        }

        $stats = [
            'total_audit' => $totalAudit,
            'lulus_ok' => $lulusOk,
            'temuan_ng' => $temuanNg,
            'kepatuhan' => $kepatuhan,
        ];

        $areas = AuditArea::where('category', '5s_standard')->orderBy('sort_order')->get();

        $dbRecords = AuditRecord::with(['area', 'process'])
            ->orderBy('audit_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        if ($dbRecords->isNotEmpty()) {
            $records = $dbRecords->map(function ($record, $index) {
                $formattedDate = is_string($record->audit_date) ? $record->audit_date . ' 16:45:21' : $record->audit_date->format('d F Y H:i:s');
                return [
                    'id' => $record->id,
                    'waktu' => $formattedDate,
                    'user' => $record->auditor_name,
                    'area' => $record->area_name,
                    'process' => $record->process ? $record->process->name : 'Penyimpanan Terminal 1',
                    'no' => $index + 1,
                    'kondisi' => $record->score >= 90 ? 'OK' : ($record->judgement ?? 'NG'),
                ];
            });
        } else {
            $records = collect([
                [
                    'id' => 1,
                    'waktu' => '26 Juni 2026 16:45:21',
                    'user' => 'Budi Santoso',
                    'area' => 'Warehouse',
                    'process' => 'Penyimpanan Terminal 1',
                    'no' => 1,
                    'kondisi' => 'NG',
                ],
                [
                    'id' => 2,
                    'waktu' => '26 Juni 2026 16:30:10',
                    'user' => 'Siti Nurhaliza',
                    'area' => 'All Process',
                    'process' => 'General Items 1',
                    'no' => 2,
                    'kondisi' => 'OK',
                ],
                [
                    'id' => 3,
                    'waktu' => '26 Juni 2026 16:15:32',
                    'user' => 'Ahmad Fauzi',
                    'area' => 'Workers and Inspectors',
                    'process' => 'Workers and Inspectors 1',
                    'no' => 3,
                    'kondisi' => 'OK',
                ],
                [
                    'id' => 4,
                    'waktu' => '26 Juni 2026 16:05:11',
                    'user' => 'Dewi Lestari',
                    'area' => 'Jalur Jalan',
                    'process' => 'Jalur Jalan 1',
                    'no' => 4,
                    'kondisi' => 'NG',
                ],
            ]);
        }

        return view('audit.riwayat', compact('stats', 'areas', 'records'));
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

    public function pedoman()
    {
        return view('audit.pedoman');
    }

    public function placeholder()
    {
        return view('audit.placeholder');
    }
}
