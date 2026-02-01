<?php
namespace Features\Auth\ResetPassword;

class ResetPasswordCommand
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
        public string $passwordConfirmation
    ) {}
}
