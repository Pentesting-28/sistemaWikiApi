<?php

namespace App\Models\RolesAndPermissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Control de asignacion masiva de datos
     */
    protected $fillable = [
    	'name',
    	'slug',
    	'description',
    	'full-access'
    ];

    /**
     * Relaciones
     */

    //Usuarios relacionados con el rol
    public function users(){
    	return $this->belongsToMany('App\Models\User')->withTimestamps();
    }

    //Un rol puede tener muchos permisos
    public function permissions(){
        return $this->belongsToMany('App\Models\RolesAndPermissions\Permission')->withTimestamps();
    }
}
