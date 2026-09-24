<?php

namespace App\Console\Commands;

use App\Models\AuditArea;
use App\Models\AuditProcess;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SyncChecksheetRevisi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checksheet:sync-revisi
                            {--file= : Path ke file Excel checksheet revisi}
                            {--dry-run : Jalankan simulasi tanpa mengubah database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi konten checksheet (checkpoint, kriteria, intention) dari file Excel ke audit_processes';

    /**
     * Pemetaan alias area Excel ke slug / nama area di database
     */
    protected array $areaAliases = [
        'proses khusus' => 'special-process',
        'pre assy penyimpanan wire (drum)' => 'pre-assy-drums',
        'pre assy (untuk cassete)' => 'pre-assy-cassette',
        'store and shipping area for junkan products' => 'store-shipping-area',
        '後工程' => 'final-assy',
        'assembly & inspection' => 'final-assy',
        'operation license system' => 'license-system',
    ];

    /**
     * Pemetaan alias proses Excel ke basis nama proses di database
     */
    protected array $processAliases = [
        'common items' => 'all process',
        'storage and store of finished product' => 'storage of finished products',
        'finishing' => 'finishing offline clip',
        '仕上げ' => 'finishing offline clip',
        'product shipping area' => 'products shipping area',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        $this->info('======================================================');
        $this->info('  MOTTO-audit: Sync Checksheet Revisi Excel -> Database');
        if ($isDryRun) {
            $this->warn('  [MODE SIMULASI / DRY-RUN ACTIVE: Database TIDAK akan diubah]');
        }
        $this->info('======================================================');

        // 1. Tentukan path file Excel
        $filePath = $this->resolveFilePath();
        if (! $filePath) {
            return Command::FAILURE;
        }

        $this->line("Membaca file: <comment>{$filePath}</comment>");

        // 2. Load Excel menggunakan PhpSpreadsheet
        try {
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
        } catch (\Throwable $e) {
            $this->error('Gagal membaca file Excel: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $allAreas = AuditArea::with('processes')->get();
        $procCounters = [];

        $updatedRows = [];
        $unchangedRows = [];
        $unmatchedRows = [];
        $ambiguousRows = [];

        $this->output->progressStart($highestRow - 1);

        // 3. Baca baris per baris (skip header row 1)
        for ($r = 2; $r <= $highestRow; $r++) {
            $this->output->progressAdvance();

            $no = $sheet->getCell('A' . $r)->getValue();
            $category = trim((string) $sheet->getCell('B' . $r)->getValue());
            $rawArea = trim((string) $sheet->getCell('C' . $r)->getValue());
            $rawProc = trim((string) $sheet->getCell('D' . $r)->getValue());
            $rawCp = (string) $sheet->getCell('E' . $r)->getValue();
            $rawCrit = (string) $sheet->getCell('F' . $r)->getValue();
            $rawIntent = (string) $sheet->getCell('G' . $r)->getValue();

            // Skip baris kosong
            if ($no === null && $rawArea === '' && $rawProc === '') {
                continue;
            }

            // A. Normalisasi dan pencarian Area
            $area = $this->matchArea($rawArea, $allAreas);
            if (! $area) {
                $unmatchedRows[] = [
                    'row' => $r,
                    'no' => $no,
                    'category' => $category,
                    'area' => $rawArea,
                    'process' => $rawProc,
                    'reason' => 'Area tidak ditemukan di database',
                ];
                continue;
            }

            // B. Normalisasi nama Proses dan lacak urutan (sequence) dalam grup proses area tersebut
            $cleanProc = $this->normalizeProcessName($rawProc);
            $procKey = $this->processAliases[$cleanProc] ?? $cleanProc;

            $procCounters[$area->id][$procKey] = ($procCounters[$area->id][$procKey] ?? 0) + 1;
            $seqIndex = $procCounters[$area->id][$procKey];

            // C. Pencocokan ke record AuditProcess dalam area
            $candidates = $this->findProcessCandidates($area, $procKey, $seqIndex);

            if (count($candidates) === 0) {
                $unmatchedRows[] = [
                    'row' => $r,
                    'no' => $no,
                    'category' => $category,
                    'area' => $rawArea,
                    'process' => $rawProc,
                    'reason' => "Proses tidak ditemukan (urutan ke-{$seqIndex} untuk '{$procKey}')",
                ];
                continue;
            }

            if (count($candidates) > 1) {
                $candidateIds = array_map(fn ($p) => $p->id, $candidates);
                $ambiguousRows[] = [
                    'row' => $r,
                    'no' => $no,
                    'category' => $category,
                    'area' => $rawArea,
                    'process' => $rawProc,
                    'candidate_ids' => $candidateIds,
                ];
                continue;
            }

            // D. Ditemukan SATU match tepat
            /** @var AuditProcess $targetProcess */
            $targetProcess = $candidates[0];

            $newCp = $this->normalizeContent($rawCp);
            $newCrit = $this->normalizeContent($rawCrit);
            $newIntent = $this->normalizeContent($rawIntent);

            $currCp = $this->normalizeContent($targetProcess->checkpoint);
            $currCrit = $this->normalizeContent($targetProcess->kriteria_judgement);
            $currIntent = $this->normalizeContent($targetProcess->audit_intention);

            $hasDifference = ($currCp !== $newCp || $currCrit !== $newCrit || $currIntent !== $newIntent);

            if (! $hasDifference) {
                $unchangedRows[] = [
                    'no' => $no,
                    'process_id' => $targetProcess->id,
                    'name' => $targetProcess->name,
                ];
                continue;
            }

            // E. Lakukan Update jika ada perbedaan isi konten
            if (! $isDryRun) {
                $targetProcess->checkpoint = $newCp;
                $targetProcess->kriteria_judgement = $newCrit;
                $targetProcess->audit_intention = $newIntent;
                // Hanya update kolom konten (tidak mengubah audit_area_id, name, sort_order, status, pedoman_path)
                $targetProcess->save();
            }

            $updatedRows[] = [
                'no' => $no,
                'process_id' => $targetProcess->id,
                'name' => $targetProcess->name,
                'area' => $area->name,
                'old_checkpoint' => $currCp,
                'new_checkpoint' => $newCp,
            ];
        }

        $this->output->progressFinish();
        $this->newLine();

        // 4. Tampilkan Laporan Hasil di Console
        $this->renderReport($isDryRun, $updatedRows, $unchangedRows, $unmatchedRows, $ambiguousRows);

        return Command::SUCCESS;
    }

    /**
     * Resolusi path file Excel (support parameter --file, storage/app/imports, dan fallback root)
     */
    protected function resolveFilePath(): ?string
    {
        $customFile = $this->option('file');
        if ($customFile) {
            $path = file_exists($customFile) ? $customFile : base_path($customFile);
            if (! file_exists($path)) {
                $this->error("File yang ditentukan tidak ditemukan: {$customFile}");
                return null;
            }
            return $path;
        }

        $defaultPath = storage_path('app/imports/checksheet terbaru revisi.xlsx');
        if (file_exists($defaultPath)) {
            return $defaultPath;
        }

        // Fallback jika file berada di root project
        $rootFallback = base_path('checksheet terbaru revisi.xlsx');
        if (file_exists($rootFallback)) {
            return $rootFallback;
        }

        $this->error('File checksheet tidak ditemukan di:');
        $this->line("  1. {$defaultPath}");
        $this->line("  2. {$rootFallback}");
        $this->line('Gunakan opsi --file="path/ke/file.xlsx" untuk menentukan lokasi file secara manual.');

        return null;
    }

    /**
     * Normalisasi string Area dan pencocokan ke AuditArea
     */
    protected function matchArea(string $rawArea, $allAreas): ?AuditArea
    {
        $norm = $this->cleanText($rawArea);

        // 1. Cek tabel alias manual
        if (isset($this->areaAliases[$norm])) {
            $slug = $this->areaAliases[$norm];
            $matched = $allAreas->firstWhere('slug', $slug);
            if ($matched) {
                return $matched;
            }
        }

        // 2. Pencocokan langsung dengan nama atau slug
        foreach ($allAreas as $area) {
            $areaNameNorm = $this->cleanText($area->name);
            $areaSlugNorm = str_replace('-', ' ', $area->slug);

            if ($norm === $areaNameNorm || $norm === $areaSlugNorm) {
                return $area;
            }
        }

        // 3. Pencocokan partial substring (hanya jika menghasilkan tepat 1 kandidat)
        $partialMatches = [];
        foreach ($allAreas as $area) {
            $areaNameNorm = $this->cleanText($area->name);
            if (str_contains($norm, $areaNameNorm) || str_contains($areaNameNorm, $norm)) {
                $partialMatches[] = $area;
            }
        }

        if (count($partialMatches) === 1) {
            return $partialMatches[0];
        }

        return null;
    }

    /**
     * Cari kandidat AuditProcess yang sesuai berdasarkan basis nama dan nomor urut di dalam area
     */
    protected function findProcessCandidates(AuditArea $area, string $procKey, int $seqIndex): array
    {
        $exactCandidates = [];
        $partialCandidates = [];

        foreach ($area->processes as $p) {
            $cleanDbName = $this->normalizeProcessName($p->name);

            // Ekstrak basis nama (tanpa angka di akhir) dan nomor urutnya
            $dbBase = trim((string) preg_replace('/\s+\d+$/', '', $cleanDbName));
            $dbNum = (int) preg_replace('/^.*\s+(\d+)$/', '$1', $cleanDbName);

            if ($dbNum === $seqIndex) {
                // TAHAP 1: Exact match prioritas utama
                if ($dbBase === $procKey) {
                    $exactCandidates[] = $p;
                }
                // TAHAP 2: Fallback partial containment
                elseif (str_contains($dbBase, $procKey) || str_contains($procKey, $dbBase)) {
                    $partialCandidates[] = $p;
                }
            }
        }

        // Jika tahap 1 menghasilkan minimal 1 kandidat, pakai hasil ini saja
        if (count($exactCandidates) > 0) {
            return $exactCandidates;
        }

        // Tahap 2: fallback
        return $partialCandidates;
    }

    /**
     * Normalisasi teks umum (lowercase, hapus prefix angka titik, hapus spasi ganda)
     */
    protected function cleanText(string $str): string
    {
        $str = mb_strtolower(trim($str), 'UTF-8');
        // Hapus prefix angka titik seperti "01.", "1.", "02. "
        $str = preg_replace('/^\d+\.\s*/', '', $str);
        // Hapus spasi berlebih
        $str = preg_replace('/\s+/', ' ', $str);

        return trim($str);
    }

    /**
     * Normalisasi spesifik untuk nama proses
     */
    protected function normalizeProcessName(string $str): string
    {
        $str = $this->cleanText($str);

        // Hapus prefix "5s " jika ada
        $str = preg_replace('/^5s\s+/', '', $str);

        // Standarisasi kata sambung
        $str = str_replace([' dan ', ' & '], ' and ', $str);

        // Hilangkan simbol pemisah yang tidak konsisten
        $str = str_replace(['_', '/', '-'], ' ', $str);

        // Hapus teks pembantu dalam tanda kurung, misal: (mae-kotei), (ato-kotei), dll.
        $str = preg_replace('/\([^)]*\)/', '', $str);

        // Hapus spasi berlebih
        $str = preg_replace('/\s+/', ' ', $str);

        return trim($str);
    }

    /**
     * Normalisasi isi teks konten (mengganti CRLF dengan LF, trim whitespace)
     */
    protected function normalizeContent(?string $content): string
    {
        if ($content === null) {
            return '';
        }
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        return trim($content);
    }

    /**
     * Menampilkan laporan ringkas hasil sinkronisasi ke console
     */
    protected function renderReport(
        bool $isDryRun,
        array $updatedRows,
        array $unchangedRows,
        array $unmatchedRows,
        array $ambiguousRows
    ): void {
        $totalUpdated = count($updatedRows);
        $totalUnchanged = count($unchangedRows);
        $totalUnmatched = count($unmatchedRows);
        $totalAmbiguous = count($ambiguousRows);

        $actionWord = $isDryRun ? 'Akan Di-update (Simulasi)' : 'Berhasil Di-update';

        $this->info('----------------- LAPORAN HASIL SINKRONISASI -----------------');
        $this->line("1. {$actionWord} (isi berubah) : <info>{$totalUpdated}</info> baris");
        $this->line("2. Match tapi Isi Sudah Sama (tidak berubah): <comment>{$totalUnchanged}</comment> baris");
        $this->line("3. Unmatched (not_found)                    : <error>{$totalUnmatched}</error> baris");
        $this->line("4. Ambiguous (lebih dari 1 kandidat)        : <error>{$totalAmbiguous}</error> baris");
        $this->info('--------------------------------------------------------------');

        // Daftar baris yang di-update
        if ($totalUpdated > 0) {
            $this->newLine();
            $this->info("Rincian Baris yang {$actionWord} ({$totalUpdated} baris):");
            $rowsTable = [];
            foreach ($updatedRows as $item) {
                $rowsTable[] = [
                    'No' => $item['no'],
                    'Process ID' => $item['process_id'],
                    'Process Name' => $item['name'],
                    'Area' => $item['area'],
                ];
            }
            $this->table(['No', 'Process ID', 'Process Name', 'Area'], $rowsTable);
        }

        // Daftar baris unmatched
        if ($totalUnmatched > 0) {
            $this->newLine();
            $this->error("Rincian Baris Unmatched / Not Found ({$totalUnmatched} baris):");
            $rowsTable = [];
            foreach ($unmatchedRows as $item) {
                $rowsTable[] = [
                    'Row' => $item['row'],
                    'No' => $item['no'],
                    'Category' => $item['category'],
                    'Area' => $item['area'],
                    'Process' => $item['process'],
                    'Alasan' => $item['reason'],
                ];
            }
            $this->table(['Row', 'No', 'Category', 'Area', 'Process', 'Alasan'], $rowsTable);
        }

        // Daftar baris ambiguous
        if ($totalAmbiguous > 0) {
            $this->newLine();
            $this->error("Rincian Baris Ambiguous ({$totalAmbiguous} baris):");
            $rowsTable = [];
            foreach ($ambiguousRows as $item) {
                $rowsTable[] = [
                    'Row' => $item['row'],
                    'No' => $item['no'],
                    'Category' => $item['category'],
                    'Area' => $item['area'],
                    'Process' => $item['process'],
                    'Kandidat Process ID' => implode(', ', $item['candidate_ids']),
                ];
            }
            $this->table(['Row', 'No', 'Category', 'Area', 'Process', 'Kandidat Process ID'], $rowsTable);
        }

        // Cetak sample perbandingan 8 baris acak dari area berbeda
        if (! empty($updatedRows)) {
            $byArea = [];
            foreach ($updatedRows as $row) {
                $byArea[$row['area']][] = $row;
            }

            // Acak urutan area untuk variasi
            $areaKeys = array_keys($byArea);
            shuffle($areaKeys);

            $samples = [];
            foreach ($areaKeys as $areaName) {
                $areaItems = $byArea[$areaName];
                $samples[] = $areaItems[array_rand($areaItems)];
                if (count($samples) >= 8) {
                    break;
                }
            }

            $this->newLine();
            $this->info('================================================================================');
            $this->info('  SAMPLE PERBANDINGAN CHECKPOINT (8 Baris Acak dari Area Berbeda)');
            $this->info('================================================================================');

            foreach ($samples as $i => $s) {
                $idx = $i + 1;
                $oldCp = mb_substr($s['old_checkpoint'], 0, 200);
                $newCp = mb_substr($s['new_checkpoint'], 0, 200);

                $this->line("--------------------------------------------------------------------------------");
                $this->line("<comment>Sample #{$idx}</comment> | No: {$s['no']} | Area: {$s['area']} | Process: {$s['name']} (ID: {$s['process_id']})");
                $this->line("--------------------------------------------------------------------------------");
                $this->line("<error>[LAMA (max 200 char)]:</error>");
                $this->line($oldCp ?: '(kosong)');
                $this->line("<info>[BARU (max 200 char)]:</info>");
                $this->line($newCp ?: '(kosong)');
                $this->newLine();
            }
            $this->info('================================================================================');
        }

        $this->newLine();
        if ($isDryRun) {
            $this->warn('Catatan: Ini adalah DRY-RUN. Jalankan tanpa flag --dry-run untuk menerapkan perubahan ke database.');
        } else {
            $this->info('Sinkronisasi selesai diterapkan ke database.');
        }
    }
}
