<?php

namespace App\Models\Handbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handbook extends Model
{
    use HasFactory;

    protected $fillable = [
    	'name',
    	'description',
    	'user_id'
    ];

    public function subtitles(){
        return $this->hasMany('App\Models\Handbook\Subtitle');
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }
}
