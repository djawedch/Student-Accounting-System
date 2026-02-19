<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('academic_year', 9);
            $table->year('baccalaureate_year');
            $table->enum('study_system', [
                'LMD',
                'Classic'
            ]);
            $table->enum('level', [
                'L1',
                'L2',
                'L3',
                'M1',
                'M2'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
