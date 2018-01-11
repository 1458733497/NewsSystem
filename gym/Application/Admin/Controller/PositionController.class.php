<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 后台推荐位管理控制器
 * @author 常明
 */
class PositionController extends CommonController {

    /**
     * index
     * 推荐位管理首页
     * 
     * @access public 
     * @return void
     **/
    public function index()
    {
        $data['status'] = array('neq',-1);
        $positions = D("Position")->select($data);
        $this->assign('positions',$positions);
        $this->assign('nav','推荐位管理');
        $this->display();
    }

    /**
     * add
     * 推荐位添加/修改方法
     * 
     * @access public 
     * @return string
     **/
    public function add() {
        // 可以IS_POST常量来判断是否为post方式提交
        if(IS_POST) {
            // 非空判断
            if(!isset($_POST['name']) || !$_POST['name']) {
                return show(0, '推荐位名称为空');
            }
            // 如果为修改，直接更新数据库
            if($_POST['id']) {
                return $this->save($_POST);
            }
            try {
                // 执行推荐位插入
                $id = D("Position")->insert($_POST);
                if($id) {
                    return show(1,'新增成功',$id);
                }
                return show(0,'新增失败',$id);
            } catch (Exception $e) {
                return show(0, $e->getMessage());
            }
        } else {
            $this->display();
        }
    }

    /**
     * edit
     * 推荐位编辑方法。获取推荐位id，通过此id获取对应数据返回给前端进行展示
     * 
     * @access public 
     * @return void
     **/
    public function edit() {
        $data = array(
            'status' => array('neq', -1),
        );
        $id = $_GET['id'];
        $position = D("Position")->find($id);
        $this->assign('vo', $position);
        $this->display();
    }

    /**
     * save
     * 推荐位保存方法。将整理好的数据存储到数据库。此方法应该移至model层
     * 
     * @access public 
     * @param array  $data 准备插入的数据
     * @return string
     **/
    public function save($data) {
        $id = $data['id'];
        unset($data['id']);
        try {
            $id = D("Position")->updateById($id,$data);
            if($id === false) {
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch (Exception $e) {
            return show(0,$e->getMessage());
        }
    }

    /**
     * setStatus
     * 更新推荐位状态(逻辑删除)
     * 
     * @access public 
     * @return string
     **/
    public function setStatus() {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                $res = D("Position")->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(0, '没有提交的内容');
    }
}