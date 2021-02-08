<?php

namespace App\Http\Controllers\Api\v1\RoleAndPemission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolesAndPermissions\Role;
use App\Models\RolesAndPermissions\Permission;
use Illuminate\Support\Facades\Validator;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Role::with('permissions')->get();
            return response()->json([
                'message' => 'Lista de roles',
                'data' => $data  
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'  => 'role.index.failed',
                'message'=> $e->getMessage(),
            ], 505);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
