<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;
    public function classeAnnee():BelongsToMany{
        return $this->belongsToMany(ClasseAnnee::class,'classe_annee_cours');
    }

    public function moduleProf():BelongsTo{
        return $this->belongsTo(ModuleProf::class);
    }

    protected static function booted()
    {
        static::created(function(Cours $classe){
            $classe->classeAnnee()->attach(request()->classes);
        });
    }

    public function semestre():BelongsTo{
        return $this->belongsTo(Semestre::class);
    }

    public function session() :HasMany {
        return $this->hasMany(Session::class);
    }
}
