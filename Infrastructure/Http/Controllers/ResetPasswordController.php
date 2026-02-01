<?php

namespace Infrastructure\Http\Controllers;

/**
 * Reset Password Page Controller
 * Displays the reset password form using Inertia
 */
class ResetPasswordController
{
    public function show()
    {
        // Get token and email from query params
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        return inertia('ResetPassword', [
            'token' => $token,
            'email' => $email
        ]);
    }
}
