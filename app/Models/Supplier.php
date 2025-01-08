<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
