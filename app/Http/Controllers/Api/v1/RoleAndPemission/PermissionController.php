<?php

namespace App\Http\Controllers\Api\v1\RoleAndPemission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolesAndPermissions\Permission;
use Exception;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $permission = Permission::all();
            return response()->json([
                'message' => 'Lista de permisos',
                'data' => $permission
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'PermissionController.index.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }
}
