<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $table = 'teacher';

    protected $primaryKey = 'teacherID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'teacherID',
        'teacher_name',
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function observer()
    {
        return $this->hasOne(
            Observer::class,
            'teacherID',
            'teacherID'
        );
    }


    public function externalObserver()
    {
        return $this->hasOne(
            ExternalObserver::class,
            'teacherID',
            'teacherID'
        );
    }
}
