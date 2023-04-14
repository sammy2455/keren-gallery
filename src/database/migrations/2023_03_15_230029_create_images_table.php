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
        Schema::create('images', function (Blueprint $table) {
            $table->string('id', 36)->nullable(false)->primary();
            $table->string('base_path')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('original_filesize')->nullable();
            $table->string('filesize')->nullable();
            $table->string('mime')->nullable();
            $table->string('ext')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
