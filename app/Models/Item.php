<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'external_id',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
