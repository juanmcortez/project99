<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class PhoneLimitReachedException extends Exception implements ShouldntReport
{
    public function __construct()
    {
        parent::__construct('A demographic record cannot have more than two phone numbers.');
    }
}
