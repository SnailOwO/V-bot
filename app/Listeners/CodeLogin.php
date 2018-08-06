<?php

namespace App\Listeners;

use App\Events\DefaultCodeLogin;
use Illuminate\Contracts\Queue\ShouldQueue;

class CodeLogin
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
    public function handle(DefaultCodeLogin $event)
    {
        $rules = [
            'code' => 'required|min:6',   //todo: 激活码位数尚未确定
        ];
        $params = $this->validate($event, $rules);
        return response()->json([
			'msg' => 'code success'
		]);
    }
}
