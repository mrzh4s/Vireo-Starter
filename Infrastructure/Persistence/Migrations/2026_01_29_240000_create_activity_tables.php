<?php

use Framework\Database\Migrations\Migration;
use Framework\Database\Migrations\Schema;
use Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateActivityTables
 *
 * Creates activity tracking tables for Framework/Log/Activity
 * - user_activities: Tracks user actions with IP, location, and device info
 * - project_activities: Tracks project workflow and status changes
 */
class CreateActivityTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        // Create user_activities table
        Schema::create('user_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->comment('User ID who performed the action');
            $table->string('ip_address', 45)->comment('IPv4 or IPv6 address');
            $table->text('url')->comment('URL accessed');
            $table->text('location')->nullable()->comment('Geographic location from IP (JSON)');
            $table->text('device')->comment('User agent / device information');
            $table->string('message')->comment('Activity description');
            $table->timestamp('action_at')->comment('When the activity occurred');

            // Indexes for efficient querying
            $table->index(['user_id', 'action_at'], 'user_activities_user_action_idx');
            $table->index('action_at', 'user_activities_action_at_idx');
            $table->index('ip_address', 'user_activities_ip_idx');
        });

        // Create project_activities table
        Schema::create('project_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('system_id')->comment('Project or system identifier');
            $table->string('current_flow')->comment('Current workflow state or status');
            $table->string('username')->comment('Username who triggered the activity');
            $table->text('details')->comment('Activity details and description');
            $table->string('authority_id')->nullable()->comment('Authority or approval ID');
            $table->timestamp('flow_timestamp')->comment('When the flow occurred');

            // Indexes for efficient querying
            $table->index(['system_id', 'flow_timestamp'], 'project_activities_system_flow_idx');
            $table->index('flow_timestamp', 'project_activities_flow_idx');
            $table->index('current_flow', 'project_activities_current_flow_idx');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('user_activities');
    }
}
