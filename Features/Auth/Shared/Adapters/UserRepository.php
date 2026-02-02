<?php
namespace Features\Auth\Shared\Adapters;

use Features\Auth\Shared\Ports\UserRepositoryInterface;
use Features\Auth\Shared\Domain\User;
use PDO;

class PgUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM auth.user_summary
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $roles = $row['roles'] ? explode(', ', $row['roles']) : [];
        $groups = $row['groups'] ? explode(', ', $row['groups']) : [];

        return new User($row['id'], $row['name'], $row['email'], $row['password'] ?? '', $roles, $groups);
    }

    public function saveSession(string $userId, string $sessionId, array $payload, string $ipAddress): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO auth.sessions
            (session_id, user_id, ip_address, payload, last_activity, is_current, created_at, updated_at)
            VALUES
            (:session_id, :user_id, :ip_address, :payload::jsonb, :last_activity, true, NOW(), NOW())
        ");
        $stmt->execute([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'payload' => json_encode($payload),
            'last_activity' => time()
        ]);
    }

    // User management methods

    public function create(User $user): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO auth.users
            (id, name, email, password, is_active, created_at, updated_at)
            VALUES
            (gen_random_uuid(), :name, :email, :password, true, NOW(), NOW())
        ");
        $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getHashedPassword()
        ]);
    }

    public function findById(string $id): ?User
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM auth.user_summary
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $roles = $row['roles'] ? explode(', ', $row['roles']) : [];
        $groups = $row['groups'] ? explode(', ', $row['groups']) : [];

        return new User($row['id'], $row['name'], $row['email'], $row['password'] ?? '', $roles, $groups);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM auth.users
            WHERE email = :email
        ");

        if ($stmt === false) {
            throw new \Exception("Failed to prepare emailExists query: " . implode(', ', $this->pdo->errorInfo()));
        }

        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    public function updatePassword(string $userId, string $hashedPassword): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE auth.users
            SET password = :password, updated_at = NOW()
            WHERE id = :user_id
        ");
        $stmt->execute([
            'password' => $hashedPassword,
            'user_id' => $userId
        ]);
    }

    // Email verification methods

    public function saveVerificationCode(string $userId, string $code, \DateTime $expiresAt): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO auth.verification_codes
            (user_id, code, expires_at, created_at, updated_at)
            VALUES
            (:user_id, :code, :expires_at, NOW(), NOW())
            ON CONFLICT (user_id) DO UPDATE
            SET code = EXCLUDED.code,
                expires_at = EXCLUDED.expires_at,
                updated_at = NOW()
        ");
        $stmt->execute([
            'user_id' => $userId,
            'code' => $code,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);
    }

    public function getVerificationCode(string $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT code, expires_at
            FROM auth.verification_codes
            WHERE user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function markEmailAsVerified(string $userId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE auth.users
            SET email_verified_at = NOW(), updated_at = NOW()
            WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
    }

    // Password reset methods

    public function createPasswordResetToken(string $email, string $hashedToken, \DateTime $expiresAt): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO auth.password_resets
            (email, token, expires_at, created_at, updated_at)
            VALUES
            (:email, :token, :expires_at, NOW(), NOW())
        ");
        $stmt->execute([
            'email' => $email,
            'token' => $hashedToken,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);
    }

    public function findPasswordResetToken(string $email): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT token, expires_at, created_at
            FROM auth.password_resets
            WHERE email = :email
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function deletePasswordResetTokens(string $email): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM auth.password_resets
            WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
    }
}
