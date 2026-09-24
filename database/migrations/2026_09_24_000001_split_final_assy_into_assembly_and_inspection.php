<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // 1. Ambil data area referensi Final Assy (id: 11)
            $finalAssy = DB::table('audit_areas')->where('id', 11)->first();

            $auditTypeId = $finalAssy ? $finalAssy->audit_type_id : 1;
            $iconSvg = $finalAssy ? $finalAssy->icon_svg : null;

            // 2. Geser sort_order area yang berada setelah Final Assy (sort_order >= 12) sebesar +1
            DB::table('audit_areas')
                ->where('sort_order', '>=', 12)
                ->increment('sort_order');

            // 3. Insert area baru "Assembly & Inspection" dengan sort_order 12
            $newAreaId = DB::table('audit_areas')->insertGetId([
                'audit_type_id' => $auditTypeId,
                'category'      => '5s_standard',
                'name'          => 'Assembly & Inspection',
                'slug'          => 'assembly-inspection',
                'description'   => 'Lini perakitan akhir dan proses inspeksi visual/kelistrikan produk',
                'icon_svg'      => $iconSvg,
                'sort_order'    => 12,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 4. Update audit_area_id pada 31 AuditProcess (ID 295 s/d 325) ke area baru
            $processIds = range(295, 325);

            DB::table('audit_processes')
                ->whereIn('id', $processIds)
                ->update([
                    'audit_area_id' => $newAreaId,
                    'updated_at'    => now(),
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            $newArea = DB::table('audit_areas')->where('slug', 'assembly-inspection')->first();

            if ($newArea) {
                // 1. Kembalikan 31 proses ke area Final Assy (id: 11)
                $processIds = range(295, 325);
                DB::table('audit_processes')
                    ->whereIn('id', $processIds)
                    ->update([
                        'audit_area_id' => 11,
                        'updated_at'    => now(),
                    ]);

                // 2. Hapus area baru
                DB::table('audit_areas')->where('id', $newArea->id)->delete();

                // 3. Kembalikan sort_order area setelahnya (-1)
                DB::table('audit_areas')
                    ->where('sort_order', '>', 12)
                    ->decrement('sort_order');
            }
        });
    }
};
