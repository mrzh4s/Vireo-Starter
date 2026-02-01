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
        return inertia('Login');
    }

    public function login(array $params)
    {
        try {
            // Get IP address from server variables
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $command = new LoginCommand($params['email'], $params['password']);
            $result = $this->handler->handle($command, $ipAddress);

            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (InvalidCredentialsException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function logout()
    {
        // Clear session data
        session_destroy();

        return [
            'status' => 'success',
            'message' => 'Logged out successfully'
        ];
    }
}
