<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 后台管理控制器
 * @author 常明
 */
class AdminController extends CommonController {

    /**
     * index
     * 后台管理首页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        $admins = D('Admin')->getAdmins();
        $this->assign('admins', $admins);
        $this->display();
    }

    /**
     * add
     * 新增用户
     * 
     * @access public 
     * @return void
     **/
    public function add() {
        // 保存数据
        if(IS_POST) {
            if(!isset($_POST['username']) || !$_POST['username']) {
                return show(0, '用户名不能为空');
            }
            if(!isset($_POST['password']) || !$_POST['password']) {
                return show(0, '密码不能为空');
            }
            $_POST['password'] = getMd5Password($_POST['password']);
            // 判定用户名是否存在
            $admin = D("Admin")->getAdminByUsername($_POST['username']);
            if($admin && $admin['status']!=-1) {
                return show(0,'该用户存在');
            }
            // 新增
            $id = D("Admin")->insert($_POST);
            if(!$id) {
                return show(0, '新增失败');
            }
            return show(1, '新增成功');
        }
        $this->display();
    }

    /**
     * setStatus
     * 变更用户状态
     * 
     * @access public 
     * @return void
     **/
    public function setStatus() {
        $data = array(
            'admin_id'=>intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($_POST,'Admin');
    }

    /**
     * personal
     * 个人中心页面
     * 
     * @access public 
     * @return void
     **/
    public function personal() {
        // 获取登录用户session信息
        $res = $this->getLoginUser();
        // 获取数据库用户信息
        $user = D("Admin")->getAdminByAdminId($res['admin_id']);
        $this->assign('vo',$user);
        $this->display();
    }

    /**
     * save
     * 更改用户信息
     * 
     * @access public 
     * @return void
     **/
    public function save() {
        $user = $this->getLoginUser();
        if(!$user) {
            return show(0,'用户不存在');
        }
        $data['realname'] = $_POST['realname'];
        $data['email'] = $_POST['email'];
        try {
            $id = D("Admin")->updateByAdminId($user['admin_id'], $data);
            if($id === false) {
                return show(0, '配置失败');
            }
            return show(1, '配置成功');
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
    }

}