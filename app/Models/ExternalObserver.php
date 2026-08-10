<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalObserver extends Model
{
    protected $table = 'external_observer';

    protected $primaryKey = 'external_observer_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'external_observer_id',
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