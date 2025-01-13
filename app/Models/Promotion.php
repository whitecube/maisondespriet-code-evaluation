<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'percentage',  
        'category_id', 
        'type', 
    ];
    # Une date de début et de fin serait une amélioration possible
    # La possibilité de lier une promotion à plusieurs groupes client et aussi à plusieurs categories seraient de bonnes améliorations pour faciliter la configuration
    
    # On pourrait aussi réfléchir a faire des promotions avec un montant au lieu de pourcentage en ajoutant
    # une nouvelle colonne 'type' afin de préciser si la réduction est un pourcentage ou un montant. Il faudra renommer la colonne percentage par reduction pour plus de clarté
    
    public function category()
    {
        return $this->belongsTo(Category::class)->nullable();
    }

    
    public function getClientTypeAttribute($value)
    {
        return $value ? ClientType::fromValue($value)->label() : null;
    }
}
