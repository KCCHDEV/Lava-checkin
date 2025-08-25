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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->string('check_in_code')->unique(); // QR code unique identifier
            $table->datetime('check_in_time');
            $table->date('check_in_date');
            $table->string('status')->default('present'); // present, late
            $table->string('location')->nullable(); // where the check-in happened
            $table->string('device_info')->nullable(); // device/browser info
            $table->string('ip_address')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_valid')->default(true); // for validation/verification
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['student_id', 'check_in_date']);
            $table->index(['check_in_date', 'status']);
            $table->index('check_in_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
