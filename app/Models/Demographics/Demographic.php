<?php

namespace App\Models\Demographics;

use Database\Factories\Demographics\DemographicFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'first_name',
    'last_name',
    'middle_name',
    'birthdate',
    'profile_picture',
    'social_security',
    'gender',
    'race',
    'ethnicity',
    'language',
    'marital_status',
    'education_level',
    'employment_status',
    'occupation',
    'income',
])]
class Demographic extends Model
{
    /** @use HasFactory<DemographicFactory> */
    use HasFactory;

    public function demographicable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'social_security' => 'encrypted',
            'income' => 'decimal:2',
        ];
    }
}
