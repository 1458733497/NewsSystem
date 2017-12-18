<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 后台菜单管理控制器
 * @author 常明
 */
class MenuController extends CommonController {

    /**
     * index
     * 菜单主页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        $data = array();
        // 获取前端搜索栏的类型值。用于数据库搜索和保留用户搜索内容
        if(isset($_REQUEST['type']) && in_array($_REQUEST['type'], array(0,1))) {
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type',$data['type']);
        } else {
            $this->assign('type',-100);
        }
        // 分页准备
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
        // 获取菜单详细数据
        $menus = D("Menu")->getMenus($data,$page,$pageSize);
        // 获取总数
        $menusCount = D("Menu")->getMenusCount($data);
        // 调用TP自带的分页功能，此语句会返回一个分页对象。
        $res = new \Think\Page($menusCount, $pageSize);
        $pageRes = $res->show();

        $this->assign('pageRes', $pageRes);
        $this->assign('menus',$menus);
        $this->display();
    }

    /**
     * add
     * 菜单添加/修改方法
     * 
     * @access public 
     * @return mixed
     **/
    public function add() {
        // 若有提交内容则进行数据验证及数据库更新处理
        if($_POST) {
            // 数据验证
            if(!isset($_POST['name']) || !$_POST['name']) {
                return show(0,'菜单名不能为空');
            }
            if(!isset($_POST['m']) || !$_POST['m']) {
                return show(0,'模块名不能为空');
            }
            if(!isset($_POST['c']) || !$_POST['c']) {
                return show(0,'控制器不能为空');
            }
            if(!isset($_POST['f']) || !$_POST['f']) {
                return show(0,'方法名不能为空');
            }
            // 若已存在id，证明为菜单修改。
            if($_POST['menu_id']) {
                return $this->save($_POST);
            }
            // 不存在id则证明为新增，执行插入。成功会返回插入新记录的id
            $menuId = D("Menu")->insert($_POST);
            if($menuId) {
                return show(1,'新增成功',$menuId);
            }
            return show(0,'新增失败',$menuId);
        } else {
            // 若没有提交内容，直接显示模板
            $this->display();
        }
    }

    /**
     * edit
     * 菜单编辑方法。获取菜单id，通过此id获取对应数据返回给前端进行展示
     * 
     * @access public 
     * @return void
     **/
    public function edit() {
        $menuId = $_GET['id'];
        $menu = D("Menu")->find($menuId);
        $this->assign('menu', $menu);
        $this->display();
    }

    /**
     * save
     * 菜单保存方法。将整理好的数据存储到数据库。此方法应该移至model层
     * 
     * @access public 
     * @param array  $data 准备插入的数据
     * @return string
     **/
    public function save($data) {
        $menuId = $data['menu_id'];
        unset($data['menu_id']);
        try {
            $id = D("Menu")->updateMenuById($menuId, $data);
            if($id === false) {
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        } catch(Exception $e) {
            return show(0,$e->getMessage());
        }
    }

    /**
     * setStatus
     * 更新菜单状态
     * 
     * @access public 
     * @return mixed
     **/
    public function setStatus() {
        try {
            // 获取前端数据
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                // 执行数据更新操作
                $res = D("Menu")->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }

            }
        }catch(Exception $e) {
            return show(0,$e->getMessage());
        }
        return show(0,'没有提交的数据');
    }

    /**
     * listorder
     * 排序操作，支持复数记录操作
     * 
     * @access public 
     * @return mixed
     **/
    public function listorder() {
        // 获取排序值
        $listorder = $_POST['listorder'];
        // 获取referer，用于操作完毕后返回
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listorder) {
            try {
                foreach ($listorder as $menuId => $v) {
                    // 执行更新
                    $id = D("Menu")->updateMenuListorderById($menuId, $v);
                    if ($id === false) {
                        $errors[] = $menuId;
                    }

                }
            }catch(Exception $e) {
                return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
            }
            if ($errors) {
                return show(0,'排序失败-'.implode(',',$errors),array('jump_url'=>$jumpUrl));
            }
            return show(1,'排序成功',array('jump_url'=>$jumpUrl));
        }
        return show(0,'排序数据失败',array('jump_url'=>$jumpUrl));
    }
}