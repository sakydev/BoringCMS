<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->uuid()->default(DB::raw('uuid_generate_v4()'))->index();
            //$table->unsignedBigInteger('set_id')->nullable();
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->string('name');
            $table->string('field_type');
            $table->json('validation')->nullable();
            $table->json('condition')->nullable();
            $table->boolean('is_required');
            $table->timestamps();

            $table->foreign('collection_id')
                ->references('id')
                ->on('collections')
                ->onDelete('cascade');
            /*
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
