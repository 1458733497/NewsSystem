<?php
namespace Admin\Model;

use Think\Model;

/**
 * 权限控制模型，默认对应admin_permmission表
 */
class AdminPermissionModel extends Model
{

    public function addPermission($name, $url, $sort, $sec, $remark, $state, $avalid)
    {
        $data = array(
            'name'=>$name,
            'url'=>$url,
            'sort'=>$sort,
            'sec'=>$sec,
            'remark'=>$remark,
            'state'=>$state,
            'avalid'=>$avalid,
            'addtime'=>date("Y-m-d G:i:s")
        );
        $result = $this->add($data);
        return $result;
    }

    public function updatePermission($name, $url, $sort, $sec, $remark, $state, $avalid, $id)
    {
        $permissionInfo = $this->getPermissionById($id);
        if ($permissionInfo) {
            $permissionInfo['name'] = $name;
            $permissionInfo['url'] = $url;
            $permissionInfo['sort'] = $sort;
            $permissionInfo['sec'] = $sec;
            $permissionInfo['remark'] = $remark;
            $permissionInfo['state'] = $state;
            $permissionInfo['avalid'] = $avalid;
            if ($this->save($permissionInfo) !== false) {
                return "ok";
            } else {
                return "更新系统权限发生错误";
            }
        } else {
            return "当前系统权限不存在";
        }
    }
    
    public function switchPermission($id, $state)
    {
        $permissionInfo = $this->getPermissionById($id);
        if ($permissionInfo) {
            $permissionInfo['state']=$state;
            if ($this->save($permissionInfo)) {
                return $permissionInfo;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getPermissionCount($condition)
    {
        return $this->count($condition);
    }

    public function getPermissionPage($conditon, $startIndex, $count)
    {
         return $this->order('addtime desc')
                    ->limit("$startIndex,$count")
                    ->findAll($conditon);
    }

    public function delPermissionById($id)
    {
        return $this->deleteById($id);
    }

    public function delPermissionByIdList($idlist)
    {
        foreach ($idlist as $pid) {
            $this->delPermissionById($pid);
        }
        return true;
    }

    public function getPermissionBySort($sortID)
    {
        return $this->where(array('sort'=>$sortID))->select();
    }

    public function getPermissionByUrl($url)
    {
        return $this->where(array('url'=>$url), "*")->select();
    }

    public function getPermissionsBySec($sec, $userperms)
    {
        $permlist= $this->where(array('sec'=>$sec, 'state'=>1, 'avalid'=>1))->select();
        foreach ($permlist as $key => $val) {
            if (mb_strpos(',' . $userperms . ',', ',' . $val['id'] . ',', 0, 'utf-8')===false) {
                unset($permlist[$key]);
            }
        }
        return $permlist;
    }

    /**
     * 通过ID取得对应权限情报
     * @param int $id 权限ID
     * @return array 对应的权限信息
     */
    public function getPermissionById($id)
    {
        return $this->find($id);
    }
}
