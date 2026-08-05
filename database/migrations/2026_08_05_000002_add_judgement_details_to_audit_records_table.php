<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_records', function (Blueprint $table) {
            $table->enum('judgement', ['OK', 'NG'])->nullable()->after('status');
            $table->string('foto_ng')->nullable()->after('judgement');
            $table->text('catatan')->nullable()->after('foto_ng');
        });
    }

    public function down(): void
    {
        Schema::table('audit_records', function (Blueprint $table) {
            $table->dropColumn(['judgement', 'foto_ng', 'catatan']);
        });
    }
};
