<?php

namespace Features\Auth\Commands;

use Framework\Cli\Command;
use Framework\Database\DB;

/**
 * CreateUserCommand - Create a new user
 *
 * Creates a new user with the specified role.
 * Example usage:
 *   ./vireo create:user@admin john secret123 --email=john@example.com
 *   ./vireo create:user john secret123 --role=moderator
 */
class CreateUserCommand extends Command
{
    /**
     * Command signature
     *
     * The @{role=user} syntax allows:
     * - beamm create:user@admin name pass  (role=admin)
     * - vireo create:user name pass --role=admin  (role=admin)
     * - vireo create:user name pass  (role=user, the default)
     */
    protected string $signature = 'create:user@{role=user} {name} {password} {--email=} {--force}';

    /**
     * Command description
     */
    protected string $description = 'Create a new user with specified role';

    /**
     * Execute the command
     *
     * @return int Exit code
     */
    public function handle(): int
    {
        // Get arguments and options
        $name = $this->argument('name');
        $password = $this->argument('password');
        $role = $this->option('role'); // Will be 'admin' if called with @admin
        $email = $this->option('email');
        $force = $this->option('force');

        // Validate inputs
        if (empty($name) || empty($password)) {
            $this->error('Name and password are required.');
            return self::FAILURE;
        }

        // Display user info
        $this->info('Creating user with the following details:');
        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $name],
                ['Role', $this->output->getColor()->cyan($role)],
                ['Email', $email ?: '(not provided)'],
            ]
        );
        $this->newLine();

        // Confirm before creating (unless forced)
        if (!$force) {
            if (!$this->confirm("Create {$role} user '{$name}'?", true)) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
            $this->newLine();
        }

        try {
            // Get database connection
            $db = DB::connection();

            // Check if user already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$name]);

            if ($stmt->fetch()) {
                $this->error("User '{$name}' already exists.");
                return self::FAILURE;
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Insert user
            $stmt = $db->prepare("
                INSERT INTO users (username, password_hash, email, role, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");

            $result = $stmt->execute([
                $name,
                $passwordHash,
                $email,
                $role
            ]);

            if ($result) {
                $this->success("User '{$name}' created successfully with role '{$role}'!");

                // Display additional info
                $this->newLine();
                $this->info('User can now log in with:');
                $this->line("  Username: {$name}");
                $this->line("  Role: {$role}");

                return self::SUCCESS;
            } else {
                $this->error('Failed to create user.');
                return self::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('Error creating user: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
