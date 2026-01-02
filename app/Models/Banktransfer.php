<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banktransfer extends Model
{
    protected $fillable = [
        'invoice_id',
        'order_id',
        'amount',
        'status',
        'receipt',
        'date', 
        'created_by',
    ];
}
