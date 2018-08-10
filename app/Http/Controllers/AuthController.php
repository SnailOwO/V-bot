<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Events\DefaultCodeLogin;
use App\Events\DefaultAccountLogin;
use App\Repositories\UserRepository;

class AuthController extends Controller {

    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

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
        // 事件返回的是数组
        $login_result = current($login_result);   
        if($login_result['back']) {
            return customResponse($login_result['msg'],array(),$login_result['code']);
        }
        // 登录成功后，返回token + user info
        return customResponse($login_result['msg'],[
                    'token' => $login_result['token'],
                    'user_info' => json_encode($login_result['user_info'])
                ]
        );
    }

    //todo: 验证是否需要销毁token

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

    // register 后统一都是游客身份，需要管理员审核
    public function register(Request $request) {  
        $data = $request->all();
        $rules = [
            'username' => 'required|max:32|unique:user,username',
            'password' => 'required|confirmed|regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,}$/',
            'password_confirmation' => 'required',
            'email' => 'required|email|unique:user,email',
            'extra.phone' => 'nullable|regex:/^1\d{10}$/',
            'extra.inviteCode' => 'nullable',
        ];
        $method_result = customValidate($data,$rules);
        if($method_result) {
            return failResponse($method_result);
        }
        $data['ip'] = $request->getClientIp();
        //开始插入数据库
        if(!$this->create($data)) {
            return failResponse(ts('custom.registerFailed'));
        }
        return customResponse(ts('custom.registerSuccess')); 
    }

    protected function create(array $data) {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => empty($data['extra']['phone']) ? '' : $data['extra']['phone'],
            'custom_account' => 1,
            'ip' => $data['ip']
        ]);
    }
}
