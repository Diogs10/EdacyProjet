<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semestre extends Model
{
    use HasFactory;

    public function cours():HasMany{
        return $this->hasMany(Cours::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
