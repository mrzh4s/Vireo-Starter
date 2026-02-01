<?php

namespace Infrastructure\Persistence\Migrations;

use Vireo\Framework\Database\Migrations\Migration;
use Vireo\Framework\Database\Migrations\Schema;
use Vireo\Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateTrafficTables
 *
 * Creates the api_traffic table for Framework/Log/Traffic
 * Tracks all API traffic including requests, responses, and performance metrics
 */
class CreateTrafficTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        Schema::create('api_traffic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('traffic')->comment('API or service identifier');
            $table->text('url')->comment('Full URL endpoint');
            $table->string('method', 10)->comment('HTTP method (GET, POST, PUT, DELETE, etc.)');
            $table->text('headers')->nullable()->comment('Request headers (JSON)');
            $table->text('body')->nullable()->comment('Request body/payload');
            $table->text('response')->nullable()->comment('Response data');
            $table->string('status', 20)->comment('Status (success, error, failed)');
            $table->integer('response_time')->nullable()->comment('Response time in milliseconds');
            $table->timestamp('created_at')->comment('When the request was made');

            // Indexes for efficient querying and analytics
            $table->index(['traffic', 'method', 'status', 'created_at'], 'api_traffic_main_idx');
            $table->index('created_at', 'api_traffic_created_at_idx');
            $table->index(['status', 'created_at'], 'api_traffic_status_created_idx');
            $table->index('method', 'api_traffic_method_idx');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('api_traffic');
    }
}
