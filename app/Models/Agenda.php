<?php

namespace App\Models;

use App\Observers\AgendaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([AgendaObserver::class])]

class Agenda extends Model
{
    protected $guarded = [];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
