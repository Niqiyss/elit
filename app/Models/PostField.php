<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostField extends Model
{
    use SoftDeletes;

    protected $table = 'post_field';

    protected $primaryKey = 'fieldID';

    public $timestamps = false;

    protected $fillable = [
        'sectionID',
        'field_label',
        'field_type',
        'display_order',
        'is_required',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(
            PostSection::class,
            'sectionID',
            'sectionID'
        );
    }

    public function options()
    {
        return $this->hasMany(
            PostFieldOption::class,
            'fieldID',
            'fieldID'
        );
    }

    public function answers()
    {
        return $this->hasMany(
            PostAnswer::class,
            'fieldID',
            'fieldID'
        );
    }
}