<?php
namespace App\Http\Controllers\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;

class PermissionController extends Controller {

    protected $permission;

    protected $rolePermission;

    private $roleHasPermission = [
        'role_id' => '=',
    ];

    public function __construct(PermissionRepository $permission,
                                RolePermissionRepository $rolePermission) {
        $this->permission = $permission;
        $this->rolePermission = $rolePermission;
    }

    // 权限列表
    public function permissionList() {
        
    }

    // 根据role id 查询具体的角色
    public function getRolePermission(Request $request) {
        $param_ary = $request->only(['role_id']);
        $rules = [
            'role_id' => 'required|Integer'
        ];
        $result = customValidate($param_ary,$rules);
        if($result) {
            return failResponse($result);
        }
        $whereAry = changeWhereAry($param_ary,$this->roleHasPermission);
        $role_has_permission = $this->rolePermission->getRecordByWhere($whereAry);
        if(empty($role_has_permission)) {   // 说明,第一次设置角色权限
            $all_permission = $this->permission->all();
            return $all_permission;
        }
        // 已经设置过
        return $role_has_permission;
    }

    // 新增、编辑角色权限
    public function addOrEditPermission() {

    }
}