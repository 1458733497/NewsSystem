<?php
namespace Common\Model;
use Think\Model;

/**
 * 后台用户管理表Admin模型
 * @author 常明
 */
class AdminModel extends Model {
    private $_db = '';

    public function __construct() {
        $this->_db = M('admin');
    }

    /**
     * getAdminByUsername
     * 根据用户名获取用户信息
     * 
     * @access public 
     * @param string  $username 用户名
     * @return array
     **/
    public function getAdminByUsername($username = '') {
        $res = $this->_db->where('username="'.$username.'"')->find();
        return $res;
    }

    /**
     * getAdminByAdminId
     * 根据用户id获取用户信息
     * 
     * @access public 
     * @param int  $adminId 用户id
     * @return array
     **/
    public function getAdminByAdminId($adminId = 0) {
        $res = $this->_db->where('admin_id='.$adminId)->find();
        return $res;
    }

    /**
     * updateByAdminId
     * 根据用户id更新用户信息
     * 
     * @access public 
     * @param int    $id 用户id
     * @param array  $data 用户信息
     * @return int
     **/
    public function updateByAdminId($id, $data) {
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }
        // 更新
        return $this->_db->where('admin_id='.$id)->save($data);
    }

    /**
     * insert
     * 新增用户数据
     * 
     * @access public 
     * @param array  $data 用户信息
     * @return int
     **/
    public function insert($data = array()) {
        if(!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    /**
     * getAdmins
     * 获取有效用户信息
     * 
     * @access public 
     * @return array
     **/
    public function getAdmins() {
        $data = array(
            'status' => array('neq',-1),
        );
        return $this->_db->where($data)->order('admin_id desc')->select();
    }

    /**
     * updateStatusById
     * 根据用户id更新用户状态
     * 
     * @access public 
     * @param int    $id 用户id
     * @param int  $status 用户状态
     * @return int
     **/
    public function updateStatusById($id, $status) {
        if(!is_numeric($status)) {
            throw_exception("status不能为非数字");
        }
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return  $this->_db->where('admin_id='.$id)->save($data);
    }

    /**
     * getLastLoginUsers
     * 获取今日登录用户
     * 
     * @access public 
     * @return int
     **/
    public function getLastLoginUsers() {
        $time = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $data = array(
            'status' => 1,
            'lastlogintime' => array("gt",$time),
        );
        $res = $this->_db->where($data)->count();
        return $res['tp_count'];
    }

}
