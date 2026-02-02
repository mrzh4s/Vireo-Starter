<?php

namespace Features\Auth\Shared\Ports;

use Features\Auth\Shared\Domain\UserDetails;

/**
 * UserDetails Repository Interface
 *
 * Defines the contract for persisting and retrieving UserDetails
 */
interface UserDetailsRepositoryInterface
{
    /**
     * Find user details by user ID
     *
     * @param string $userId
     * @return UserDetails|null
     */
    public function findByUserId(string $userId): ?UserDetails;

    /**
     * Save user details
     *
     * @param UserDetails $userDetails
     * @return UserDetails
     */
    public function save(UserDetails $userDetails): UserDetails;

    /**
     * Update user details
     *
     * @param UserDetails $userDetails
     * @return UserDetails
     */
    public function update(UserDetails $userDetails): UserDetails;

    /**
     * Delete user details by user ID
     *
     * @param string $userId
     * @return bool
     */
    public function deleteByUserId(string $userId): bool;

    /**
     * Check if user details exist for a user
     *
     * @param string $userId
     * @return bool
     */
    public function existsForUser(string $userId): bool;
}
