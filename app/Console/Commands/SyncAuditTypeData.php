<?php

namespace App\Console\Commands;

use App\Models\AuditArea;
use App\Models\AuditType;
use Illuminate\Console\Command;

class SyncAuditTypeData extends Command
{
    protected $signature = 'sync-audit-type-data';
    protected $description = 'Sinkronisasi audit_type_id di tabel audit_areas berdasarkan kolom category';

    public function handle(): int
    {
        $this->info('Memulai sinkronisasi audit_type_id...');

        $areas = AuditArea::all();
        $syncedCount = 0;

        foreach ($areas as $area) {
            if (! $area->category) {
                continue;
            }

            $type = AuditType::where('slug', $area->category)->first();

            if ($type) {
                $area->audit_type_id = $type->id;
                $area->save();
                $syncedCount++;
                $this->line("Area ID {$area->id} ('{$area->name}') -> audit_type_id {$type->id} ({$type->nama})");
            } else {
                $this->warn("Tipe audit tidak ditemukan untuk slug category '{$area->category}' pada Area ID {$area->id}");
            }
        }

        $this->info("Sinkronisasi selesai! {$syncedCount} dari {$areas->count()} row berhasil diperbarui.");

        return Command::SUCCESS;
    }
}
