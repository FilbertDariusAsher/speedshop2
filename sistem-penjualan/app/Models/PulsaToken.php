<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PulsaToken extends Model
{
    protected $table = 'pulsa_tokens';
    protected $fillable = ['customer_name', 'type', 'provider', 'phone_number', 'token_number', 'amount', 'price', 'transaction_date'];
}
