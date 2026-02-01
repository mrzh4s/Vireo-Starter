<?php
namespace Features\Auth\ForgotPassword;

use Features\Auth\Shared\Ports\UserRepositoryInterface;

class ForgotPasswordHandler
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ForgotPasswordCommand $command): array
    {
        // Check if user exists (silently skip if not found for security)
        $user = $this->repository->findByEmail($command->email);

        if ($user) {
            // Generate secure 64-character token
            $token = bin2hex(random_bytes(32));

            // Hash token before storing
            $hashedToken = hash('sha256', $token);

            // Set expiration to 1 hour from now
            $expiresAt = new \DateTime('+1 hour');

            // Delete any existing reset tokens for this email
            $this->repository->deletePasswordResetTokens($command->email);

            // Save new hashed token
            $this->repository->createPasswordResetToken($command->email, $hashedToken, $expiresAt);

            // Generate reset link (for development/testing)
            // In production, this would be sent via email
            $resetLink = 'http://localhost/auth/reset-password?token=' . $token . '&email=' . urlencode($command->email);

            // For development: return the plain token
            // For production: send email and don't return token
            return [
                'message' => 'If your email exists in our system, you will receive a password reset link.',
                'reset_link' => $resetLink, // For development only - remove in production
                'token' => $token // For development only - remove in production
            ];
        }

        // Always return the same message (don't reveal if email exists)
        return [
            'message' => 'If your email exists in our system, you will receive a password reset link.'
        ];
    }
}
