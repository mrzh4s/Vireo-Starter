<?php
namespace Features\Auth\ForgotPassword;

use Features\Auth\ForgotPassword\ForgotPasswordCommand;
use Features\Auth\ForgotPassword\ForgotPasswordHandler;
use Vireo\Framework\Validation\ValidationException;

class ForgotPasswordController
{
    private ForgotPasswordHandler $handler;

    public function __construct(ForgotPasswordHandler $handler)
    {
        $this->handler = $handler;
    }

    public function show()
    {
        return inertia('auth/pages/ResetPassword');
    }

    public function sendResetLink(array $params)
    {
        $isInertia = isset($_SERVER['HTTP_X_INERTIA']) && $_SERVER['HTTP_X_INERTIA'] === 'true';

        try {
            // Validate input
            $validated = validate($params, [
                'email' => 'required|email|max:255'
            ]);

            // Create command and delegate to handler
            $command = new ForgotPasswordCommand($validated['email']);
            $result = $this->handler->handle($command);

            // Handle Inertia request (from UI)
            if ($isInertia) {
                $_SESSION['success'] = $result['message'] ?? 'Password reset link sent!';
                redirect_back('/auth/forgot-password');
            }

            // Handle JSON API request
            return [
                'status' => 'success',
                'data' => $result
            ];
        } catch (ValidationException $e) {
            if ($isInertia) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['old'] = $params;
                redirect_back('/auth/forgot-password');
            }
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->getErrors()
            ];
        } catch (\Exception $e) {
            if ($isInertia) {
                $_SESSION['error'] = 'An error occurred while processing your request';
                redirect_back('/auth/forgot-password');
            }
            return [
                'status' => 'error',
                'message' => 'An error occurred while processing your request'
            ];
        }
    }
}
