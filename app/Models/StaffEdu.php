<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StaffEdu extends Authenticatable
{
    use Notifiable;

    protected $table = 'staff_edu';
    protected $primaryKey = 'staffid';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'staffid',
        'staffname',
        'email',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}