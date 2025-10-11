<?php

namespace App\Models;

use App\Observers\AgendamentoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([AgendamentoObserver::class])]
class Agendamento extends Model
{
    protected $guarded = [];
}
