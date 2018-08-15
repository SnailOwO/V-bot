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
}