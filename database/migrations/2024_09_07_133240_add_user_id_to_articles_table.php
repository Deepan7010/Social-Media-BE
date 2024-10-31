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
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(); // Add user_id column
            
            // Optionally, add a foreign key constraint to reference the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop the foreign key constraint first (if added)
            $table->dropForeign(['user_id']);
            
            // Then drop the user_id column
            $table->dropColumn('user_id');
        });
    }
};
