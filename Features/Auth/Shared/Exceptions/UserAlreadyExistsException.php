<?php
namespace Features\Auth\Shared\Exceptions;

class UserAlreadyExistsException extends \Exception
{
    public function __construct(string $message = 'User with this email already exists')
    {
        parent::__construct($message);
    }
}
