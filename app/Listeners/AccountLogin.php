<?php

namespace App\Listeners;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
use App\Events\DefaultAccountLogin;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(DefaultAccountLogin $event)
    {
        $response_ary = array(
            'back' => false,
            'msg' => '',
            'code' => 422,
            'data' => ''
        );
        $rules = [
            'username'   => [
                'required'
                //'exists:Snail,SnailOwO',   //todo:判断用户名是否重复
            ],
            'password' => 'required|string|min:6|max:32',
        ];
        $credentials  = $event->request->only(['username', 'password']);
        $validate_result = customValidate($credentials, $rules);
        if($validate_result) {
            $response_ary['back'] = true;
            $response_ary['msg'] = $validate_result;
            return $response_ary;
        }
        if (!$token = auth('api')->attempt($credentials)) {
            $response_ary['back'] = true;
            $response_ary['msg'] = ts('custom.loginFailed');
            $response_ary['code'] = 401;
            return $response_ary;
        }
        $response_ary['data'] = $token;
        return $response_ary;
    }
}
