<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'school';

    protected $primaryKey = 'schoolID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'schoolID',
        'school_name',
        'school_address',
        'register_date',
        'phone_number',
        'total_teacher',
        'vacancy',
        'capacity',
    ];

    public function guruNews()
    {
        return $this->hasMany(
            GuruNew::class,
            'schoolID',
            'schoolID'
        );
    }

    public function school()
    {
        return $this->belongsTo(
            School::class,
            'schoolID',
            'schoolID'
        );
    }
}