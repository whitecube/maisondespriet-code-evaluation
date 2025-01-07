<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Whitecube\Sluggable\HasSlug;

class Category extends Model
{
    use SoftDeletes, HasSlug;

    public $sluggable = 'name';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
