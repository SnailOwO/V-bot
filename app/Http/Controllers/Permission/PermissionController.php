<?php
namespace App\Http\Controllers\Permission;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function addOrEditPermission(Request $request) {
        $param_ary = $request->only(['role_id','permission_ary']);
        $rules = [
            'role_id' => [
                'required',
                'Integer',
                'exists:roles,id'
            ],
            'permission_ary.*' => 'required|Integer'
        ];
        $result = customValidate($param_ary,$rules);
        if($result) {
            return failResponse($result);
        }
        $whereAry = changeWhereAry($param_ary,$this->roleHasPermission);
        $role_has_permission = $this->rolePermission->getRecordByWhere($whereAry);
        // 组装批量插入的数据数组
        //$assemble_ary = array_flip(array_fill_keys($param_ary['permission_ary'],'permission_id'));
        $assemble_ary = array();
        foreach($param_ary['permission_ary'] as $val) {
            $assemble_ary[] = [
                'role_id' => $param_ary['role_id'],
                'permission_id' => $val
            ];
        }
        if(empty($role_has_permission)) {   // 第一次设置
            $this->rolePermission->insertBatch('role_has_permissions',$assemble_ary); 
        } else {   // 已经设置过
           $this->rolePermission->getThisModel()->editRolePermission($whereAry,$assemble_ary);
        }
        return customResponse(ts('custom.operateSuccess'),[],201);  
    }
}