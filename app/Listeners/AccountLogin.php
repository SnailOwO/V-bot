<?php

namespace App\Listeners;

use Illuminate\Http\Request;
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
        $rules = [
            'username'   => [
                'required'
                //'exists:Snail,SnailOwO',   //todo:判断用户名是否重复
            ],
            'password' => 'required|string|min:6|max:32',
        ];
        $this->validate($event->request, $rules);
        return response()->json([
			'msg' => 'account success'
		]);
    }
}
