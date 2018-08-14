<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    private $limit = 10;

    protected $fillable = [
        'name',
        'guard_name'
    ];
}
