<?php
namespace Features\Auth\Login;

use Features\Auth\Login\LoginCommand;
use Features\Auth\Login\LoginHandler;
use Features\Auth\Shared\Exceptions\InvalidCredentialsException;

class LoginController
{
    private LoginHandler $handler;

    public function __construct(LoginHandler $handler)
    {
        $this->handler = $handler;
    }

    public function show()
    {
        return inertia('auth/pages/SignIn');
    }

    public function login(array $params)
    {
        $isInertia = isset($_SERVER['HTTP_X_INERTIA']) && $_SERVER['HTTP_X_INERTIA'] === 'true';

        try {
            // Get IP address from server variables
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $command = new LoginCommand($params['email'], $params['password']);
            $result = $this->handler->handle($command, $ipAddress);

            // Set session data for authenticated user
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user'] = $result['user'];
            $_SESSION['session_id'] = $result['session_id'];

            // Handle remember me
            if (isset($params['remember']) && $params['remember']) {
                setcookie('remember_token', $result['session_id'], time() + (86400 * 30), '/', '', true, true);
            }

            // Handle Inertia request (from UI)
            if ($isInertia) {
                return redirect_to('/dashboard');
            }

            // Handle JSON API request
            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (InvalidCredentialsException $e) {
            if ($isInertia) {
                // Flash validation errors for Inertia
                inertia_errors([
                    'email' => $e->getMessage()
                ]);

                // Redirect back to login page
                return redirect_to('/auth/signin');
            }

            // Handle JSON API request
            http_response_code(422);
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function logout()
    {
        $isInertia = isset($_SERVER['HTTP_X_INERTIA']) && $_SERVER['HTTP_X_INERTIA'] === 'true';

        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }

        // Clear session data
        $_SESSION = [];
        session_destroy();

        // Handle Inertia request (from UI)
        if ($isInertia) {
            return redirect_to('/auth/signin');
        }

        // Handle JSON API request
        return [
            'status' => 'success',
            'message' => 'Logged out successfully'
        ];
    }
}
