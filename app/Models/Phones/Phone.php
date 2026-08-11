<?php

namespace App\Models\Phones;

use App\Models\Demographics\Demographic;
use Database\Factories\Phones\PhoneFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'phone_number',
    'type',
])]
class Phone extends Model
{
    /** @use HasFactory<PhoneFactory> */
    use HasFactory;

    public function demographic(): BelongsTo
    {
        return $this->belongsTo(Demographic::class);
    }
}
