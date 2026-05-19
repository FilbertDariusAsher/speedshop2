<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['customer_name', 'total', 'transaction_date'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
