<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class AddressAlreadyExistsException extends Exception implements ShouldntReport
{
    public function __construct()
    {
        parent::__construct('An address record already exists for this demographic.');
    }
}
