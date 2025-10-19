<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'date:Y-m-d',
    ];

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
