<?php
namespace App\Repositories;

use App\Model\RolePermission;

class RolePermissionRepository {
     
    use BaseRepository;
    
    protected $model;

    public function __construct(RolePermission $rolePermission) {
        $this->model = $rolePermission;
    }
}