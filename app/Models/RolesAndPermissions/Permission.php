<?php

namespace App\Models\RolesAndPermissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Control de asignacion masiva de datos
     */
    protected $fillable = [
    	'name',
    	'slug',
    	'description'
    ];

    /**
     * Relaciones
     */

    //Rol relacionados con el permiso
    public function roles(){
        return $this->belongsToMany('App\Models\RolesAndPermissions\Role')->withTimestamps();
    }
}
