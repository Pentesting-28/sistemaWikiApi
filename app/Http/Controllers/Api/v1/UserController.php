<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
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
            $data = User::where('id', '<>', Auth::id())->get();
            return response()->json([
                'message' => 'Lista de usuarios',
                'data'    => $data
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'users.index.failed',
                'message'=> $e->getMessage(),
            ],500);
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
                'password_confirmation' => 'required|same:password'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'error'=>$validator->errors()
                ], 422);
            }
            $request['password'] = Hash::make($request->password);
            $data = User::create($request->all());
            return response()->json([
                'message' => 'Usuario creado con éxito',
                'data'    => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'    => 'user.store.failed',
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
            if($id == Auth::id()){
                throw new Exception('Usuario no encontrado', 505);
            }
            $data = User::findOrFail($id);
            return response()->json([
                "message" =>'Detalles de usuario',
                "data" => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'  => 'users.show.failed',
                'message'=> $e->getMessage(),
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
            if($id == Auth::id()){
                throw new Exception('Usuario no encontrado', 505);
            }
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'email' => "required|string|email|max:255|unique:users,email,$id"
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'error'=>$validator->errors()
                ], 422);
            }
            $data = User::findOrFail($id);
            $data->update($request->all());
            return response()->json([
                'message' => 'Usuario actualizado con éxito',
                'data'    => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'    => 'user.update.failed',
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
            if($id == Auth::id()){
                throw new Exception('Usuario no encontrado', 505);
            }
            $data = User::findOrFail($id);
            $data->delete();
            return response()->json([
                'message' => 'Usuario eliminado con éxito',
                'data'    => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'user.destroy.failed',
                'message' => $e->getMessage()
            ], 505);
        }
    }
}
