<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostField extends Model
{
    protected $table = 'post_field';

    protected $primaryKey = 'fieldID';

    public $timestamps = false;

    protected $fillable = [
        'field_label',
        'field_type',
        'display_order',
        'is_required',
        'status',
        'sectionID',
    ];

    public function section()
    {
        return $this->belongsTo(
            PostSection::class,
            'sectionID',
            'sectionID'
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