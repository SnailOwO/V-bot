<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\DefaultCodeLogin;
use App\Events\DefaultAccountLogin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // login method
    public function login(Request $request) {
        $credentials  = $request->only(['method']);
        $is_account = $credentials['method'];
        $rules = [
            'method' => 'required|boolean'
        ];
        $this->validate($request, $rules);
        // 根据前台用户的选择方式，触发对应的登录方式。
        return $is_account ? event(new DefaultAccountLogin($request)) : event(new DefaultCodeLogin($request));
        // //用户名、密码登录
        // if(!$login_method) {
        //     if (! $token = auth('api')->attempt($credentials)) {
        //         return response()->json(['error' => 'Unauthorized'], 401);
        //     }
        //     return $this->respondWithToken($token);
        // } else {   //邀请码登录

        // }
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
