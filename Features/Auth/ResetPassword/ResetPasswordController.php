<?php
namespace Features\Auth\ResetPassword;

use Features\Auth\ResetPassword\ResetPasswordCommand;
use Features\Auth\ResetPassword\ResetPasswordHandler;
use Features\Auth\Shared\Exceptions\InvalidResetTokenException;
use Vireo\Framework\Validation\ValidationException;

class ResetPasswordController
{
    private ResetPasswordHandler $handler;

    public function __construct(ResetPasswordHandler $handler)
    {
        $this->handler = $handler;
    }

    public function show()
    {
        // Get token from query params if available
        $token = $_GET['token'] ?? null;
        return inertia('auth/pages/ChangePassword', ['token' => $token]);
    }

    public function reset(array $params)
    {
        $isInertia = isset($_SERVER['HTTP_X_INERTIA']) && $_SERVER['HTTP_X_INERTIA'] === 'true';

        try {
            // Validate input
            $validated = validate($params, [
                'email' => 'required|email|max:255',
                'token' => 'required|string',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password'
            ]);

            // Create command and delegate to handler
            $command = new ResetPasswordCommand(
                $validated['email'],
                $validated['token'],
                $validated['password'],
                $validated['password_confirmation']
            );

            $result = $this->handler->handle($command);

            // Handle Inertia request (from UI)
            if ($isInertia) {
                $_SESSION['success'] = $result['message'] ?? 'Password reset successfully!';
                redirect_to('/auth/signin');
            }

            // Handle JSON API request
            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (InvalidResetTokenException $e) {
            if ($isInertia) {
                $_SESSION['error'] = $e->getMessage();
                redirect_back('/auth/reset-password');
            }
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        } catch (ValidationException $e) {
            if ($isInertia) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['old'] = $params;
                redirect_back('/auth/reset-password');
            }
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->getErrors()
            ];
        } catch (\Exception $e) {
            if ($isInertia) {
                $_SESSION['error'] = 'An error occurred while resetting your password';
                redirect_back('/auth/reset-password');
            }
            return [
                'status' => 'error',
                'message' => 'An error occurred while resetting your password'
            ];
        }
    }
}
