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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('container_id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->enum('upload_type', ['audio', 'video', 'document'])->default('document');
            $table->integer('size');
            $table->timestamps();

            $table->foreign('container_id')
                ->references('id')
                ->on('containers')
                ->onDelete('cascade');

            $table->foreign('folder_id')
                ->references('id')
                ->on('folders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
