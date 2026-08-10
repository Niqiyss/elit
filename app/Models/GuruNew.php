<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GuruNew extends Authenticatable
{
    use Notifiable;

    protected $table = 'guru_new';

    protected $primaryKey = 'gn_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'gn_id',
        'ic_number',
        'gn_name',
        'phone_number',
        'email',
        'marital_status',
        'gender',
        'address',
        'race',
        'appointed_date',
        'service_date',
        'role',
        'current_status',
        'hrID',
        'schoolID',
        'password',
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

    public function hr()
    {
        return $this->belongsTo(
            HRAdministrator::class,
            'hrID',
            'hrID'
        );
    }
}