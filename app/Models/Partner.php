<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'uri',
        'exchange_type',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
