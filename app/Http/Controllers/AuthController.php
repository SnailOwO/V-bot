<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // login method
    public function login(Request $request) {
		//return $request->only(['username', 'password','method']);
        $rules = [
            'username'   => [
                'required'
                //'exists:Snail,SnailOwO',   //todo:判断用户名是否重复
            ],
            'password' => 'required|string|min:6|max:32',
            'method' => 'required|between:normal,code'
        ];
        //$params = $this->validate($request, $rules);
        $credentials  = $request->only(['username', 'password','method']);
        $login_method = $credentials['method'];
        unset($credentials['method']);
        //用户名、密码登录
        if($login_method == 'normal') {
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
        }
        //邀请码登录
        if($login_method == 'code') {   

        } 
		return response()->json([
			'msg' => 'success'
		]);
    }

    

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
