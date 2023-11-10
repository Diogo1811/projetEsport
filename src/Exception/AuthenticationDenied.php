<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationDenied extends AuthenticationException
{

    // this is the function that will to send a message
    public function getFailureMessage()
    {
        return 'Authentication error message.';
    }
}