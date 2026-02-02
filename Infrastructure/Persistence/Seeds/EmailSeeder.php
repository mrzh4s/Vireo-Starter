<?php

namespace Infrastructure\Persistence\Seeds;

use Vireo\Framework\Database\Seeds\Seeder;

/**
 * Email Seeder
 *
 * Seeds default email templates and layouts.
 */
class EmailSeeder extends Seeder
{
    /**
     * Run the seeder
     */
    public function run(): void
    {
        $this->log('Seeding email layouts...');
        $this->seedLayouts();

        $this->log('Seeding email templates...');
        $this->seedTemplates();

        $this->log('Email seeding completed successfully');
    }

    /**
     * Seed email layouts
     */
    private function seedLayouts(): void
    {
        $layouts = [
            [
                'name' => 'Default Email Layout',
                'slug' => 'default',
                'html' => $this->getDefaultLayoutHtml(),
                'variables' => json_encode([]),
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($layouts as $layout) {
            // Check if layout already exists
            $existing = table('email.layouts')->where('slug', $layout['slug'])->first();
            if (!$existing) {
                table('email.layouts')->insert($layout);
            }
        }
    }

    /**
     * Seed email templates
     */
    private function seedTemplates(): void
    {
        $defaultLayoutId = table('email.layouts')->where('slug', 'default')->first()['id'];

        $templates = [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome',
                'subject' => 'Welcome to {{ $app_name }}!',
                'body_html' => $this->getWelcomeEmailHtml(),
                'body_text' => $this->getWelcomeEmailText(),
                'variables' => json_encode([
                    ['name' => 'name', 'type' => 'string', 'required' => true],
                    ['name' => 'login_url', 'type' => 'string', 'required' => false, 'default' => ''],
                ]),
                'layout_id' => $defaultLayoutId,
                'category' => 'authentication',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset Your Password',
                'body_html' => $this->getPasswordResetHtml(),
                'body_text' => $this->getPasswordResetText(),
                'variables' => json_encode([
                    ['name' => 'name', 'type' => 'string', 'required' => true],
                    ['name' => 'reset_url', 'type' => 'string', 'required' => true],
                    ['name' => 'expires_in', 'type' => 'string', 'required' => false, 'default' => '1 hour'],
                ]),
                'layout_id' => $defaultLayoutId,
                'category' => 'authentication',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Email Verification',
                'slug' => 'email-verification',
                'subject' => 'Verify Your Email Address',
                'body_html' => $this->getEmailVerificationHtml(),
                'body_text' => $this->getEmailVerificationText(),
                'variables' => json_encode([
                    ['name' => 'name', 'type' => 'string', 'required' => true],
                    ['name' => 'verification_url', 'type' => 'string', 'required' => true],
                ]),
                'layout_id' => $defaultLayoutId,
                'category' => 'authentication',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($templates as $template) {
            // Check if template already exists
            $existing = table('email.templates')->where('slug', $template['slug'])->first();
            if (!$existing) {
                table('email.templates')->insert($template);
            }
        }
    }

    /**
     * Get default layout HTML
     */
    private function getDefaultLayoutHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 5px; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #007bff; }
        .content { padding: 20px 0; }
        .footer { text-align: center; padding: 20px 0; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        .button { display: inline-block; padding: 12px 24px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $app_name }}</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $app_name }}. All rights reserved.</p>
            <p>{{ $support_email }}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Get welcome email HTML
     */
    private function getWelcomeEmailHtml(): string
    {
        return <<<'HTML'
<h2>Welcome, {{ $name }}!</h2>
<p>Thank you for joining {{ $app_name }}. We're excited to have you on board!</p>
<p>Your account has been created successfully. You can now log in and start using our platform.</p>
@if($login_url)
<p style="text-align: center; margin: 30px 0;">
    <a href="{{ $login_url }}" class="button">Go to Dashboard</a>
</p>
@endif
<p>If you have any questions, feel free to contact our support team.</p>
<p>Best regards,<br>The {{ $app_name }} Team</p>
HTML;
    }

    /**
     * Get welcome email text
     */
    private function getWelcomeEmailText(): string
    {
        return <<<'TEXT'
Welcome, {{ $name }}!

Thank you for joining {{ $app_name }}. We're excited to have you on board!

Your account has been created successfully. You can now log in and start using our platform.

If you have any questions, feel free to contact our support team.

Best regards,
The {{ $app_name }} Team
TEXT;
    }

    /**
     * Get password reset HTML
     */
    private function getPasswordResetHtml(): string
    {
        return <<<'HTML'
<h2>Reset Your Password</h2>
<p>Hi {{ $name }},</p>
<p>We received a request to reset your password. Click the button below to create a new password:</p>
<p style="text-align: center; margin: 30px 0;">
    <a href="{{ $reset_url }}" class="button">Reset Password</a>
</p>
<p>This link will expire in {{ $expires_in }}.</p>
<p>If you didn't request a password reset, you can safely ignore this email.</p>
<p>Best regards,<br>The {{ $app_name }} Team</p>
HTML;
    }

    /**
     * Get password reset text
     */
    private function getPasswordResetText(): string
    {
        return <<<'TEXT'
Reset Your Password

Hi {{ $name }},

We received a request to reset your password. Visit the link below to create a new password:

{{ $reset_url }}

This link will expire in {{ $expires_in }}.

If you didn't request a password reset, you can safely ignore this email.

Best regards,
The {{ $app_name }} Team
TEXT;
    }

    /**
     * Get email verification HTML
     */
    private function getEmailVerificationHtml(): string
    {
        return <<<'HTML'
<h2>Verify Your Email Address</h2>
<p>Hi {{ $name }},</p>
<p>Thanks for signing up! Please verify your email address by clicking the button below:</p>
<p style="text-align: center; margin: 30px 0;">
    <a href="{{ $verification_url }}" class="button">Verify Email</a>
</p>
<p>If you didn't create an account, you can safely ignore this email.</p>
<p>Best regards,<br>The {{ $app_name }} Team</p>
HTML;
    }

    /**
     * Get email verification text
     */
    private function getEmailVerificationText(): string
    {
        return <<<'TEXT'
Verify Your Email Address

Hi {{ $name }},

Thanks for signing up! Please verify your email address by visiting:

{{ $verification_url }}

If you didn't create an account, you can safely ignore this email.

Best regards,
The {{ $app_name }} Team
TEXT;
    }
}
