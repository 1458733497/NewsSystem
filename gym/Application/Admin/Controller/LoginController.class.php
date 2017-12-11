<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 用户登录控制器。
 */
class LoginController extends Controller {

    public function index() {
        // 如用户已登录，直接跳转至后台管理首页，否则显示登录页面
        if(session('adminUser')) {
            $this->redirect('Login/Index');
        }
        $this->display();
    }

    /**
     * check
     * 账号密码验证
     * @author 常明
     * 
     * @access public 
     * @return string 向前端返回json格式的字符串
     **/
    public function check() {
        // 获取前端页面传参
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 非空判断
        if(!trim($username)) {
            return show(0,'用户名不能为空');
        }
        if(!trim($password)) {
            return show(0,'密码不能为空');
        }
        // 从数据库取得用户名和密码，进行对比
        $ret = D('Admin')->getAdminByUsername($username);
        // 若未找到对应用户名，返回失败
        if(!$ret || $ret['status'] !=1) {
            return show(0,'该用户不存在');
        }
        // 若密码错误，返回失败
        if($ret['password'] != getMd5Password($password)) {
            return show(0,'密码错误');
        }
        // 若验证成功，则更新最后访问时间
        D("Admin")->updateByAdminId($ret['admin_id'],array('lastlogintime'=>time()));
        // 记录session，防止重复登录
        session('adminUser', $ret);
        // 返回成功
        return show(1,'登录成功');
    }

    /**
     * loginout
     * 退出登录处理
     * @author 常明
     * 
     * @access public 
     * @return void
     **/
    public function loginout() {
        // 清空对应session
        session('adminUser', null);
        // 跳转至登录页面
        $this->redirect('Login/Index');
    }

}