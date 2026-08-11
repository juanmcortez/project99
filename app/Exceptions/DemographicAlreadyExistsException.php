<?php

namespace App\Exceptions;

use Exception;

class DemographicAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('A demographic record already exists for this model.');
    }
}
