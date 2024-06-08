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
        Schema::create('cardexes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bimehgar_id')->constrained('bimehgar');


            $table->foreignId('typebimeh_id')->constrained('type_bimeh');


            $table->foreignId('car_id')->constrained('cars');


            $table->foreignId('user_id')->constrained('users');

            $table->date('start')->nullable();
            $table->date('expired')->nullable();
            $table->date('alarm')->nullable();
            $table->string('price')->nullable();
            $table->string('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cardexes');
    }
};
