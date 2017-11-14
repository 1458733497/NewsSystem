<?php
namespace Admin\Model;

use Think\Model;

/**
 * 管理员模型，对应admin表
 */
class AdminModel extends Model
{

    //管理员登录检测
    public function login($name, $pass)
    {
        $ChkAdmin = $this->where(array('name'=>$name, 'password'=>md5($pass)))->select();
        return $ChkAdmin;
    }

    public function logout($name, $logintime)
    {
        $adminInfo = $this->getAdminByName($name);
        if ($adminInfo) {
            $adminInfo['lastlogintime']=$logintime;
            if ($this->save($adminInfo)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getAdminById($id)
    {
        return $this->find($id);
    }

    public function getAdminByName($name)
    {
          return $this->where(array('name'=>$name))->select();
    }

    public function addAdmin($name, $groupID, $pass, $state, $pointID)
    {
        $data = array(
            'name'=>$name,
            'group_id'=>$groupID,
            'point_id'=>$pointID,
            'password'=>md5($pass),
            'state'=>$state,
            'ip'=>get_client_ip(),
            'addtime'=>date("Y-m-d G:i:s"),
            'lastlogintime'=>date("Y-m-d G:i:s")
        );

        $result =$this->add($data);
        return $result;
    }

    public function switchAdmin($adminID, $meth)
    {
        $adminInfo = $this->getAdminById($adminID);
        if ($adminInfo) {
            $adminInfo['state'] = $meth;
            if ($this->save($adminInfo)) {
                return $adminInfo;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function delAdminByIdList($idlist)
    {
        return $this->deleteByIds($idlist);
    }

    public function getAdminCount($condition)
    {
        return $this->count($condition);
    }

    public function getAdminPage($conditon, $startIndex, $count)
    {
        return $this->order('addtime desc')
                    ->limit("$startIndex,$count")
                    ->findAll($conditon);
    }

    public function getAdminList($conditon)
    {
        return $this->order('addtime desc')
                    ->findAll($conditon);
    }

    public function updateAdmin($name, $groupID, $pass, $state, $adminID)
    {
        $adminInfo = $this->getAdminById($adminID);
        if ($adminInfo) {
            $adminInfo['name']=$name;
            $adminInfo['group_id']=$groupID;
            $adminInfo['state']=$state;
            if ($pass != "") {
                $adminInfo['password']=md5($pass);
            }
            if ($this->save($adminInfo)) {
                return "ok";
            } else {
                return "更新管理员信息发生错误";
            }
        } else {
            return "当前管理员信息不存在";
        }
    }

    public function changepassword($usercode, $oldpass, $newpass)
    {
        $adminInfo = $this->find(array('name'=>$usercode,'password'=>$oldpass));
        if ($adminInfo) {
            $adminInfo['password'] = $newpass;
            if ($this->save($adminInfo)) {
                return "ok";
            } else {
                return "管理员修改密码发生错误";
            }
        } else {
            return "原密码输入错误";
        }
    }
}
