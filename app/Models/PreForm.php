<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreForm extends Model
{
    protected $table = 'pre_form';
    protected $primaryKey = 'formID';
    public $timestamps = false;

    protected $fillable = [
        'form_name',
        'instruction',
        'status',
        'staffid',
    ];

    public function sections()
    {
        return $this->hasMany(
            PreSection::class,
            'formID',
            'formID'
        );
    }

    public function responses()
    {
        return $this->hasMany(
            PreResponse::class,
            'formID',
            'formID'
        );
    }
}