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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('blueprint_id')->nullable();
            //$table->unsignedBigInteger('set_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('field_type');
            $table->json('validation');
            $table->json('condition');
            $table->boolean('is_required');
            $table->timestamps();

            /*$table->foreign('blueprint_id')
                ->references('id')
                ->on('blueprints')
                ->onDelete('cascade');

            $table->foreign('set_id')
                ->references('id')
                ->on('sets')
                ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
