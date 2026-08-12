<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class DemographicAlreadyExistsException extends Exception implements ShouldntReport
{
    public function __construct()
    {
        parent::__construct('A demographic record already exists for this model.');
    }
}
