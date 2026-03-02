<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade');

            $table->foreignId('doctor_id')
                  ->constrained('doctors')
                  ->onDelete('cascade');

            $table->foreignId('specialization_id')
                  ->constrained('specializations')
                  ->onDelete('restrict');

            $table->dateTime('scheduled_at');

            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'no_show'])
                  ->default('scheduled');

            $table->unsignedInteger('price')->default(0);

            $table->enum('created_by_type', ['patient', 'doctor', 'admin', 'guest'])
                  ->default('patient');
            $table->foreignId('created_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};