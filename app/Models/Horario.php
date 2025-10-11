<?php

namespace App\Models;

use App\Observers\HorarioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([HorarioObserver::class])]
class Horario extends Model
{
    protected $guarded = [];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
