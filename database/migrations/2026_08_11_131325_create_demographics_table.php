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
        Schema::create('demographics', function (Blueprint $table) {
            $table->id();
            $table->morphs('demographicable');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('birthdate');
            $table->string('profile_picture')->nullable();
            $table->text('social_security')->nullable();
            $table->string('gender', 64)->nullable();
            $table->string('race', 64)->nullable();
            $table->string('ethnicity', 64)->nullable();
            $table->string('language', 64)->nullable();
            $table->string('marital_status', 64)->nullable();
            $table->string('education_level', 64)->nullable();
            $table->string('employment_status', 64)->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('income', 12, 2)->nullable();
            $table->timestamps();

            $table->unique(['demographicable_type', 'demographicable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demographics');
    }
};
