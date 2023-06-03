<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('department')->nullable();
            $table->string('major')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('level')->nullable();
            $table->bigInteger('phone_num')->nullable();
            $table->bigInteger('student_card_num')->nullable();

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
