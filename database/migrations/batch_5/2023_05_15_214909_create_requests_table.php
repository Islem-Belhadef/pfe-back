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
        Schema::disableForeignKeyConstraints();

        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->string('supervisor_email');
            $table->string('supervisor_first_name');
            $table->string('supervisor_last_name');
            $table->bigInteger('duration')->nullable();
            $table->text('rejection_motive')->nullable();
            $table->string('title');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
