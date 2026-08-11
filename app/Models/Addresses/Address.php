<?php

namespace App\Models\Addresses;

use App\Models\Demographics\Demographic;
use Database\Factories\Addresses\AddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

    public function demographic(): BelongsTo
    {
        return $this->belongsTo(Demographic::class);
    }
}
