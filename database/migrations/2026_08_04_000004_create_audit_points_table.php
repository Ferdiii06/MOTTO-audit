<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_process_id')->constrained('audit_processes')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_points');
    }
};
