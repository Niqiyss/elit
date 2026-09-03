<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdpcResponse extends Model
{
    protected $table = 'pdpc_response';
    protected $primaryKey = 'responseID';

    protected $fillable = [
        'formID',
        'gn_id',
        'observer_id',
        'external_observer_id',
        'observation_stage',
        'attempt_no',
        'class_name',
        'subject_name',
        'observation_date',
        'observation_time',
        'total_score',
        'percentage',
        'achievement_level',
        'result',
        'status',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'percentage' => 'decimal:2',
    ];

    public function form()
    {
        return $this->belongsTo(
            PdpcForm::class,
            'formID',
            'formID'
        );
    }

    public function guruNew()
    {
        return $this->belongsTo(
            GuruNew::class,
            'gn_id',
            'gn_id'
        );
    }

    public function observer()
    {
        return $this->belongsTo(
            Observer::class,
            'observer_id',
            'observer_id'
        );
    }

    public function externalObserver()
    {
        return $this->belongsTo(
            ExternalObserver::class,
            'external_observer_id',
            'external_observer_id'
        );
    }

    public function scores()
    {
        return $this->hasMany(
            PdpcScore::class,
            'responseID',
            'responseID'
        );
    }
}