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
        Schema::create('all_nutrients', function (Blueprint $table) {
            $table->id();
            $table->string('source_url');
            $table->string('food_name');
            $table->integer('gram_value');
            $table->integer('kcal');
            $table->decimal('carbs', 8, 2);
            $table->decimal('protein', 8, 2);
            $table->decimal('fat', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_nutrients');
    }
};
