<?php
namespace Features\Auth\Shared\Ports;

use Features\Auth\Shared\Domain\User;

interface UserRepositoryInterface
{
    // Existing methods
    public function findByEmail(string $email): ?User;
    public function saveSession(string $userId, string $sessionId, array $payload, string $ipAddress): void;

    // User management
    public function create(User $user): void;
    public function findById(string $id): ?User;
    public function emailExists(string $email): bool;
    public function updatePassword(string $userId, string $hashedPassword): void;

    // Email verification
    public function saveVerificationCode(string $userId, string $code, \DateTime $expiresAt): void;
    public function getVerificationCode(string $userId): ?array;
    public function markEmailAsVerified(string $userId): void;

    // Password reset
    public function createPasswordResetToken(string $email, string $hashedToken, \DateTime $expiresAt): void;
    public function findPasswordResetToken(string $email): ?array;
    public function deletePasswordResetTokens(string $email): void;
}
