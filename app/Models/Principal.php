<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Principal extends Authenticatable
{
    use Notifiable;

    protected $table = 'principal';

    protected $primaryKey = 'principalID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'principalID',
        'principal_name',
        'ic_number',
        'phone_number',
        'email',
        'marital_status',
        'gender',
        'address',
        'race',
        'appointed_date',
        'service_date',
        'pension_date',
        'latest_age',
        'password',
        'role',
        'schoolID',
    ];

    protected $hidden = [
        'password',
    ];

    public function school()
    {
        return $this->belongsTo(
            School::class,
            'schoolID',
            'schoolID'
        );
    }
}
