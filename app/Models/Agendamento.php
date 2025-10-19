<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $guarded = [];

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
