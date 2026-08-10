<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostForm extends Model
{
    protected $table = 'post_form';

    protected $primaryKey = 'formID';

    public $timestamps = false;

    protected $fillable = [
        'form_name',
        'instruction',
        'status',
        'staffid',
    ];

    public function admin()
    {
        return $this->belongsTo(
            StaffEdu::class,
            'staffid',
            'staffid'
        );
    }

    public function sections()
    {
        return $this->hasMany(
            PostSection::class,
            'formID',
            'formID'
        );
    }

    public function responses()
    {
        return $this->hasMany(
            PostResponse::class,
            'formID',
            'formID'
        );
    }
}