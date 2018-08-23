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
}
