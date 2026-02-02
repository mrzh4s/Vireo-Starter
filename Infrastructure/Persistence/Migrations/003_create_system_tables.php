<?php

namespace Infrastructure\Persistence\Migrations;

use Vireo\Framework\Database\Migrations\Migration;
use Vireo\Framework\Database\Migrations\Schema;
use Vireo\Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateSystemTables
 *
 * Schema: system
 * Creates system-level tables for logging, monitoring, and caching:
 * - logs (application logging)
 * - user_activities (user action tracking)
 * - project_activities (workflow tracking)
 * - api_traffic (API monitoring)
 * - cache (database cache storage)
 */
class CreateSystemTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        // Create system schema
        $pdo = \Vireo\Framework\Database\DB::connection('main');
        $pdo->exec('CREATE SCHEMA IF NOT EXISTS system');

        // Create logs table
        Schema::create('system.logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel', 255)->comment('Log channel (app, security, database, etc.)');
            $table->string('level', 20)->comment('Log level (debug, info, warning, error, etc.)');
            $table->text('message')->comment('Log message');
            $table->text('context')->nullable()->comment('Additional context data (JSON)');
            $table->text('extra')->nullable()->comment('Extra metadata (JSON)');
            $table->timestamp('created_at')->comment('When the log entry was created');

            $table->index(['channel', 'level', 'created_at'], 'logs_channel_level_created_idx');
            $table->index('created_at', 'logs_created_at_idx');
            $table->index('level', 'logs_level_idx');
        });

        // Create user_activities table
        Schema::create('system.user_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->comment('User ID who performed the action');
            $table->string('ip_address', 45)->comment('IPv4 or IPv6 address');
            $table->text('url')->comment('URL accessed');
            $table->text('location')->nullable()->comment('Geographic location from IP (JSON)');
            $table->text('device')->comment('User agent / device information');
            $table->string('message')->comment('Activity description');
            $table->timestamp('action_at')->comment('When the activity occurred');

            $table->index(['user_id', 'action_at'], 'user_activities_user_action_idx');
            $table->index('action_at', 'user_activities_action_at_idx');
            $table->index('ip_address', 'user_activities_ip_idx');
        });

        // Create project_activities table
        Schema::create('system.project_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('system_id')->comment('Project or system identifier');
            $table->string('current_flow')->comment('Current workflow state or status');
            $table->string('username')->comment('Username who triggered the activity');
            $table->text('details')->comment('Activity details and description');
            $table->string('authority_id')->nullable()->comment('Authority or approval ID');
            $table->timestamp('flow_timestamp')->comment('When the flow occurred');

            $table->index(['system_id', 'flow_timestamp'], 'project_activities_system_flow_idx');
            $table->index('flow_timestamp', 'project_activities_flow_idx');
            $table->index('current_flow', 'project_activities_current_flow_idx');
        });

        // Create api_traffic table
        Schema::create('system.api_traffic', function (Blueprint $table) {
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

            $table->index(['traffic', 'method', 'status', 'created_at'], 'api_traffic_main_idx');
            $table->index('created_at', 'api_traffic_created_at_idx');
            $table->index(['status', 'created_at'], 'api_traffic_status_created_idx');
            $table->index('method', 'api_traffic_method_idx');
        });

        // Create cache table
        Schema::create('system.cache', function (Blueprint $table) {
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
        Schema::dropIfExists('system.cache');
        Schema::dropIfExists('system.api_traffic');
        Schema::dropIfExists('system.project_activities');
        Schema::dropIfExists('system.user_activities');
        Schema::dropIfExists('system.logs');
    }
}
