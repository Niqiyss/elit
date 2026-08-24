<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreResponse extends Model
{
    protected $table = 'pre_response';
    protected $primaryKey = 'responseID';

    // Only pre_response has created_at and updated_at
    public $timestamps = true;

    protected $fillable = [
        'formID',
        'gn_id',
        'observer_id',
        'observation_stage',
        'class_name',
        'subject_name',
        'observation_date',
        'total_score',
        'percentage',
        'achievement_level',
        'other_comment',
        'status',
    ];


    // Form used for this response
    public function form()
    {
        return $this->belongsTo(
            PreForm::class,
            'formID',
            'formID'
        );
    }


    // New teacher being observed
    public function guruNew()
    {
        return $this->belongsTo(
            GuruNew::class,
            'gn_id',
            'gn_id'
        );
    }


    // Observer who completed the form
    public function observer()
    {
        return $this->belongsTo(
            Observer::class,
            'observer_id',
            'observer_id'
        );
    }


    // Scores for every criteria
    public function scores()
    {
        return $this->hasMany(
            PreScore::class,
            'responseID',
            'responseID'
        );
    }


    // One ULASAN for each section
    public function sectionComments()
    {
        return $this->hasMany(
            PreSectionComment::class,
            'responseID',
            'responseID'
        );
    }
}