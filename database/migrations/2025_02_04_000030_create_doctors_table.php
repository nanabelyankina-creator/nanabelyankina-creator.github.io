<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('specialization_id')
                  ->constrained()
                  ->onDelete('restrict');

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();

            $table->unsignedInteger('experience_years')->default(0);
            $table->string('category')->nullable();

            $table->unsignedInteger('base_price')->default(0);
            $table->text('about')->nullable();
            $table->string('avatar_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};