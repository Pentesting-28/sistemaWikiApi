<?php

namespace App\Http\Controllers\Api\v1\RoleAndPemission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolesAndPermissions\{Role,Permission};
use Validator;
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
            $roles = Role::with('permissions')->get();
            return response()->json([
                'message' => 'Lista de roles',
                'data' => $roles  
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'  => 'RoleController.index.failed',
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
        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|min:5|max:255|unique:roles,name',
                'slug'        => 'required|min:5|max:255|unique:roles,slug',
                'description' => 'required|min:5|max:255',
                'full-access' => 'required|in:yes,no',
                'permission.*' => 'integer|exists:permissions,id'
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> $validator->errors()], 422);
            }
            $rol = Role::create($request->all());
            if ($request->has('permission')) {
                $rol->permissions()->sync($request->get('permission'));
            }
            $rol_permission = Role::whereId($rol->id)->with('permissions')->first();
            return response()->json([
                'message' => 'Rol creado con éxito',
                'data' =>  $rol_permission
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'RoleController.store.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $rol_show = Role::with('permissions')->findOrFail($id);
            return response()->json([
                'message' => 'Detalles del rol',
                'data' => $rol_show
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'RoleController.show.failed',
                'error' => $e->getMessage()
            ], 505);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'name'         => "required|min:5|max:255|unique:roles,name,$id",
                'slug'         => "required|min:5|max:255|unique:roles,slug,$id",
                'description'  => 'required|min:5|max:255',
                'full-access'  => 'required|in:yes,no',
                'permission.*' => 'integer|exists:permissions,id'
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=> $validator->errors()], 422);
            }
            $rol = Role::findOrFail($id);
            $rol->update($request->all());
            if ($request->has('permission')) {
                $rol->permissions()->sync($request->get('permission'));
            }
            $rol_permission = Role::whereId($rol->id)->with('permissions')->first();
            return response()->json([
                'message' => 'Rol actualizado con éxito',
                'data' =>  $rol_permission
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'RoleController.update.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rol_destroy = Role::findOrFail($id);
            $rol_destroy->delete();
            return response()->json([
                'message' => 'Rol eliminado con éxito',
                'data' => $rol_destroy
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'RoleController.destroy.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }
}
