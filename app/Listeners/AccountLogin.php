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
            'back' => true,
            'msg' => '',
            'code' => 422,
            'token' => '',
            'user_info' => array()
        );
        $rules = [
            'username'   => 'required',
            'password' => 'required|string|min:6|max:32',
        ];
        $credentials  = $event->request->only(['username', 'password']);
        $validate_result = customValidate($credentials, $rules);
        if($validate_result) {
            $response_ary['msg'] = $validate_result;
            return $response_ary;
        }
        if ($token = auth('api')->attempt($credentials)) {
            // 判断身份，游客身份需要审核
            $user_info = auth()->user();
            if(!$user_info['role']) {
                $response_ary['msg'] = ts('custom.needToVerifyIdentity');
                return $response_ary;
            }
            // 是否加入黑名单
            // 是否在允许的IP白名单中
            $response_ary['back'] = false;
            $response_ary['msg'] = ts('custom.loginSuccess');
            $response_ary['user_info'] = $user_info;
            return $response_ary;
        } else {
            $response_ary['msg'] = ts('custom.loginFailed');
            $response_ary['code'] = 401;
            $response_ary['token'] = $token;
        }
        return $response_ary;
    }
}
