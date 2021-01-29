<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'role_id' => 'required',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password)
            ]);

        $user->save();

            $user->company()->sync($request->company_id);
            $user->permission()->sync($request->permissions);

        $user_new = User::where('id', $user->id)->with('role','company','permission')->first();

            return response()->json([
                'data' => $user_new,
                'message' => 'Registro Exitoso'
        ], 201);
    }

    //Register
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'             => 'required|string',
                'email'            => 'required|string|email|unique:users',
                'password'         => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error'=>$validator->errors()
                ], 422);
            }

            $user           = new User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->email_verified_at = now();
            $user->password = Hash::make($request->password);
            $user->save();

            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            $user = Auth::user();
            $user->role()->attach($request->role_id);

            $success = [
                "token" => $user->createToken('sistemacontable')->accessToken,
                "user"  => $user->where('id', $user->id)->with('role.permissions')->first(),// User::where('id',$user->id)->with('role.permissions')->get(),
            ];

            return response()->json([
                "message" =>'Registrado con éxito',
                "data" => $success,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error'  => 'auth.signup.failed',
                'message'=> $e->getMessage(),
            ], 505);
        }
    }
    
    //El attempt método regresará true si la autenticación fue exitosa, de lo contrario, false será devuelto.

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
}
