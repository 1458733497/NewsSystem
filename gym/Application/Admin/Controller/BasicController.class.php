<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
/**
 * 网站基本配置控制器
 * @author 常明
 */
class BasicController extends CommonController {

    /**
     * index
     * 基本配置首页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        // 获取基本配置数据
        $result = D("Basic")->select();
        $this->assign('vo', $result);
        $this->assign('type',1);
        $this->display();
    }

    /**
     * add
     * 基本配置添加/修改方法
     * 
     * @access public 
     * @return mixed
     **/
    public function add() {
        // 数据验证
        if($_POST) {
            if(!$_POST['title']) {
                return show(0, '站点信息不能为空');
            }
            if(!$_POST['keywords']) {
                return show(0, '站点关键词');
            }
            if(!$_POST['description']) {
                return show(0, '站点描述');
            }
            // 保存
            D("Basic")->save($_POST);
            return show(1, '配置成功');
        } else {
            return show(0, '没有提交的数据');
        }
    }

    /**
     * cache
     * 缓存管理
     * 
     * @access public 
     * @return mixed
     **/
    public function cache() {
        $this->assign('type',2);
        $this->display();
    }



}