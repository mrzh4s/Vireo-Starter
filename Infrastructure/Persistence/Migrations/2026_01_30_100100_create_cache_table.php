<?php

use Framework\Database\Migrations\Migration;
use Framework\Database\Migrations\Schema;
use Framework\Database\Migrations\Blueprint;

/**
 * Cache Table Migration
 *
 * Creates the cache table for database-backed cache storage.
 * Used by DatabaseCache when CACHE_DRIVER=database
 */
class CreateCacheTable extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key', 255)->primary();
            $table->text('value');
            $table->integer('expires_at')->nullable();
            $table->timestamps();

            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
    }
}
