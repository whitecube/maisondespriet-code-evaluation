<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Whitecube\Sluggable\HasSlug;

class Product extends Model
{
    use SoftDeletes, HasSlug;

    public $sluggable = 'name';

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}