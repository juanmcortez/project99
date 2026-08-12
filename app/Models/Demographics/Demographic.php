<?php

namespace App\Models\Demographics;

use App\Enums\EducationLevel;
use App\Enums\EmploymentStatus;
use App\Enums\Ethnicity;
use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\MaritalStatus;
use App\Enums\Race;
use App\Models\Addresses\Address;
use App\Models\Phones\Phone;
use Database\Factories\Demographics\DemographicFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use HasFactory, SoftDeletes;

    public function demographicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ])));
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
            'gender' => Gender::class,
            'race' => Race::class,
            'ethnicity' => Ethnicity::class,
            'language' => Language::class,
            'marital_status' => MaritalStatus::class,
            'education_level' => EducationLevel::class,
            'employment_status' => EmploymentStatus::class,
        ];
    }
}
