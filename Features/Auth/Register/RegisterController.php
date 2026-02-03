<?php
namespace Features\Auth\Register;

use Features\Auth\Register\RegisterCommand;
use Features\Auth\Register\RegisterHandler;
use Features\Auth\Shared\Exceptions\UserAlreadyExistsException;
use Vireo\Framework\Validation\ValidationException;

class RegisterController
{
    private RegisterHandler $handler;

    public function __construct(RegisterHandler $handler)
    {
        $this->handler = $handler;
    }

    public function show()
    {
        return inertia('auth/pages/SignUp');
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
                // Flash success message
                flash_success($result['message'] ?? 'Registration successful! Please sign in to continue.');

                // Use 303 redirect for Inertia
                header('Location: /auth/signin', true, 303);
                exit;
            }

            // Handle JSON API request
            return [
                'status' => 'success',
                'data' => $result
            ];
            
        } catch (UserAlreadyExistsException $e) {
            if ($isInertia) {
                // Flash error message
                flash_error($e->getMessage());

                // Use 303 redirect back to signup page
                header('Location: /auth/signup', true, 303);
                exit;
            }
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        } catch (ValidationException $e) {
            if ($isInertia) {
                // Flash validation errors as a single error message
                $errorMessages = array_values($e->getErrors());
                $firstError = is_array($errorMessages[0]) ? $errorMessages[0][0] : $errorMessages[0];
                flash_error($firstError);

                // Also keep field-specific errors for form display
                inertia_errors($e->getErrors());
                inertia_old($params);

                // Use 303 redirect back to signup page
                header('Location: /auth/signup', true, 303);
                exit;
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
                // Flash error message
                flash_error('An error occurred during registration');

                // Use 303 redirect back to signup page
                header('Location: /auth/signup', true, 303);
                exit;
            }
            return [
                'status' => 'error',
                'message' => 'An error occurred during registration',
                'debug' => app('debug') ? $e->getMessage() : null
            ];
        }
    }
}
