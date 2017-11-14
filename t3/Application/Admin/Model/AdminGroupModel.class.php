<?php
namespace Admin\Model;

use Think\Model;

/**
 * 管理组模型，对应admin_group表
 */
class AdminGroupModel extends Model
{
    public function getGroupByName($name)
    {
        return $this->find(array('name'=>$name));
    }
    
    public function getGroupById($gid)
    {
        return $this->find($gid);
    }

    public function getGroupCount($condition)
    {
        return $this->count($condition);
    }

    public function getGroupPage($conditon, $startIndex, $count)
    {
        return $this->order('addtime desc')
                    ->limit("$startIndex,$count")
                    ->findAll($conditon);
    }

    public function addGroup($name, $ip, $remark, $state, $supereditor)
    {
            $now = date("Y-m-d G:i:s");
            $data = array(
                            'name'=>$name,
                            'iplimit'=>$ip,
                            'remark'=>$remark,
                            'state'=>$state,
                            'addtime'=>$now,
                            'permissions'=>'',
                            'supereditor'=>$supereditor,
                        );
            $result = $this->add($data);
            return $result;
    }

    public function updateGroup($id, $name, $ip, $remark, $state, $supereditor)
    {
        $groupInfo = $this->getGroupById($id);
        if ($groupInfo) {
            $groupInfo['name']=$name;
            $groupInfo['iplimit']=$ip;
            $groupInfo['remark']=$remark;
            $groupInfo['state']=$state;
            $groupInfo['supereditor']=$supereditor;
            if ($this->save($groupInfo)) {
                return "ok";
            } else {
                return "更新用户组发生错误";
            }
        } else {
            return "当前用户组不存在";
        }
    }

    public function switchGroup($id, $state)
    {
        $groupInfo = $this->getGroupById($id);
        if ($groupInfo) {
            $groupInfo['state']=$state;
            if ($this->save($groupInfo)) {
                return $groupInfo;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function updatePermsByGroupID($id, $perms)
    {
        $groupInfo = $this->getGroupById($id);
        if ($groupInfo) {
            $groupInfo['permissions'] = $perms;
            if ($this->save($groupInfo)) {
                return $groupInfo;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getGroupList()
    {
        return $this->findAll(array('state'=>1));
    }

    public function getCurrentGroupId($groupArray)
    {
        //计数完，更新资源表group及check字段
        //找到当前用户组范围内的所有记录集合
        $groupInfo = $this->first(array('count'=>0,'id'=>array('in',$groupArray),'state'=>1));
        if ($groupInfo) {
            //依次判断是否都为零，如果有零存在，则从ID最小的地方开始计数
            $groupInfo['count']=1;
            if ($this->save($groupInfo)) {
                return $groupInfo['id'];
            } else {
                return -1;
            }
        } else {
            //如果没有零存在，则全部更新为零，然后在从ID最小的地方开始计数
            $ret = $this->setField('count', 0, array('id'=>array('in',$groupArray),'state'=>1));
            if ($ret) {
                $groupInfo = $this->first(array('count'=>0,'id'=>array('in',$groupArray),'state'=>1));
                if ($groupInfo) {
                    $groupInfo['count']=1;
                    if ($this->save($groupInfo)) {
                        return $groupInfo['id'];
                    } else {
                        return -2;
                    }
                } else {
                    return -3;
                }
            } else {
                return 0;
            }
        }
    }
}
