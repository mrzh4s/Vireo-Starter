<?php

namespace Infrastructure\Persistence\Migrations;

use Vireo\Framework\Database\Migrations\Migration;
use Vireo\Framework\Database\Migrations\Schema;
use Vireo\Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreatePermissionTables
 *
 * Schema: auth
 * Creates permission system tables:
 * - permissions
 * - role_hierarchies, role_permissions
 * - user_permissions
 * - permission_attributes, permission_settings
 * - permission_audit_log
 */
class CreatePermissionTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        // Create permissions table
        Schema::create('auth.permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('module', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('module');
            $table->index('category');
            $table->index('is_active');
        });

        // Create role_hierarchies table (parent-child role relationships)
        Schema::create('auth.role_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_role_id');
            $table->unsignedBigInteger('child_role_id');
            $table->timestamps();

            $table->unique(['parent_role_id', 'child_role_id']);
            $table->foreign('parent_role_id')->references('id')->on('auth.roles')->onDelete('cascade');
            $table->foreign('child_role_id')->references('id')->on('auth.roles')->onDelete('cascade');
            $table->index('parent_role_id');
            $table->index('child_role_id');
        });

        // Create role_permissions table (many-to-many)
        Schema::create('auth.role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('granted_by')->nullable();
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
            $table->foreign('role_id')->references('id')->on('auth.roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('auth.permissions')->onDelete('cascade');
            $table->index('role_id');
            $table->index('permission_id');
        });

        // Create user_permissions table (direct user permission overrides)
        Schema::create('auth.user_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->unsignedBigInteger('permission_id');
            $table->boolean('granted')->default(true);
            $table->unsignedBigInteger('granted_by')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'permission_id']);
            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('auth.permissions')->onDelete('cascade');
            $table->index('user_id');
            $table->index('permission_id');
            $table->index('granted');
            $table->index('expires_at');
        });

        // Create permission_attributes table (permission metadata)
        Schema::create('auth.permission_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->string('attribute_key', 100);
            $table->text('attribute_value')->nullable();
            $table->string('attribute_type', 50)->default('string');
            $table->timestamps();

            $table->unique(['permission_id', 'attribute_key']);
            $table->foreign('permission_id')->references('id')->on('auth.permissions')->onDelete('cascade');
            $table->index('permission_id');
            $table->index('attribute_key');
        });

        // Create permission_settings table (system-wide settings)
        Schema::create('auth.permission_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 50)->unique();
            $table->text('setting_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('updated_at');
        });

        // Create permission_audit_log table (audit trail)
        Schema::create('auth.permission_audit_log', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable();
            $table->string('action', 100);
            $table->string('resource_type', 100)->nullable();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('created_at');

            $table->index('user_id');
            $table->index('action');
            $table->index(['resource_type', 'resource_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('auth.permission_audit_log');
        Schema::dropIfExists('auth.permission_settings');
        Schema::dropIfExists('auth.permission_attributes');
        Schema::dropIfExists('auth.user_permissions');
        Schema::dropIfExists('auth.role_permissions');
        Schema::dropIfExists('auth.role_hierarchies');
        Schema::dropIfExists('auth.permissions');
    }
}
