<?php
namespace Features\Auth\Register;

class RegisterCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $passwordConfirmation,
        public bool $terms = false
    ) {}
}
