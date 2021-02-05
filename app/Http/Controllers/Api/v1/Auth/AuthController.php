<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;

class AuthController extends Controller
{

    //Start of session
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'       => 'required|string|email',
                'password'    => 'required|string|min:6',
                'remember_me' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error'=>$validator->errors()
                ], 422);
            }

            $credentials = ['email' => $request->email, 'password' => $request->password];
            if (!Auth::attempt($credentials)) {
                //El attempt método regresará true si la autenticación fue exitosa, de lo contrario, false será devuelto.
                return response()->json(['error'=>'No autorizado'], 401);
            }

            $user = Auth::user();
            $success = [
                "token" => $user->createToken('sistemaWiki')->accessToken,
                "user"  => $user
            ];
            return response()->json([
                "message" => 'Inicio de sesión con éxito',
                'data' => $success,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error'  => 'auth.login.failed',
                'message'=> $e->getMessage(),
            ], 505);
        }
    }

    //user Information
    // public function userInformation()
    // {
    //     try {
    //         $user = Auth::user();
    //         $success = [
    //             "user"  => $user->where('id', $user->id)->with('role.permissions')->first(),
    //         ];
    //         return response()->json([
    //             "message" => 'Informacion de usuario',
    //             'data' => $success,
    //         ], 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error'  => 'auth.login.failed',
    //             'message'=> $e->getMessage(),
    //         ], 505);
    //     }
    // }

    //Session closure
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                "message" => 'Cierre de sesión con éxito',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'  => 'auth.logout.failed',
                'message'=> $e->getMessage(),
            ], 505);
        }
    }
}
