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
        'create_at' => 'bw',
        'update_at' => 'bw',
    ];


    public function __construct(RoleRepository $roleRepository) {
        $this->roleRepository = $roleRepository;
    }

    public function getList(Request $request) {
        $data = $request->only(['name','create_at','update_at','page']);
        $rules = [
            'name' => 'nullable',
            'create_at.*' => 'nullable|date_format:Y-m-d H:i:s',
            'update_at.*' => 'nullable|date_format:Y-m-d H:i:s',
            'page' => 'required|integer',
        ];
        $list_result = customValidate($data,$rules);
        if($list_result) {
            return failResponse($list_result);
        }
        $count = 0;
        $whereAry = changeWhereAry($data,$this->roleList);
    }   
}