<?php
namespace App\Repositories;

use App\Model\Permission;

class PermissionRepository {
     
    use BaseRepository;
    
    protected $model;

    public function __construct(Permission $permission) {
        $this->model = $permission;
    }
}