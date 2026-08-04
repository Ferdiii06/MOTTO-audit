<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_areas', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('5s_standard'); // 5s_standard, change_point, license_system
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('icon_svg')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_areas');
    }
};
