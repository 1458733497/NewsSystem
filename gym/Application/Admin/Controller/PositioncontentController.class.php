<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 后台推荐位内容管理控制器
 * @author 常明
 */
class PositioncontentController extends CommonController {

    public function index() {
        // 获取推荐位信息
        $positions = D("Position")->getNormalPositions();
        // 整理搜索条件
        $data['status'] = array('neq', -1);
        if($_GET['title']) {
            $data['title'] = trim($_GET['title']);
            $this->assign('title', $data['title']);
        }
        $data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']) : $positions[0]['id'];
        // 获取推荐位内容信息
        $contents = D("PositionContent")->select($data);
        $this->assign('positions', $positions);
        $this->assign('contents', $contents);
        $this->assign('positionId', $data['position_id']);
        $this->display();
    }

    /**
     * add
     * 推荐位内容添加/修改方法
     * 
     * @access public 
     * @return mixed
     **/
    public function add() {
        // 如果有提交，进行数据验证
        if($_POST) {
            if(!isset($_POST['position_id']) || !$_POST['position_id']) {
                return show(0, '推荐位ID不能为空');
            }
            if(!isset($_POST['title']) || !$_POST['title']) {
                return show(0, '推荐位标题不能为空');
            }
            if(!$_POST['url'] && !$_POST['news_id']) {
                return show(0, 'url和news_id不能同时为空');
            }
            if(!isset($_POST['thumb']) || !$_POST['thumb']) {
                if($_POST['news_id']) {
                    $res = D("News")->find($_POST['news_id']);
                    if($res && is_array($res)) {
                        $_POST['thumb'] = $res['thumb'];
                    }
                }else{
                    return show(0,'图片不能为空');
                }
            }
            if($_POST['id']) {
              return $this->save($_POST);
            }
            try{
                $id = D("PositionContent")->insert($_POST);
                if($id) {
                    return show(1, '新增成功');
                }
                return show(0, '新增失败');
            }catch(Exception $e) {
                return show(0, $e->getMessage());
            }
        }else {
            $positions = D("Position")->getNormalPositions();
            $this->assign('positions', $positions);
            $this->display();
        }
    }

    /**
     * edit
     * 推荐位编辑页面对应控制器。获取推荐位文章id，通过此id获取对应数据返回给前端进行展示
     * 
     * @access public 
     * @return void
     **/
    public function edit() {
        $id = $_GET['id'];
        $position = D("PositionContent")->find($id);
        $positions = D("Position")->getNormalPositions();
        $this->assign('positions', $positions);
        $this->assign('vo', $position);
        $this->display();
    }

    /**
     * save
     * 推荐位文章保存方法。将整理好的数据存储到数据库。此方法应该移至model层
     * 
     * @access public 
     * @param array  $data 准备插入的数据
     * @return string
     **/
    public function save($data) {
        $id = $data['id'];
        unset($data['id']);
        try {
            $resId = D("PositionContent")->updateById($id, $data);
            if($resId === false) {
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * setStatus
     * 更新推荐位文章状态(逻辑删除)
     * 
     * @access public 
     * @return mixed
     **/
    public function setStatus() {

        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data, 'PositionContent');
    }

    /**
     * listorder
     * 排序操作，支持复数记录操作
     * 
     * @access public 
     * @return mixed
     **/
    public function listorder() {
        return parent::listorder("PositionContent");
    }

}