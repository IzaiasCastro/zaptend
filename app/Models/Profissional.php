<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $guarded = [];

    public function agenda()
    {
        return $this->hasOne(Agenda::class);
    }
}
