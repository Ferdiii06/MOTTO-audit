<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_user_id')->constrained('audit_users')->onDelete('cascade');
            $table->foreignId('audit_process_id')->nullable()->constrained('audit_processes')->onDelete('cascade');
            $table->foreignId('audit_area_id')->nullable()->constrained('audit_areas')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->foreignId('created_by')->nullable()->constrained('audit_users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_schedules');
    }
};
