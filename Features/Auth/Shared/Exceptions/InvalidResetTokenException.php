<?php
namespace Features\Auth\Shared\Exceptions;

class InvalidResetTokenException extends \Exception
{
    public function __construct(string $message = 'Invalid or expired password reset token')
    {
        parent::__construct($message);
    }
}
