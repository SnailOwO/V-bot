<?php
namespace App\Http\Controllers\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller {

    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }

    // 权限列表
    public function permissionList() {
        
    }

    // 根据role id 查询具体的角色
    public function getRolePermission() {

    }

    // 新增角色权限
    public function addRolePermission() {

    }

    // 编辑角色权限
    public function editRolePermission() {

    }
}