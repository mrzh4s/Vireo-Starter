<?php

namespace Infrastructure\Persistence\Seeds;

use Vireo\Framework\Database\Seeds\Seeder;
use Vireo\Framework\Database\DB;

/**
 * Auth Seeder
 * Converted from: docs/auth/auth.seed.sql
 *
 * Seeds authentication data:
 * - Roles (super_admin, admin, hr_manager, etc.)
 * - Groups (admin, client, authority, vendor, board)
 * - Sample users with details
 * - Role and group assignments
 */
class AuthSeeder extends Seeder
{
    /**
     * Run the seeder
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedGroups();
        $this->seedUsers();

        $this->log('Auth seed data created successfully!');
        $this->log('Default password for all users: password');
        $this->log('Super Admin: superadmin@kutt.my');
    }

    /**
     * Seed roles
     */
    private function seedRoles(): void
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Full system access with all permissions'],
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Administrative access to manage system'],
            ['name' => 'hr_manager', 'display_name' => 'HR Manager', 'description' => 'Human Resources management access'],
            ['name' => 'finance_manager', 'display_name' => 'Finance Manager', 'description' => 'Financial management and oversight access'],
            ['name' => 'department_head', 'display_name' => 'Department Head', 'description' => 'Department leadership access'],
            ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Team management access'],
            ['name' => 'supervisor', 'display_name' => 'Supervisor', 'description' => 'Team supervision access'],
            ['name' => 'senior_staff', 'display_name' => 'Senior Staff', 'description' => 'Senior level employee access'],
            ['name' => 'staff', 'display_name' => 'Staff', 'description' => 'Regular employee access'],
            ['name' => 'intern', 'display_name' => 'Intern', 'description' => 'Internship program access'],
            ['name' => 'contractor', 'display_name' => 'Contractor', 'description' => 'External contractor access'],
            ['name' => 'vendor', 'display_name' => 'Vendor', 'description' => 'Vendor/supplier access'],
            ['name' => 'auditor', 'display_name' => 'Auditor', 'description' => 'System auditing access'],
            ['name' => 'authority_user', 'display_name' => 'Authority User', 'description' => 'Road authority personnel access'],
            ['name' => 'client', 'display_name' => 'Client', 'description' => 'Client/applicant access'],
            ['name' => 'board_member', 'display_name' => 'Board Member', 'description' => 'Board of directors access'],
            ['name' => 'viewer', 'display_name' => 'Viewer', 'description' => 'Read-only access'],
            ['name' => 'guest', 'display_name' => 'Guest', 'description' => 'Limited guest access'],
        ];

        foreach ($roles as $role) {
            DB::query(
                "INSERT INTO auth.roles (name, display_name, description, is_active, created_at, updated_at)
                 VALUES (?, ?, ?, true, datetime('now'), datetime('now'))",
                [$role['name'], $role['display_name'], $role['description']]
            );
        }

        $this->log('Roles seeded: ' . count($roles) . ' roles created');
    }

    /**
     * Seed groups
     */
    private function seedGroups(): void
    {
        $groups = [
            ['name' => 'admin', 'display_name' => 'Admin Group', 'description' => 'Administrative staff including HR, Finance, IT, and Management'],
            ['name' => 'client', 'display_name' => 'Client Group', 'description' => 'Applicants for road corridor submissions'],
            ['name' => 'authority', 'display_name' => 'Authority Group', 'description' => 'Road Authorities and Government Officials'],
            ['name' => 'vendor', 'display_name' => 'Vendor Group', 'description' => 'External vendors and suppliers'],
            ['name' => 'board', 'display_name' => 'Board Members', 'description' => 'Board of directors and executives'],
        ];

        foreach ($groups as $group) {
            DB::query(
                "INSERT INTO auth.groups (name, display_name, description, is_active, created_at, updated_at)
                 VALUES (?, ?, ?, true, datetime('now'), datetime('now'))",
                [$group['name'], $group['display_name'], $group['description']]
            );
        }

        $this->log('Groups seeded: ' . count($groups) . ' groups created');
    }

    /**
     * Seed users with details
     */
    private function seedUsers(): void
    {
        // Password hash for 'password'
        $password = password_hash('password', PASSWORD_DEFAULT);

        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'superadmin@kutt.my',
                'role' => 'super_admin',
                'group' => null,
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone' => '+60123456789',
                'employee_id' => 'SA001',
                'city' => 'Kuala Terengganu',
                'state' => 'Terengganu',
                'country' => 'Malaysia',
            ],
            [
                'name' => 'Ahmad Zaki',
                'email' => 'admin@kutt.my',
                'role' => 'admin',
                'group' => 'admin',
                'first_name' => 'Ahmad',
                'last_name' => 'Zaki',
                'phone' => '+60137654321',
                'employee_id' => 'ADM001',
                'city' => 'Kuala Terengganu',
                'state' => 'Terengganu',
                'country' => 'Malaysia',
            ],
            [
                'name' => 'Sarah Ahmad',
                'email' => 'sarah.ahmad@kutt.my',
                'role' => 'hr_manager',
                'group' => 'admin',
                'first_name' => 'Sarah',
                'last_name' => 'Ahmad',
                'phone' => '+60129876543',
                'employee_id' => 'HR001',
                'city' => 'Kuala Terengganu',
                'state' => 'Terengganu',
                'country' => 'Malaysia',
            ],
        ];

        foreach ($users as $user) {
            // Insert user
            $userId = $this->createUUID();
            DB::query(
                "INSERT INTO auth.users (id, name, email, password, email_verified_at, is_active, created_at, updated_at)
                 VALUES (?, ?, ?, ?, datetime('now'), true, datetime('now'), datetime('now'))",
                [$userId, $user['name'], $user['email'], $password]
            );

            // Insert user details
            DB::query(
                "INSERT INTO auth.user_details (user_id, first_name, last_name, phone, employee_id, city, state, country, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))",
                [$userId, $user['first_name'], $user['last_name'], $user['phone'], $user['employee_id'], $user['city'], $user['state'], $user['country']]
            );

            // Assign role
            $roleId = DB::query("SELECT id FROM auth.roles WHERE name = ?", [$user['role']])->fetch(\PDO::FETCH_COLUMN);
            if ($roleId) {
                DB::query(
                    "INSERT INTO auth.role_user (user_id, role_id, created_at, updated_at)
                     VALUES (?, ?, datetime('now'), datetime('now'))",
                    [$userId, $roleId]
                );
            }

            // Assign group
            if ($user['group']) {
                $groupId = DB::query("SELECT id FROM auth.groups WHERE name = ?", [$user['group']])->fetch(\PDO::FETCH_COLUMN);
                if ($groupId) {
                    DB::query(
                        "INSERT INTO auth.group_user (user_id, group_id, created_at, updated_at)
                         VALUES (?, ?, datetime('now'), datetime('now'))",
                        [$userId, $groupId]
                    );
                }
            }
        }

        $this->log('Users seeded: ' . count($users) . ' users created');
    }

    /**
     * Generate UUID (for SQLite compatibility)
     */
    private function createUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
