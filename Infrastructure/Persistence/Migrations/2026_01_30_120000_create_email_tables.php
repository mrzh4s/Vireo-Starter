<?php

namespace Infrastructure\Persistence\Migrations;

use Vireo\Framework\Database\Migrations\Migration;
use Vireo\Framework\Database\Migrations\Schema;
use Vireo\Framework\Database\Migrations\Blueprint;

/**
 * Migration: CreateEmailTables
 *
 * Creates all email system tables:
 * - emails_queue (main queue with status tracking)
 * - email_attachments (attachment metadata and storage)
 * - email_templates (reusable email templates)
 * - email_layouts (template layouts for inheritance)
 * - email_campaigns (bulk email campaigns)
 * - email_tracking (event tracking: opens, clicks, bounces)
 * - email_tracking_pixels (unique tracking tokens)
 * - email_link_tracking (click tracking with URLs)
 */
class CreateEmailTables extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        // Create email_layouts table first (referenced by email_templates)
        Schema::create('email_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('html');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });

        // Create email_templates table
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subject', 500);
            $table->text('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable(); // Template variable definitions
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->string('category', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('layout_id')->references('id')->on('email_layouts')->onDelete('set null');
            $table->index('slug');
            $table->index('category');
            $table->index('is_active');
        });

        // Create email_campaigns table
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('from_address');
            $table->string('from_name');
            $table->string('subject', 500);
            $table->unsignedBigInteger('template_id')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'paused', 'cancelled'])->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('rate_limit')->nullable(); // Emails per hour
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('email_templates')->onDelete('set null');
            $table->index('slug');
            $table->index('status');
            $table->index('scheduled_at');
        });

        // Create emails_queue table (main queue)
        Schema::create('emails_queue', function (Blueprint $table) {
            $table->id();
            $table->string('from_address');
            $table->string('from_name');
            $table->json('to_addresses'); // Array of {email, name}
            $table->json('cc_addresses')->nullable();
            $table->json('bcc_addresses')->nullable();
            $table->string('reply_to')->nullable();
            $table->string('subject', 500);
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->json('template_data')->nullable(); // Template variables
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'processing', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(3);
            $table->text('last_error')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('email_templates')->onDelete('set null');
            $table->foreign('campaign_id')->references('id')->on('email_campaigns')->onDelete('cascade');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('campaign_id');
            $table->index('priority');
            $table->index('created_at');
            $table->index(['status', 'scheduled_at', 'priority']); // Composite for queue processing
        });

        // Create email_attachments table
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id');
            $table->string('filename'); // Stored filename
            $table->string('original_filename'); // Original user filename
            $table->string('mime_type', 100);
            $table->bigInteger('size'); // Size in bytes
            $table->string('storage_disk', 50); // local, public, s3, etc.
            $table->string('storage_path', 500); // Path on disk
            $table->boolean('inline')->default(false); // For inline images
            $table->string('content_id')->nullable(); // CID for inline images
            $table->timestamp('created_at');

            $table->foreign('email_id')->references('id')->on('emails_queue')->onDelete('cascade');
            $table->index('email_id');
        });

        // Create email_tracking_pixels table
        Schema::create('email_tracking_pixels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id')->unique();
            $table->string('pixel_token', 64)->unique(); // SHA256 token
            $table->timestamp('created_at');

            $table->foreign('email_id')->references('id')->on('emails_queue')->onDelete('cascade');
            $table->index('pixel_token');
        });

        // Create email_link_tracking table
        Schema::create('email_link_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id');
            $table->text('original_url');
            $table->string('tracking_token', 64)->unique(); // SHA256 token
            $table->integer('click_count')->default(0);
            $table->timestamp('first_clicked_at')->nullable();
            $table->timestamp('last_clicked_at')->nullable();
            $table->timestamps();

            $table->foreign('email_id')->references('id')->on('emails_queue')->onDelete('cascade');
            $table->index('tracking_token');
            $table->index('email_id');
        });

        // Create email_tracking table (events)
        Schema::create('email_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id');
            $table->enum('event_type', ['sent', 'delivered', 'opened', 'clicked', 'bounced', 'complained', 'unsubscribed']);
            $table->json('event_data')->nullable(); // Additional event data
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable();
            $table->json('location')->nullable(); // Geo location data
            $table->timestamp('tracked_at');

            $table->foreign('email_id')->references('id')->on('emails_queue')->onDelete('cascade');
            $table->index('email_id');
            $table->index('event_type');
            $table->index('tracked_at');
            $table->index(['email_id', 'event_type']); // Composite for statistics
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        // Drop in reverse order (due to foreign keys)
        Schema::dropIfExists('email_tracking');
        Schema::dropIfExists('email_link_tracking');
        Schema::dropIfExists('email_tracking_pixels');
        Schema::dropIfExists('email_attachments');
        Schema::dropIfExists('emails_queue');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_layouts');
    }
}
