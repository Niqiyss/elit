<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observer extends Model
{
    protected $table = 'observer';

    protected $primaryKey = 'observer_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'observer_id',
        'teacherID',
        'start_date',
        'end_date',
        'status',
    ];


    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacherID',
            'teacherID'
        );
    }
}