<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['room', 'equipment']);
            $table->uuid('room_id')->nullable();
            $table->uuid('equipment_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'COMPLETED', 'CANCELLED', 'ACTIVE'])->default('PENDING');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
            $table->foreign('equipment_id')->references('id')->on('equipment')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
