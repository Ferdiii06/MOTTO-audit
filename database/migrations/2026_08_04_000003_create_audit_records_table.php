<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_area_id')->nullable()->constrained('audit_areas')->onDelete('restrict');
            $table->foreignId('audit_process_id')->nullable()->constrained('audit_processes')->onDelete('restrict');
            $table->foreignId('audit_user_id')->nullable()->constrained('audit_users')->onDelete('restrict');
            $table->date('audit_date');
            $table->string('area_name'); // Historical snapshot
            $table->string('auditor_name'); // Historical snapshot
            $table->decimal('score', 5, 2)->default(0);
            $table->string('status')->default('Pending'); // Selesai, Pending
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_records');
    }
};
