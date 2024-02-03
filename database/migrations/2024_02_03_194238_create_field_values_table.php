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
        Schema::create('field_values', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('field_id');
            $table->text('text_value')->nullable();
            $table->text('rich_text_value')->nullable();
            $table->decimal('numeric_value')->nullable();
            $table->date('date_value')->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->json('array_value')->nullable();
            $table->timestamps();

            $table->foreign('field_id')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_values');
    }
};
