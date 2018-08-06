<?php
namespace App\Events;

class DefaultAccountLogin
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
}