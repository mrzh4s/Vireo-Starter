<?php

use Framework\Database\Migrations\Migration;
use Framework\Database\Migrations\Schema;
use Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateAuthTables
 * Converted from: docs/auth/auth.table.sql
 *
 * Creates all authentication and authorization tables:
 * - users, user_details
 * - roles, groups
 * - role_user, group_user (pivot tables)
 * - sessions, password_resets, login_attempts, verification_codes
 */
class CreateAuthTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        $schema = $this->schema();

        // Enable UUID extension and create auth schema
        $pdo = \Framework\Database\DB::connection('main');
        $pdo->exec('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $pdo->exec('CREATE SCHEMA IF NOT EXISTS auth');

        // Create users table first (referenced by other tables)
        Schema::create('auth.users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });

        // Create roles table
        Schema::create('auth.roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create groups table
        Schema::create('auth.groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create role_user pivot table
        Schema::create('auth.role_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('auth.roles')->onDelete('cascade');
            $table->index('user_id');
            $table->index('role_id');
        });

        // Create group_user pivot table
        Schema::create('auth.group_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->unique(['user_id', 'group_id']);
            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('auth.groups')->onDelete('cascade');
            $table->index('user_id');
            $table->index('group_id');
        });

        // Create user_details table
        Schema::create('auth.user_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->text('unit_no')->nullable();
            $table->text('street_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('employee_id')->nullable();
            $table->integer('telegram_id')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->index('user_id');
        });

        // Create sessions table
        Schema::create('auth.sessions', function (Blueprint $table) {
            $table->id();
            $table->text('session_id');
            $table->uuid('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload');
            $table->integer('last_activity');
            $table->timestamp('expires_at')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_name')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_trusted')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->index('user_id');
        });

        // Create password_resets table
        Schema::create('auth.password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('email')->references('email')->on('auth.users')->onDelete('cascade');
            $table->index('email');
        });

        // Create login_attempts table
        Schema::create('auth.login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('email')->nullable();
            $table->integer('attempts')->default(1);
            $table->timestamp('last_attempt')->nullable();
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            $table->unique(['ip_address', 'email']);
            $table->foreign('email')->references('email')->on('auth.users')->onDelete('cascade');
            $table->index('email');
        });

        // Create verification_codes table
        Schema::create('auth.verification_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->unique();
            $table->string('code', 10)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth.users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('auth.verification_codes');
        Schema::dropIfExists('auth.login_attempts');
        Schema::dropIfExists('auth.password_resets');
        Schema::dropIfExists('auth.sessions');
        Schema::dropIfExists('auth.user_details');
        Schema::dropIfExists('auth.group_user');
        Schema::dropIfExists('auth.role_user');
        Schema::dropIfExists('auth.groups');
        Schema::dropIfExists('auth.roles');
        Schema::dropIfExists('auth.users');
    }
}
