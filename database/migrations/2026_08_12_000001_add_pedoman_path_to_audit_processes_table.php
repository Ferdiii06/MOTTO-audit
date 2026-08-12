<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_processes', function (Blueprint $table) {
            $table->string('pedoman_path')->nullable()->after('kriteria_judgement');
        });
    }

    public function down(): void
    {
        Schema::table('audit_processes', function (Blueprint $table) {
            $table->dropColumn('pedoman_path');
        });
    }
};
