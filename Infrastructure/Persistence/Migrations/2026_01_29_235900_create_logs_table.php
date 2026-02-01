<?php

use Framework\Database\Migrations\Migration;
use Framework\Database\Migrations\Schema;
use Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateLogsTable
 *
 * Creates the logs table for Framework/Logging/Handlers/DatabaseHandler
 * This table stores all application logs from the PSR-3 compatible logging system
 */
class CreateLogsTable extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel', 255)->comment('Log channel (app, security, database, etc.)');
            $table->string('level', 20)->comment('Log level (debug, info, warning, error, etc.)');
            $table->text('message')->comment('Log message');
            $table->text('context')->nullable()->comment('Additional context data (JSON)');
            $table->text('extra')->nullable()->comment('Extra metadata (JSON)');
            $table->timestamp('created_at')->comment('When the log entry was created');

            // Indexes for efficient querying
            $table->index(['channel', 'level', 'created_at'], 'logs_channel_level_created_idx');
            $table->index('created_at', 'logs_created_at_idx');
            $table->index('level', 'logs_level_idx');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
}
