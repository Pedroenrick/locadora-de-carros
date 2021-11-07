<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //autenticação (email e senha)
        $credenciais = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credenciais);

        if($token){
            return response()->json([
                'token' => $token
            ]);
        } else{
            return response()->json([
                'error' => 'Usuário ou senha inválidos'
            ], 403);
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'message' => 'Deslogado com sucesso'
        ]);
    }

    public function refresh()
    {
        $token = auth('api')->refresh(); //cliente encaminhe um jwt valido
        return response()->json([
            'token' => $token
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
