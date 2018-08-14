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
        $query = $this->model->orderBy('created_at', 'desc');
        $start = max(0, (intval($search_key_ary['page']) - 1));
        $pages = $start * $this->limit;
        $query->take($this->limit)->skip($pages);
        if(!empty($search_key_ary)) {
            if(isset($search_key_ary['name'])) {
                $query->where('name','like','%'. $search_key_ary['name'] .'%');
            }    
            if(isset($search_key_ary['created_at'])) {
                if(count($search_key_ary['created_at']) == 1) {
                    $query->where('created_at',$search_key_ary['created_at']);
                }
                if(count($search_key_ary['created_at']) > 1) {
                    $query->whereBetween('created_at',$search_key_ary['created_at']);
                }
            } 
            if(isset($search_key_ary['update_at'])) {
                if(count($search_key_ary['update_at']) == 1) {
                    $query->where('update_at',$search_key_ary['update_at']);
                }
                if(count($search_key_ary['update_at']) == 2) {
                    $query->whereBetween('update_at',$search_key_ary['update_at']);
                }
            } 
        }   
        //orm 层返回的是collect对象
		return $query->get()
        ->toArray();
    }

    public function getRoleNum($search_key_ary = array()) {
        $query = $this->model->orderBy('created_at', 'desc');
        if(!empty($search_key_ary)) {
            if(isset($search_key_ary['name'])) {
                $query->where('name','like','%'. $search_key_ary['name'] .'%');
            }    
            if(isset($search_key_ary['created_at'])) {
                if(count($search_key_ary['created_at']) == 1) {
                    $query->where('created_at',$search_key_ary['created_at']);
                }
                if(count($search_key_ary['created_at']) > 1) {
                    $query->whereBetween('created_at',$search_key_ary['created_at']);
                }
            } 
            if(isset($search_key_ary['update_at'])) {
                if(count($search_key_ary['update_at']) == 1) {
                    $query->where('update_at',$search_key_ary['update_at']);
                }
                if(count($search_key_ary['update_at']) == 2) {
                    $query->whereBetween('update_at',$search_key_ary['update_at']);
                }
            } 
        }   
		return $query->count();
    }
}