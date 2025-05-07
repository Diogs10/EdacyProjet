<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{

    use HasFactory;
    
    public function annee():HasMany {
        return $this->hasMany(AnneeScolaire::class);
    }
}