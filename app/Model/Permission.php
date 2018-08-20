<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name'
    ];
}
