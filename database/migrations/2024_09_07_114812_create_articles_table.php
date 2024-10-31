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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->text('paper_title')->nullable();
            $table->longText('abstract')->nullable(); // Long paragraph
            $table->text('publication_name')->nullable();
            $table->year('year')->nullable(); // Year column for publication year
            $table->string('doi')->nullable();
            $table->binary('authors')->nullable(); // BLOB for authors
            $table->text('research_interest')->nullable();
            $table->text('section')->nullable();
            $table->string('link')->nullable();
            $table->string('article')->nullable(); // PDF file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
