<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreCriteria extends Model
{
    use SoftDeletes;

    protected $table = 'pre_criteria';
    protected $primaryKey = 'criteriaID';
    public $timestamps = false;

    protected $fillable = [
        'sectionID',
        'criteria_label',
        'display_order',
        'status',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function section()
    {
        return $this->belongsTo(
            PreSection::class,
            'sectionID',
            'sectionID'
        );
    }

    public function scores()
    {
        return $this->hasMany(
            PreScore::class,
            'criteriaID',
            'criteriaID'
        );
    }
}