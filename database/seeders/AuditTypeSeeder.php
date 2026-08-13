<?php

namespace Database\Seeders;

use App\Models\AuditType;
use Illuminate\Database\Seeder;

class AuditTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nama' => '5S Standard',
                'slug' => '5s_standard',
                'deskripsi' => 'Evaluasi standar 5S pada area kerja dan lini produksi',
            ],
            [
                'nama' => 'Change Point Management',
                'slug' => 'change_point',
                'deskripsi' => 'Pengawasan dan audit manajemen perubahan proses',
            ],
            [
                'nama' => 'License System',
                'slug' => 'license_system',
                'deskripsi' => 'Verifikasi dan audit sistem lisensi kerja operator',
            ],
        ];

        foreach ($types as $type) {
            AuditType::updateOrCreate(
                ['slug' => $type['slug']],
                [
                    'nama' => $type['nama'],
                    'deskripsi' => $type['deskripsi'],
                ]
            );
        }
    }
}
