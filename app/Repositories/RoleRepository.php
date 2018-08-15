<?php
namespace App\Repositories;

use App\Model\Role;

class RoleRepository {
    
    use BaseRepository;
    
    protected $model;

    public function __construct(Role $role) {
        $this->model = $role;
    }

    public function getRole($search_key_ary = array()) {
        $query = $this->model->orderBy('id');
        $start = max(0, ($search_key_ary['page'] - 1));
        $pages = $start * $this->model->limit;
        $query->take($this->model->limit)->skip($pages);
        if(!empty($search_key_ary['where'])) {   //目前没有区分，当有or in 查询的时候，在修改
            $query->where($search_key_ary['where']);  
        }
        //orm 层返回的是collect对象
		return $query->get()
        ->toArray();
    }

    public function getRoleNum($search_key_ary = array()) {
        $query = $this->model->orderBy('id');
        if(!empty($search_key_ary['where'])) {
            $query->where($search_key_ary['where']);  
        }   
		return $query->count();
    }
}