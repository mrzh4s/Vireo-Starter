<?php

namespace Infrastructure\Persistence\Seeds;

use Vireo\Framework\Database\Seeds\Seeder;
use Vireo\Framework\Database\DB;

/**
 * Attribute Seeder
 *
 * Seeds permission attributes (metadata) for enhanced permission management:
 * - UI attributes (icons, colors, display order)
 * - Behavior flags (requires 2FA, approval workflows)
 * - Contextual information (help text, warnings)
 */
class AttributeSeeder extends Seeder
{
    /**
     * Run the seeder
     */
    public function run(): void
    {
        $this->seedPermissionAttributes();

        $this->log('Attribute seed data created successfully!');
    }

    /**
     * Seed permission attributes
     */
    private function seedPermissionAttributes(): void
    {
        // Define attributes for each permission
        $permissionAttributes = [
            // User Management Attributes
            'users.view' => [
                ['key' => 'icon', 'value' => 'users', 'type' => 'string'],
                ['key' => 'color', 'value' => '#3b82f6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '10', 'type' => 'integer'],
            ],
            'users.create' => [
                ['key' => 'icon', 'value' => 'user-plus', 'type' => 'string'],
                ['key' => 'color', 'value' => '#10b981', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '11', 'type' => 'integer'],
                ['key' => 'requires_approval', 'value' => 'true', 'type' => 'boolean'],
            ],
            'users.update' => [
                ['key' => 'icon', 'value' => 'user-edit', 'type' => 'string'],
                ['key' => 'color', 'value' => '#f59e0b', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '12', 'type' => 'integer'],
            ],
            'users.delete' => [
                ['key' => 'icon', 'value' => 'user-minus', 'type' => 'string'],
                ['key' => 'color', 'value' => '#ef4444', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '13', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
                ['key' => 'warning_message', 'value' => 'This action will permanently delete the user', 'type' => 'string'],
            ],
            'users.manage' => [
                ['key' => 'icon', 'value' => 'users-cog', 'type' => 'string'],
                ['key' => 'color', 'value' => '#8b5cf6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '14', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
            ],

            // Role Management Attributes
            'roles.view' => [
                ['key' => 'icon', 'value' => 'shield', 'type' => 'string'],
                ['key' => 'color', 'value' => '#3b82f6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '20', 'type' => 'integer'],
            ],
            'roles.create' => [
                ['key' => 'icon', 'value' => 'shield-plus', 'type' => 'string'],
                ['key' => 'color', 'value' => '#10b981', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '21', 'type' => 'integer'],
                ['key' => 'requires_approval', 'value' => 'true', 'type' => 'boolean'],
            ],
            'roles.delete' => [
                ['key' => 'icon', 'value' => 'shield-x', 'type' => 'string'],
                ['key' => 'color', 'value' => '#ef4444', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '23', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
            ],
            'roles.manage' => [
                ['key' => 'icon', 'value' => 'shield-check', 'type' => 'string'],
                ['key' => 'color', 'value' => '#8b5cf6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '25', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
            ],

            // Permission Management Attributes
            'permissions.manage' => [
                ['key' => 'icon', 'value' => 'lock', 'type' => 'string'],
                ['key' => 'color', 'value' => '#dc2626', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '30', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
                ['key' => 'warning_message', 'value' => 'Modifying permissions affects system security', 'type' => 'string'],
            ],

            // Project Management Attributes
            'projects.view' => [
                ['key' => 'icon', 'value' => 'folder', 'type' => 'string'],
                ['key' => 'color', 'value' => '#3b82f6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '40', 'type' => 'integer'],
            ],
            'projects.create' => [
                ['key' => 'icon', 'value' => 'folder-plus', 'type' => 'string'],
                ['key' => 'color', 'value' => '#10b981', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '41', 'type' => 'integer'],
            ],
            'projects.approve' => [
                ['key' => 'icon', 'value' => 'check-circle', 'type' => 'string'],
                ['key' => 'color', 'value' => '#10b981', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '44', 'type' => 'integer'],
                ['key' => 'requires_approval', 'value' => 'false', 'type' => 'boolean'],
                ['key' => 'help_text', 'value' => 'Approve or reject project submissions', 'type' => 'string'],
            ],

            // System Settings Attributes
            'settings.manage' => [
                ['key' => 'icon', 'value' => 'cog', 'type' => 'string'],
                ['key' => 'color', 'value' => '#6b7280', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '50', 'type' => 'integer'],
                ['key' => 'requires_2fa', 'value' => 'true', 'type' => 'boolean'],
                ['key' => 'warning_message', 'value' => 'Changes to system settings affect all users', 'type' => 'string'],
            ],

            // Audit Logs Attributes
            'audit.view' => [
                ['key' => 'icon', 'value' => 'file-text', 'type' => 'string'],
                ['key' => 'color', 'value' => '#6b7280', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '60', 'type' => 'integer'],
            ],
            'audit.export' => [
                ['key' => 'icon', 'value' => 'download', 'type' => 'string'],
                ['key' => 'color', 'value' => '#3b82f6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '61', 'type' => 'integer'],
                ['key' => 'help_text', 'value' => 'Export audit logs for compliance reporting', 'type' => 'string'],
            ],

            // Reports Attributes
            'reports.view' => [
                ['key' => 'icon', 'value' => 'chart-bar', 'type' => 'string'],
                ['key' => 'color', 'value' => '#3b82f6', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '70', 'type' => 'integer'],
            ],
            'reports.export' => [
                ['key' => 'icon', 'value' => 'file-export', 'type' => 'string'],
                ['key' => 'color', 'value' => '#10b981', 'type' => 'string'],
                ['key' => 'display_order', 'value' => '72', 'type' => 'integer'],
            ],
        ];

        $insertedCount = 0;
        foreach ($permissionAttributes as $permissionName => $attributes) {
            // Get permission ID
            $permissionId = DB::query(
                "SELECT id FROM auth.permissions WHERE name = ?",
                [$permissionName]
            )->fetch(\PDO::FETCH_COLUMN);

            if (!$permissionId) {
                continue;
            }

            foreach ($attributes as $attr) {
                // Check if attribute already exists
                $existing = DB::query(
                    "SELECT id FROM auth.permission_attributes WHERE permission_id = ? AND attribute_key = ?",
                    [$permissionId, $attr['key']]
                )->fetch();

                if (!$existing) {
                    DB::query(
                        "INSERT INTO auth.permission_attributes (permission_id, attribute_key, attribute_value, attribute_type, created_at, updated_at)
                         VALUES (?, ?, ?, ?, NOW(), NOW())",
                        [$permissionId, $attr['key'], $attr['value'], $attr['type']]
                    );
                    $insertedCount++;
                }
            }
        }

        if ($insertedCount > 0) {
            $this->log('Permission attributes seeded: ' . $insertedCount . ' attributes created');
        } else {
            $this->log('Permission attributes already exist, skipping');
        }
    }
}
