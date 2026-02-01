<?php

namespace Infrastructure\Http\Controllers;

/**
 * Forgot Password Page Controller
 * Displays the forgot password form using Inertia
 */
class ForgotPasswordController
{
    public function show()
    {
        return inertia('ForgotPassword');
    }
}
