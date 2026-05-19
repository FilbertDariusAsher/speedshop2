<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'stock', 'price', 'harga_jual', 'has_invoice'];
}
