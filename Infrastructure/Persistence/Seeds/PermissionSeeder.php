<?php

namespace Infrastructure\Persistence\Seeds;

use Framework\Database\Seeds\Seeder;
use Framework\Database\DB;

/**
 * Permission Seeder
 * Converted from: docs/permission/permission.seed.sql
 *
 * Seeds permission system data:
 * - Permissions (users, roles, projects, etc.)
 * - Role hierarchies
 * - Role-permission assignments
 * - Permission settings
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the seeder
     */
    public function run(): void
    {
        $this->seedPermissions();
        $this->seedRoleHierarchies();
        $this->seedRolePermissions();
        $this->seedPermissionSettings();

        $this->log('Permission seed data created successfully!');
    }

    /**
     * Seed permissions
     */
    private function seedPermissions(): void
    {
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View user listings and profiles', 'module' => 'users', 'category' => 'read'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new users', 'module' => 'users', 'category' => 'write'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'description' => 'Update user information', 'module' => 'users', 'category' => 'write'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete users from system', 'module' => 'users', 'category' => 'write'],
            ['name' => 'users.manage', 'display_name' => 'Manage Users', 'description' => 'Full user management access', 'module' => 'users', 'category' => 'admin'],

            // Role Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View role listings', 'module' => 'roles', 'category' => 'read'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'module' => 'roles', 'category' => 'write'],
            ['name' => 'roles.update', 'display_name' => 'Update Roles', 'description' => 'Update role information', 'module' => 'roles', 'category' => 'write'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete roles', 'module' => 'roles', 'category' => 'write'],
            ['name' => 'roles.assign', 'display_name' => 'Assign Roles', 'description' => 'Assign roles to users', 'module' => 'roles', 'category' => 'write'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'description' => 'Full role management access', 'module' => 'roles', 'category' => 'admin'],

            // Permission Management
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'description' => 'View permission listings', 'module' => 'permissions', 'category' => 'read'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'description' => 'Create new permissions', 'module' => 'permissions', 'category' => 'write'],
            ['name' => 'permissions.update', 'display_name' => 'Update Permissions', 'description' => 'Update permission information', 'module' => 'permissions', 'category' => 'write'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'description' => 'Delete permissions', 'module' => 'permissions', 'category' => 'write'],
            ['name' => 'permissions.grant', 'display_name' => 'Grant Permissions', 'description' => 'Grant permissions to users/roles', 'module' => 'permissions', 'category' => 'write'],
            ['name' => 'permissions.revoke', 'display_name' => 'Revoke Permissions', 'description' => 'Revoke permissions from users/roles', 'module' => 'permissions', 'category' => 'write'],
            ['name' => 'permissions.manage', 'display_name' => 'Manage Permissions', 'description' => 'Full permission management access', 'module' => 'permissions', 'category' => 'admin'],

            // Project Management
            ['name' => 'projects.view', 'display_name' => 'View Projects', 'description' => 'View project listings', 'module' => 'projects', 'category' => 'read'],
            ['name' => 'projects.create', 'display_name' => 'Create Projects', 'description' => 'Create new projects', 'module' => 'projects', 'category' => 'write'],
            ['name' => 'projects.update', 'display_name' => 'Update Projects', 'description' => 'Update project information', 'module' => 'projects', 'category' => 'write'],
            ['name' => 'projects.delete', 'display_name' => 'Delete Projects', 'description' => 'Delete projects', 'module' => 'projects', 'category' => 'write'],
            ['name' => 'projects.approve', 'display_name' => 'Approve Projects', 'description' => 'Approve project submissions', 'module' => 'projects', 'category' => 'admin'],
            ['name' => 'projects.manage', 'display_name' => 'Manage Projects', 'description' => 'Full project management access', 'module' => 'projects', 'category' => 'admin'],

            // System Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'View system settings', 'module' => 'settings', 'category' => 'read'],
            ['name' => 'settings.update', 'display_name' => 'Update Settings', 'description' => 'Update system settings', 'module' => 'settings', 'category' => 'write'],
            ['name' => 'settings.manage', 'display_name' => 'Manage Settings', 'description' => 'Full settings management', 'module' => 'settings', 'category' => 'admin'],

            // Audit Logs
            ['name' => 'audit.view', 'display_name' => 'View Audit Logs', 'description' => 'View system audit logs', 'module' => 'audit', 'category' => 'read'],
            ['name' => 'audit.export', 'display_name' => 'Export Audit Logs', 'description' => 'Export audit log data', 'module' => 'audit', 'category' => 'write'],
            ['name' => 'audit.manage', 'display_name' => 'Manage Audit Logs', 'description' => 'Full audit log management', 'module' => 'audit', 'category' => 'admin'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'View system reports', 'module' => 'reports', 'category' => 'read'],
            ['name' => 'reports.create', 'display_name' => 'Create Reports', 'description' => 'Create custom reports', 'module' => 'reports', 'category' => 'write'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'description' => 'Export report data', 'module' => 'reports', 'category' => 'write'],
            ['name' => 'reports.manage', 'display_name' => 'Manage Reports', 'description' => 'Full report management', 'module' => 'reports', 'category' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            DB::query(
                "INSERT INTO permissions (name, display_name, description, module, category, is_active, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, true, datetime('now'), datetime('now'))",
                [$permission['name'], $permission['display_name'], $permission['description'], $permission['module'], $permission['category']]
            );
        }

        $this->log('Permissions seeded: ' . count($permissions) . ' permissions created');
    }

    /**
     * Seed role hierarchies
     */
    private function seedRoleHierarchies(): void
    {
        // Get role IDs
        $roles = [
            'super_admin' => DB::query("SELECT id FROM roles WHERE name = 'super_admin'")->fetch(\PDO::FETCH_COLUMN),
            'admin' => DB::query("SELECT id FROM roles WHERE name = 'admin'")->fetch(\PDO::FETCH_COLUMN),
            'manager' => DB::query("SELECT id FROM roles WHERE name = 'manager'")->fetch(\PDO::FETCH_COLUMN),
            'supervisor' => DB::query("SELECT id FROM roles WHERE name = 'supervisor'")->fetch(\PDO::FETCH_COLUMN),
            'staff' => DB::query("SELECT id FROM roles WHERE name = 'staff'")->fetch(\PDO::FETCH_COLUMN),
        ];

        $hierarchies = [
            ['parent' => 'super_admin', 'child' => 'admin'],
            ['parent' => 'admin', 'child' => 'manager'],
            ['parent' => 'manager', 'child' => 'supervisor'],
            ['parent' => 'supervisor', 'child' => 'staff'],
        ];

        foreach ($hierarchies as $hierarchy) {
            $parentId = $roles[$hierarchy['parent']] ?? null;
            $childId = $roles[$hierarchy['child']] ?? null;

            if ($parentId && $childId) {
                DB::query(
                    "INSERT INTO role_hierarchies (parent_role_id, child_role_id, created_at, updated_at)
                     VALUES (?, ?, datetime('now'), datetime('now'))",
                    [$parentId, $childId]
                );
            }
        }

        $this->log('Role hierarchies seeded');
    }

    /**
     * Seed role permissions
     */
    private function seedRolePermissions(): void
    {
        // Super Admin gets ALL permissions
        $superAdminId = DB::query("SELECT id FROM roles WHERE name = 'super_admin'")->fetch(\PDO::FETCH_COLUMN);
        if ($superAdminId) {
            $permissions = DB::query("SELECT id FROM permissions")->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($permissions as $permId) {
                DB::query(
                    "INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at)
                     VALUES (?, ?, datetime('now'), datetime('now'))",
                    [$superAdminId, $permId]
                );
            }
        }

        // Admin gets most permissions (except super admin permissions)
        $adminId = DB::query("SELECT id FROM roles WHERE name = 'admin'")->fetch(\PDO::FETCH_COLUMN);
        if ($adminId) {
            $permissions = DB::query(
                "SELECT id FROM permissions WHERE name NOT IN ('permissions.manage', 'roles.manage')"
            )->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($permissions as $permId) {
                DB::query(
                    "INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at)
                     VALUES (?, ?, datetime('now'), datetime('now'))",
                    [$adminId, $permId]
                );
            }
        }

        // Manager gets project management permissions
        $managerId = DB::query("SELECT id FROM roles WHERE name = 'manager'")->fetch(\PDO::FETCH_COLUMN);
        if ($managerId) {
            $permissionNames = ['users.view', 'users.update', 'projects.view', 'projects.create', 'projects.update', 'projects.approve', 'reports.view', 'reports.create', 'reports.export'];
            foreach ($permissionNames as $name) {
                $permId = DB::query("SELECT id FROM permissions WHERE name = ?", [$name])->fetch(\PDO::FETCH_COLUMN);
                if ($permId) {
                    DB::query(
                        "INSERT INTO role_permissions (role_id, permission_id, created_at, updated_at)
                         VALUES (?, ?, datetime('now'), datetime('now'))",
                        [$managerId, $permId]
                    );
                }
            }
        }

        $this->log('Role permissions seeded');
    }

    /**
     * Seed permission settings
     */
    private function seedPermissionSettings(): void
    {
        $settings = [
            ['key' => 'source', 'value' => 'database', 'description' => 'Permission source: database, config, or both'],
            ['key' => 'cache_enabled', 'value' => 'true', 'description' => 'Enable permission caching'],
            ['key' => 'cache_ttl', 'value' => '3600', 'description' => 'Cache time-to-live in seconds'],
            ['key' => 'audit_enabled', 'value' => 'true', 'description' => 'Enable permission audit logging'],
        ];

        foreach ($settings as $setting) {
            DB::query(
                "INSERT INTO permission_settings (setting_key, setting_value, description, updated_at)
                 VALUES (?, ?, ?, datetime('now'))",
                [$setting['key'], $setting['value'], $setting['description']]
            );
        }

        $this->log('Permission settings seeded');
    }
}
