<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_users', function (Blueprint $table) {
            $table->string('role')->default('auditor')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('audit_users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
