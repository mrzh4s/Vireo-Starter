<?php
namespace Features\Auth\Register;

use Features\Auth\Register\RegisterCommand;
use Features\Auth\Register\RegisterHandler;
use Features\Auth\Shared\Exceptions\UserAlreadyExistsException;
use Framework\Validation\ValidationException;

class RegisterController
{
    private RegisterHandler $handler;

    public function __construct(RegisterHandler $handler)
    {
        $this->handler = $handler;
    }

    public function register(array $params)
    {
        $isInertia = isset($_SERVER['HTTP_X_INERTIA']) && $_SERVER['HTTP_X_INERTIA'] === 'true';

        try {
            // Validate input
            $validated = validate($params, [
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password',
                'terms' => 'required'
            ]);

            // Create command and delegate to handler
            $command = new RegisterCommand(
                $validated['name'],
                $validated['email'],
                $validated['password'],
                $validated['password_confirmation'],
                (bool)$validated['terms']
            );

            $result = $this->handler->handle($command);

            // Handle Inertia request (from UI)
            if ($isInertia) {
                $_SESSION['success'] = $result['message'] ?? 'Registration successful!';
                redirect_to('/auth/signin');
            }

            // Handle JSON API request
            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (UserAlreadyExistsException $e) {
            if ($isInertia) {
                $_SESSION['error'] = $e->getMessage();
                redirect_back('/auth/register');
            }
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        } catch (ValidationException $e) {
            if ($isInertia) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['old'] = $params;
                redirect_back('/auth/register');
            }
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->getErrors()
            ];
        } catch (\Exception $e) {
            // Log the actual error for debugging
            error_log('Registration error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());

            if ($isInertia) {
                $_SESSION['error'] = 'An error occurred during registration';
                redirect_back('/auth/register');
            }
            return [
                'status' => 'error',
                'message' => 'An error occurred during registration',
                'debug' => app('debug') ? $e->getMessage() : null
            ];
        }
    }
}
