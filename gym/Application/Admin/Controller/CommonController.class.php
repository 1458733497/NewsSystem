<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 后台管理模块公共控制器
 * @author 常明
 */
class CommonController extends Controller {


    public function __construct() {
        parent::__construct();
        $this->_init();
    }

    /**
     * _init
     * 所有后台模块公用的用户登录检查功能——若用户未登录，将其引导至登录页面
     * 
     * @access private 
     * @return void
     **/
    private function _init() {
        // 判断是否登录
        $isLogin = $this->isLogin();
        if(!$isLogin) {
            $this->redirect('Login/Index');
        }
    }

    /**
     * getLoginUser
     * 获取登录用户信息
     * @access public
     * @return mixed
     */
    public function getLoginUser() {
        return session("adminUser");
    }

    /**
     * 判定是否登录
     * @return boolean 
     */
    public function isLogin() {
        $user = $this->getLoginUser();
        if($user && is_array($user)) {
            return true;
        }

        return false;
    }

    /**
     * setStatus
     * 更新数据状态(逻辑删除)
     * 
     * @access public 
     * @param array $data 要更新的状态
     * @param int   $models 模型名
     * @return int
     **/
    public function setStatus($data, $models) {
        try {
            if ($_POST) {
                $id = $data['id'];
                $status = $data['status'];
                if (!$id) {
                    return show(0, 'ID不存在');
                }
                $res = D($models)->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
            return show(0, '没有提交的内容');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * listorder
     * 排序操作，支持复数记录操作
     * 
     * @access public 
     * @param int $model 模型名
     * @return int
     **/
    public function listorder($model='') {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        try {
            if ($listorder) {
                foreach ($listorder as $id => $v) {
                    // 执行更新
                    $id = D($model)->updateListorderById($id, $v);
                    if ($id === false) {
                        $errors[] = $id;
                    }
                }
                if ($errors) {
                    return show(0, '排序失败-' . implode(',', $errors), array('jump_url' => $jumpUrl));
                }
                return show(1, '排序成功', array('jump_url' => $jumpUrl));
            }
        }catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(0,'排序数据失败',array('jump_url' => $jumpUrl));
    }
}