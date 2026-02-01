<?php
namespace Features\Auth\ForgotPassword;

class ForgotPasswordCommand
{
    public function __construct(
        public string $email
    ) {}
}
