<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenNominal extends Model
{
    protected $fillable = ['nominal_amount', 'harga_final', 'profit', 'active'];
}
