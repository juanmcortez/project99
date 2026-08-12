<?php

namespace App\Models\Addresses;

use App\Enums\Country;
use App\Models\Demographics\Demographic;
use Database\Factories\Addresses\AddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'street_line_1',
    'street_line_2',
    'city',
    'state',
    'zip_code',
    'country',
])]
class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory, SoftDeletes;

    public function demographic(): BelongsTo
    {
        return $this->belongsTo(Demographic::class);
    }

    public function getFormattedAttribute(): string
    {
        $cityState = trim(implode(', ', array_filter([$this->city, $this->state])));
        $cityStateZip = trim($cityState.($this->zip_code ? ' '.$this->zip_code : ''));

        $lines = array_filter([
            $this->street_line_1,
            $this->street_line_2,
            $cityStateZip !== '' ? $cityStateZip : null,
            $this->country?->label(),
        ]);

        return implode("\n", $lines);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'country' => Country::class,
        ];
    }
}
