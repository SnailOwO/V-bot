<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\DefaultCodeLogin;
use App\Events\DefaultAccountLogin;

class AuthController extends Controller {

    /**
     * login method
     * 
     * @param  Illuminate\Http\Request $request
     */
    public function login(Request $request) {
        $credentials  = $request->only(['method']);
        $is_account = $credentials['method'];
        $rules = [
            'method' => 'required|boolean'
        ];
        $method_result = customValidate($request->only(['method']),$rules);
        if($method_result) {
            return failResponse($method_result);
        }
        // 根据前台用户的选择方式，触发对应的登录方式。   
        $login_result = $is_account ? event(new DefaultAccountLogin($request)) : event(new DefaultCodeLogin($request));
        $login_result = current($login_result);   //事件返回的是数组
        if($login_result['back']) {
            return customResponse($login_result['msg'],array(),$login_result['code']);
        }
        return customResponse('Login Success',
                [
                    'token' => $login_result['data'],
                    'user_info' => ''
                ]
        );
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
