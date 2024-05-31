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
        Schema::create('nutrients', function (Blueprint $table) {
            $table->id();
            $table->string('source_url');
            $table->string('food_name');
            $table->tinyInteger('food_type')->unsigned();
            $table->decimal('gram_value', 8, 2);
            $table->decimal('kcal', 8, 2);
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
        Schema::dropIfExists('nutrients');
    }
};
