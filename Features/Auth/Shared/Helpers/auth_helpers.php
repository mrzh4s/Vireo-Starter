<?php

/**
 * Auth Helper Functions
 *
 * Provides convenient functions for authentication and user management
 */

if (!function_exists('auth')) {
    /**
     * Get the authenticated user or check authentication status
     *
     * @return array|bool Returns user array if authenticated, false otherwise
     */
    function auth()
    {
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            return $_SESSION['user'] ?? false;
        }
        return false;
    }
}

if (!function_exists('user')) {
    /**
     * Get the authenticated user
     *
     * @return array|null Returns user array or null
     */
    function user()
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('user_id')) {
    /**
     * Get the authenticated user's ID
     *
     * @return string|null Returns user ID or null
     */
    function user_id()
    {
        return $_SESSION['user_id'] ?? null;
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    function is_authenticated(): bool
    {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }
}

if (!function_exists('is_guest')) {
    /**
     * Check if user is a guest (not authenticated)
     *
     * @return bool
     */
    function is_guest(): bool
    {
        return !is_authenticated();
    }
}

if (!function_exists('has_role')) {
    /**
     * Check if user has a specific role
     *
     * @param string $role Role name to check
     * @return bool
     */
    function has_role(string $role): bool
    {
        $user = user();
        if (!$user || !isset($user['roles'])) {
            return false;
        }

        return in_array($role, array_column($user['roles'], 'name'));
    }
}

if (!function_exists('has_any_role')) {
    /**
     * Check if user has any of the specified roles
     *
     * @param array $roles Array of role names
     * @return bool
     */
    function has_any_role(array $roles): bool
    {
        foreach ($roles as $role) {
            if (has_role($role)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('has_all_roles')) {
    /**
     * Check if user has all of the specified roles
     *
     * @param array $roles Array of role names
     * @return bool
     */
    function has_all_roles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!has_role($role)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('in_group')) {
    /**
     * Check if user is in a specific group
     *
     * @param string $group Group name to check
     * @return bool
     */
    function in_group(string $group): bool
    {
        $user = user();
        if (!$user || !isset($user['groups'])) {
            return false;
        }

        return in_array($group, array_column($user['groups'], 'name'));
    }
}

if (!function_exists('user_name')) {
    /**
     * Get the authenticated user's name
     *
     * @return string|null
     */
    function user_name(): ?string
    {
        $user = user();
        return $user['name'] ?? null;
    }
}

if (!function_exists('user_email')) {
    /**
     * Get the authenticated user's email
     *
     * @return string|null
     */
    function user_email(): ?string
    {
        $user = user();
        return $user['email'] ?? null;
    }
}
