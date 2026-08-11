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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demographic_id')->constrained()->cascadeOnDelete();
            $table->string('street_line_1');
            $table->string('street_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code', 20);
            $table->string('country', 2);
            $table->timestamps();

            $table->unique('demographic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
