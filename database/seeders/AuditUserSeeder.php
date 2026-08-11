<?php

namespace Database\Seeders;

use App\Models\AuditUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuditUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user - do not overwrite existing password if user already exists
        $admin = AuditUser::where('nik', '12345')->first();
        if ($admin) {
            $admin->update(['role' => 'admin']);
        } else {
            AuditUser::create([
                'nik' => '12345',
                'name' => 'Admin QA',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // 23 Auditor accounts
        $auditorCodes = [
            'TWI', 'EMU', 'AYP', 'MMR', 'HMA', 'DAF',
            'JIKAS', 'NUR', 'ABD', 'OVI', 'FIR', 'ISR',
            'IAM', 'SSI', 'DIAN', 'NAF', 'WWI', 'RWU',
            'AIN', 'DHS', 'WSU', 'NIA', 'LSE',
        ];

        foreach ($auditorCodes as $code) {
            AuditUser::updateOrCreate(
                ['nik' => $code],
                [
                    'name' => $code,
                    'password' => Hash::make($code),
                    'role' => 'auditor',
                ]
            );
        }
    }
}