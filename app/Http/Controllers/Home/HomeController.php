<?php

namespace App\Http\Controllers\Home;

use Redis;
//use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

    private $name = 'wang';

    public function index() {
        // echo 'Hola,' . $this->name;
        // return view('child');
        //Redis::set('name','kola');
        echo Redis::get('name');
    }

    // public function create() {
    //     echo __METHOD__;
    // }

    // public function store() {
    //     echo __METHOD__;
    // }

    // public function show($id) {
    //     echo $id;
    // }

    // public  function edit() {
    //     echo __METHOD__;
    // }

    // public function update() {
    //     echo __METHOD__;
    // }

    // public function destory() {
    //     echo __METHOD__;
    // }


}
