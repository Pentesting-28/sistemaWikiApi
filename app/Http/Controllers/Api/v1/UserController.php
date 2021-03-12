<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::where('id', '<>', auth()->user()->id)->get();
            return response()->json([
                'message' => 'Lista de usuarios',
                'data'    => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'UserController.index.failed',
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
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|same:password',
                'role_id'  => 'integer|exists:roles,id'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $request['password'] = Hash::make($request->password);
            $user = User::create($request->all());
            if ($request->has('role_id')) {
                $user->roles()->sync($request->get('role_id'));
            }
            $user_rol = User::whereId($user->id)->with('roles')->first();
            return response()->json([
                'message' => 'Usuario creado con éxito',
                'data'    => $user_rol
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'    => 'UserController.store.failed',
                'message'  => $e->getMessage()
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
            if($id == auth()->user()->id){
                throw new Exception('Usuario no encontrado', 505);
            }
            $user_rol = User::with('roles')->findOrFail($id);
            return response()->json([
                "message" =>'Detalles de usuario',
                "data" => $user_rol
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'  => 'UserController.show.failed',
                'message'=> $e->getMessage()
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
            if($id == auth()->user()->id){
                throw new Exception('Usuario no encontrado', 505);
            }
            $validator = Validator::make($request->all(), [
                'name'    => 'required|string|max:255',
                'email'   => "required|string|email|max:255|unique:users,email,$id",
                'role_id' => 'integer|exists:roles,id'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $user = User::findOrFail($id);
            $user->update($request->all());
            if ($request->has('role_id')) {
                $user->roles()->sync($request->get('role_id'));
            }
            $user_rol = User::whereId($user->id)->with('roles')->first();
            return response()->json([
                'message' => 'Usuario actualizado con éxito',
                'data'    => $user_rol
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'    => 'UserController.update.failed',
                'message'  => $e->getMessage()
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
            if($id == auth()->user()->id){
                throw new Exception('Usuario no encontrado', 404);
            }
            $data = User::findOrFail($id);
            $data->delete();
            return response()->json([
                'message' => 'Usuario eliminado con éxito',
                'data'    => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'UserController.destroy.failed',
                'message' => $e->getMessage()
            ], 505);
        }
    }
}
