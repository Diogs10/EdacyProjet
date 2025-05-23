<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleProf extends Model
{
    use HasFactory;

    public function module():BelongsTo{
        return $this->belongsTo(Module::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
