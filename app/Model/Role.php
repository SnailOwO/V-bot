<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $limit = 10;

    protected $fillable = [
        'name',
        'guard_name'
    ];
}
