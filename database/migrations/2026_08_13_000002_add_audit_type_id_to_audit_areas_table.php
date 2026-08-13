<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_areas', function (Blueprint $table) {
            $table->foreignId('audit_type_id')
                ->nullable()
                ->after('category')
                ->constrained('audit_types')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('audit_areas', function (Blueprint $table) {
            $table->dropForeign(['audit_type_id']);
            $table->dropColumn('audit_type_id');
        });
    }
};
