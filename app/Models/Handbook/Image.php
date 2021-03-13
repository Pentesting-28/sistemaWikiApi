<?php

namespace App\Models\Handbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
    	'url'
    ];

    public function subtitles(){
    	return $this->belongsTo('App\Models\Handbook\Subtitle');
    }
}
