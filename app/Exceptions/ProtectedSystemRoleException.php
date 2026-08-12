<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class ProtectedSystemRoleException extends Exception implements ShouldntReport
{
    public function __construct()
    {
        parent::__construct('System roles cannot be modified or deleted.');
    }
}
