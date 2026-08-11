<?php

namespace App\Models\Concerns;

use App\Models\Demographics\Demographic;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasDemographic
{
    public function demographic(): MorphOne
    {
        return $this->morphOne(Demographic::class, 'demographicable');
    }
}
