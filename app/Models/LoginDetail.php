<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginDetail extends Model
{
    protected $fillable = [
        'user_id', 'ip', 'date', 'details', 'type', 'created_by'
    ];
    public function Getuser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->first();
    }
}
