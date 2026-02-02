<?php

namespace Features\Auth\Shared\Adapters;

use Features\Auth\Shared\Domain\UserDetails;
use Features\Auth\Shared\Ports\UserDetailsRepositoryInterface;
use Vireo\Framework\Database\DB;

/**
 * UserDetails Repository Implementation
 *
 * Handles persistence of UserDetails domain entities
 */
class PgUserDetailsRepository implements UserDetailsRepositoryInterface
{
    private const TABLE = 'auth.user_details';

    /**
     * Find user details by user ID
     */
    public function findByUserId(string $userId): ?UserDetails
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE user_id = :user_id LIMIT 1";

        $result = DB::query($sql, ['user_id' => $userId]);

        if (empty($result)) {
            return null;
        }

        $row = is_array($result) ? $result[0] : $result->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }

        return UserDetails::fromArray($row);
    }

    /**
     * Save new user details
     */
    public function save(UserDetails $userDetails): UserDetails
    {
        $data = $userDetails->toArray();
        unset($data['id']); // Don't include ID for insert
        unset($data['created_at']); // Let database handle timestamp
        unset($data['updated_at']); // Let database handle timestamp

        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ":$field", $fields);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s) RETURNING *",
            self::TABLE,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $result = DB::query($sql, $data);
        if ($result instanceof \PDOStatement) {
            $row = $result->fetch(\PDO::FETCH_ASSOC);
        } else {
            $row = is_array($result) ? $result[0] : null;
        }
        return UserDetails::fromArray($row);
    }

    /**
     * Update existing user details
     */
    public function update(UserDetails $userDetails): UserDetails
    {
        $data = $userDetails->toArray();
        $userId = $data['user_id'];
        unset($data['id']);
        unset($data['user_id']);
        unset($data['created_at']);
        unset($data['updated_at']); // Let database handle timestamp

        $setClause = implode(', ', array_map(
            fn($field) => "$field = :$field",
            array_keys($data)
        ));

        $sql = sprintf(
            "UPDATE %s SET %s, updated_at = NOW() WHERE user_id = :user_id RETURNING *",
            self::TABLE,
            $setClause
        );

        $data['user_id'] = $userId;
        $result = DB::query($sql, $data);
        if ($result instanceof \PDOStatement) {
            $row = $result->fetch(\PDO::FETCH_ASSOC);
        } else {
            $row = is_array($result) ? $result[0] : null;
        }
        return UserDetails::fromArray($row);
    }

    /**
     * Delete user details by user ID
     */
    public function deleteByUserId(string $userId): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE user_id = :user_id";
        DB::query($sql, ['user_id' => $userId]);
        return true;
    }

    /**
     * Check if user details exist for a user
     */
    public function existsForUser(string $userId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::TABLE . " WHERE user_id = :user_id";

        $result = DB::query($sql, ['user_id' => $userId]);

        return ($result[0]['count'] ?? 0) > 0;
    }

    /**
     * Create or update user details (upsert)
     */
    public function createOrUpdate(UserDetails $userDetails): UserDetails
    {
        if ($this->existsForUser($userDetails->getUserId())) {
            return $this->update($userDetails);
        }

        return $this->save($userDetails);
    }
}
