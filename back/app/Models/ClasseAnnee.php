<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasseAnnee extends Model
{
    use HasFactory;

    public function annee():BelongsTo{
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function classe():BelongsTo{
        return $this->belongsTo(Classe::class);
    }
}
