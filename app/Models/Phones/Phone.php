<?php

namespace App\Models\Phones;

use App\Enums\PhoneType;
use App\Models\Demographics\Demographic;
use Database\Factories\Phones\PhoneFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'phone_number',
    'type',
])]
class Phone extends Model
{
    /** @use HasFactory<PhoneFactory> */
    use HasFactory, SoftDeletes;

    public function demographic(): BelongsTo
    {
        return $this->belongsTo(Demographic::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => PhoneType::class,
        ];
    }
}
