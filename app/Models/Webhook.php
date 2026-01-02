<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'module',
        'url',
        'method',
        'created_by',
    ];

    public static $module = [
        'New Project' => 'New Project',
        'New Task' => 'New Task',
        'New Invoice' => 'New Invoice',
        'Task Stage Updated' => 'Task Stage Updated',
        'New Milestone' => 'New Milestone',
        'Milestone Status Updated' => 'Milestone Status Updated',
        'Invoice Status Updated' => 'Invoice Status Updated',
    ];

    public static $method = [
        'GET' => 'GET',
        'POST' => 'POST'
    ];
}
