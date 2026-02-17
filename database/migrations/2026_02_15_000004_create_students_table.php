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
                '3 years classic',
                '4 years classic',
                '5 years classic',
                '6 years classic',
                '7 years classic'
            ]);
            $table->enum('level', [
                'L1',
                'L2',
                'L3',
                'M1',
                'M2',
                'first year',
                'second year',
                'third year',
                'fourth year',
                'fifth year',
                'sixth year',
                'seventh year'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
