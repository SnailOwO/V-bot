<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    
    protected $table = 'permissions';
 
    protected $fillable = [];   // 目前不允许，自定义添加
}


