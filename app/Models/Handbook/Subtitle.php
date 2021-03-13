<?php

namespace App\Models\Handbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtitle extends Model
{
    use HasFactory;

    protected $fillable = [
    	'name',
    	'description',
    	'handbook_id'
    ];

    public function handbooks(){
    	return $this->belongsTo('App\Models\Handbook\Handbook');
    }

    public function imagen(){
        return $this->hasOne('App\Models\Handbook\Imagen');
    }
}
