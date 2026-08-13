<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_users', function (Blueprint $table) {
            $table->string('tipe_auditor')->default('auditor')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('audit_users', function (Blueprint $table) {
            $table->dropColumn('tipe_auditor');
        });
    }
};
