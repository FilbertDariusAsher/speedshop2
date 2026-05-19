<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PulsaProvider extends Model
{
    protected $fillable = ['name', 'active'];

    public function nominals()
    {
        return $this->hasMany(PulsaNominal::class, 'provider_id');
    }
}
