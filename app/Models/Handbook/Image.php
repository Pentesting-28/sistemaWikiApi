<?php

namespace App\Models\Handbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
    	'url',
    	'subtitle_id'
    ];

    public function subtitle(){
    	return $this->belongsTo('App\Models\Handbook\Subtitle');
    }
}
