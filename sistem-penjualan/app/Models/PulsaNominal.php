<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PulsaNominal extends Model
{
    protected $fillable = ['provider_id', 'nominal_amount', 'markup', 'active'];

    public function provider()
    {
        return $this->belongsTo(PulsaProvider::class, 'provider_id');
    }

    // Helper function untuk hitung harga final
    public function getTotalPrice()
    {
        return ($this->nominal_amount * 1000) + $this->markup;
    }
}
