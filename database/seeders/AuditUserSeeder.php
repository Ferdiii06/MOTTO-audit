<?php

namespace Database\Seeders;

use App\Models\AuditUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuditUserSeeder extends Seeder
{
    public function run(): void
    {
        AuditUser::updateOrCreate(
            ['nik' => '12345'],
            [
                'name' => 'Admin QA',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}