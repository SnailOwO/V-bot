<?php
namespace App\Http\Controllers\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;

class PermissionController extends Controller
{

    protected $permission;

    protected $rolePermission;

    private $roleHasPermission = [
        'role_id' => '=',
        'permission_id' => '='
    ];

    private $param_ary = [
        'is_show' => '='
    ];

    public function __construct(
        PermissionRepository $permission,
        RolePermissionRepository $rolePermission
    ) {
        $this->permission = $permission;
        $this->rolePermission = $rolePermission;
    }

    // 权限列表
    public function permissionList(Request $request) {
        $whereAry = [];
        $param_ary = ['*'];
        if($request->has(['is_show'])) {
            $param = $request->only(['is_show']);
            $rules = [
                'is_show' => 'required|boolean'
            ];
            $result = customValidate($param, $rules);
            if ($result) {
                return failResponse($result);
            }
            $param_ary = ['id','pid','name'];
            $whereAry = changeWhereAry($this->menuAry, $param);
        }
        return $this->permission->getRecordByWhere($whereAry,$param_ary);
    }

    // 根据role id 查询具体的角色权限
    public function getRolePermission(Request $request) {
        $param_ary = $request->only(['role_id']);
        $rules = [
            'role_id' => 'required|Integer'
        ];
        $result = customValidate($param_ary, $rules);
        if ($result) {
            return failResponse($result);
        }
        return $this->rolePermission->getModel()->getRolePermission($param_ary['role_id'], ['id']);
    }

    // 新增、编辑角色权限
    // todo: 增加超级管理员自定义验证规则
    public function addOrEditPermission(Request $request) {
        $param_ary = $request->only(['role_id', 'permission_ary']);
        $rules = [
            'role_id' => 'required|Integer|exists:roles,id',
            'permission_ary.*' => 'required|Integer|exists:permissions,id'
        ];
        $result = customValidate($param_ary, $rules);
        if ($result) {
            return failResponse($result);
        }
        $role_has_permission = array_column($this->rolePermission->getModel()->getRolePermission($param_ary['role_id'], ['id']), 'id');
        $assemble_ary = array();
        if (empty($role_has_permission)) {   // 第一次设置
            $assemble_ary = $this->fillInsertData($param_ary['role_id'], $param_ary['permission_ary']);
            $this->rolePermission->insertBatch('role_has_permissions', $assemble_ary);
        } else {   // 已经设置过
            $diff_permission = array_diff($role_has_permission, $param_ary['permission_ary']);
            if (!empty($diff_permission)) {
                $add_ary = [];
                $delete_ary = [];
                foreach ($diff_permission as $val) {
                    if (array_search($val, $role_has_permission)) {
                        array_push($delete_ary,$val);
                    } else {
                        array_push($add_ary,$val);
                    }
                }
                unset($diff_permission);
                // 组装删除数组
                $whereAry = [];
                if (!empty($delete_ary)) {
                    $validate_ary = [
                        'role_id' => $param_ary['role_id'],
                        'permission_id' => $delete_ary
                    ];
                    $whereAry = changeWhereAry($param_ary, $this->roleHasPermission);
                }
                // 组装新增数组
                if (!empty($add_ary)) {
                    $assemble_ary = $this->fillInsertData($param_ary['role_id'], $param_ary['permission_ary']);
                }
                unset($param_ary);
                $this->rolePermission->getModel()->editRolePermission($whereAry, $assemble_ary);
            }
        }
        return customResponse(ts('custom.operateSuccess'), [], 201);
    }

    // 获取用户menu 
    public function getUserMenu(Request $request) {
        $param_ary = $request->only(['role_id']);
        $rules = [
            'role_id' => 'required|Integer|exists:roles,id',
        ];
        $result = customValidate($param_ary, $rules);
        if ($result) {
            return failResponse($result);
        }
        return $this->rolePermission->getModel()->getRoleMenu($param_ary['role_id']);
    }

    // 按旨拼装insert data (可以考虑封装成公用的)
    private function fillInsertData($role_id, $permission_ary) {
        $assemble_ary = [];
        foreach ($permission_ary as $val) {
            $assemble_ary[] = [
                'role_id' => $role_id,
                'permission_id' => $val
            ];
        }
        return $assemble_ary;
    }
}