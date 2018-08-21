<?php
namespace App\Http\Controllers\Role;

use App\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
      
class RoleController extends Controller {

    protected $roleRepository;

    private $roleList = [
        'name' => 'like',
        'created_at' => 'bw',
        'updated_at' => 'bw',
    ];

    public function __construct(RoleRepository $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    // show list
    public function getList(Request $request) {
        $data = $request->only(['name','created_at','updated_at','page']);
        $rules = [
            'name' => 'nullable',
            'created_at.*' => 'nullable|date_format:Y-m-d H:i:s',
            'updated_at.*' => 'nullable|date_format:Y-m-d H:i:s',
            'page' => 'required|integer',
        ];
        $list_result = customValidate($data,$rules);
        if($list_result) {
            return failResponse($list_result);
        }
        $operation_list = array(
            'count' => 0,
            'roleAry' => []
        );
        $search_key_ary = [
            'page' => $data['page'] ?? 1,
            'where' => [],
        ];
        $whereAry = changeWhereAry($data,$this->roleList);
        $search_key_ary['where'] = $whereAry;
        $count = $this->roleRepository->getRoleNum($search_key_ary);
        if($count) {
            $roleAry = $this->roleRepository->getRole($search_key_ary);
            $operation_list['count'] = $count;
            $operation_list['roleAry'] = $roleAry;
        }
        return $operation_list;
    }   

    // add
    public function addRole(Request $request) {
        $role_data = $request->only(['name']);
        $rules = [
            'name' => 'required|unique:roles,name'
        ];
        $add_result = customValidate($role_data,$rules);
        if($add_result) {
            return failResponse($add_result);
        }
        $role_data['guard_name']= 'api';
        $insert_data = $this->roleRepository->create($role_data);
        unset($role_data);
        return customResponse(ts('custom.operateSuccess'),$insert_data,201);
    }

    // edit
    public function editRole(Request $request) {
        $role_data = $request->only(['id','name']);
        $rules = [
            'id' => 'required|Integer',
            'name' => 'required|unique:roles,name'
        ];
        $add_result = customValidate($role_data,$rules);
        if($add_result) {
            return failResponse($add_result);
        }
        $id = $role_data['id'];
        unset($role_data['id']);
        $this->roleRepository->update($id,$role_data);
        return customResponse(ts('custom.operateSuccess'),$role_data,201);
    }

    // delete
    public function delRole(Request $request) {
        $role_data = $request->only(['ids']);
        $rules = [
            'ids.*' => 'required|Integer',
        ];
        $add_result = customValidate($role_data,$rules);
        if($add_result) {
            return failResponse($add_result);
        }
        $this->roleRepository->destroyByIds($role_data['ids']);
        return customResponse(ts('custom.operateSuccess'),[],204);
    }
}