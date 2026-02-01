<?php
namespace Features\Auth\ResetPassword;

use Features\Auth\Shared\Ports\UserRepositoryInterface;
use Features\Auth\Shared\Exceptions\InvalidResetTokenException;

class ResetPasswordHandler
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ResetPasswordCommand $command): array
    {
        // Find user by email
        $user = $this->repository->findByEmail($command->email);
        if (!$user) {
            throw new InvalidResetTokenException();
        }

        // Get stored token from database
        $resetToken = $this->repository->findPasswordResetToken($command->email);
        if (!$resetToken) {
            throw new InvalidResetTokenException();
        }

        // Check expiration
        $expiresAt = new \DateTime($resetToken['expires_at']);
        if ($expiresAt < new \DateTime()) {
            throw new InvalidResetTokenException('Password reset token has expired.');
        }

        // Hash the provided token and compare with stored hashed token
        $hashedProvidedToken = hash('sha256', $command->token);
        if (!hash_equals($resetToken['token'], $hashedProvidedToken)) {
            throw new InvalidResetTokenException();
        }

        // Update password with new hashed password
        $newHashedPassword = password_hash($command->password, PASSWORD_BCRYPT);
        $this->repository->updatePassword($user->getId(), $newHashedPassword);

        // Delete the used token
        $this->repository->deletePasswordResetTokens($command->email);

        // Optional: Invalidate all user sessions except current
        // $this->repository->invalidateUserSessions($user->getId());

        return [
            'message' => 'Password has been reset successfully. You can now login with your new password.'
        ];
    }
}
