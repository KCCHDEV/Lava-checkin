<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // รหัสวิชา
            $table->string('name'); // ชื่อวิชา
            $table->text('description')->nullable(); // คำอธิบาย
            $table->string('teacher_name'); // ชื่อครูผู้สอน
            $table->string('class'); // ชั้นเรียน
            $table->boolean('is_active')->default(true); // สถานะการใช้งาน
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
