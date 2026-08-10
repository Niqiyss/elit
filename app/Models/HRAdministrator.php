<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HRAdministrator extends Authenticatable
{
    use Notifiable;

    protected $table = 'hr_administrator';
    protected $primaryKey = 'hrID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'hrID',
        'hrname',
        'email',
        'password',
        'phone_number',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}