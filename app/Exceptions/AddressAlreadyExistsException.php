<?php

namespace App\Exceptions;

use Exception;

class AddressAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('An address record already exists for this demographic.');
    }
}
