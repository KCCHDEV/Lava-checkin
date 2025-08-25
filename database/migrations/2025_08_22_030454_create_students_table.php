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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อ-นามสกุล
            $table->string('phone'); // เบอร์โทรศัพท์
            $table->text('address'); // ที่อยู่
            $table->string('student_id')->unique(); // รหัสนักเรียน
            $table->string('class'); // ชั้นเรียน
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
