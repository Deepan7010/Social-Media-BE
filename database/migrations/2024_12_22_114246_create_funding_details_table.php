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
        Schema::create('funding_details', function (Blueprint $table) {
            $table->id();
            $table->string("funding_type");
            $table->string("funding_subtype");
            $table->string("title");
            $table->string("description");
            $table->string("project_link");
            $table->date("start_date");
            $table->date("end_date");
            $table->string("total_funding_amt");
            $table->string("funding_agency_name");
            $table->string("city");
            $table->string("region");
            $table->string("country");
            $table->string("funding_identifier");
            $table->string("grant_link");
            $table->string("relationship");
            $table->unsignedBigInteger("profile_id");
            $table->timestamps();
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funding_details');
    }
};
