<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_scholarship', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('scholarship_id')->constrained()->onDelete('restrict');
            $table->date('grant_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['awarded', 'paid', 'cancelled'])->default('awarded');
            $table->date('paid_at')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scholarship');
    }
};
