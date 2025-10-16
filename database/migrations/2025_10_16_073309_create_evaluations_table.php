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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->decimal('technical_score', 5, 2);
            $table->decimal('design_score', 5, 2);
            $table->decimal('documentation_score', 5, 2);
            $table->decimal('presentation_score', 5, 2);
            $table->decimal('total_score', 5, 2);
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('project_id');
            $table->index('evaluator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
