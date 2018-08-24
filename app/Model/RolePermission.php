<?php
namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\QueryException;

class RolePermission extends Model {

    protected $table = 'role_has_permissions';

    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    public function deleteWhere($role_whereAry) {
        return DB::table($this->table)
            ->where($role_whereAry)
            ->delete();
    }

    public function insertAll(array $data) {
        return DB::table($this->table)
            ->insert($data);
    }

    /* 编辑权限：
     * 1. 事务下，删除所有用户权限，在批量插入 
     * 2. 对比，多的新增，少的删除 (√)
     */
    public function editRolePermission($role_whereAry, array $data) {
        // 开启事务
        DB::beginTransaction();
        try {
            if (!empty($role_whereAry)) {
                // 删除
                $this->deleteWhere($role_whereAry);
            }
            if (!empty($data)) {
                  // 批量插入
                $this->insertAll($data);
            }
            // 提交事务
            DB::commit();
            return true;
        } catch (QueryException $e) {
            DB::rollBack();
            return false;
        }
    }

    // 联表查询出，role当前的权限
    public function getRolePermission($role_id, $param = array('*')) {
        return DB::table('permissions as p')
            ->rightJoin('role_has_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->where('rp.role_id', $role_id)
            ->get($param)
            ->toArray();
    }

    // menu list is_show：1
    public function getMenuList($role_id, $is_object = false) {
        $query = DB::table('permissions as p')
            ->leftJoin('role_has_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->select('p.*')
            ->where('rp.role_id', $role_id)
            ->where('p.is_show', 1);
        return $is_object ? $query : $query->get()
                                        ->toArray();
    }

    //  get pid:0 menu
    public function getParentPermission($role_id) {
        return DB::table('permissions as p')
            ->leftJoin('role_has_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->select('p.pid')
            ->where('rp.role_id', $role_id)
            ->groupBy('p.pid')
            ->get()
            ->toArray();
    }

    // 获取用户的role menu
    // ps:父级菜单若没有加入到permission表中，自动查询出来
    public function getRoleMenu($role_id) {
        // self menu 
        // is_show 1
        $self = $this->getMenuList($role_id,true);
        // parent menu
        $sub_query = DB::table('permissions as p')
            ->leftJoin('role_has_permissions as rp', 'p.id', '=', 'rp.permission_id')
            ->select('p.pid')
            ->where('rp.role_id', $role_id)
            ->groupBy('p.pid');

        return DB::table(DB::raw("({$sub_query->toSql()}) as has"))
            ->mergeBindings($sub_query)
            ->join('permissions as parent', 'parent.id', '=', 'has.pid')
            ->select('parent.*')
            ->union($self)
            ->get()
            ->toArray();
    }
}
