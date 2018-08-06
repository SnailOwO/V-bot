<?php
namespace App\Events;

class DefaultCodeLogin
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
}
