<?php
namespace App\Repositories;

use App\Model\Role;

class PermissionRepository {
     
    use BaseRepository;
    
    protected $model;

    public function __construct(Role $role) {
        $this->model = $role;
    }
}