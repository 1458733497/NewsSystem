<?php
namespace Common\Model;
use Think\Model;
/**
 * 菜单表Menu模型
 * @author 常明
 */
class MenuModel extends  Model {
    // 数据库变量
    private $_db = '';
    // 引用Menu表
    public function __construct() {
        $this->_db = M('menu');
    }

    /**
     * insert
     * 菜单表插入操作
     * 
     * @access public 
     * @param array  $data 要插入的数据 
     * @return mixed
     **/
    public function insert($data = array()) {
        if(!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    public function find($id){
        if(!$id || !is_numeric($id)) {
            return array();
        }
        return $this->_db->where('menu_id='.$id)->find();
    }

    /**
     * updateMenuById
     * 通过ID更新数据
     * 
     * @access public 
     * @param int    $id 指定id
     * @param array  $data 要插入的数据 
     * @return mixed
     **/
    public function updateMenuById($id, $data) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    /**
     * updateStatusById
     * 通过ID更新菜单状态
     * 
     * @access public 
     * @param int    $id 指定id
     * @param int  $status 数据状态 
     * @return mixed
     **/
    public function updateStatusById($id, $status) {
        // 数据验证
        if(!is_numeric($id) || !$id) {
            throw_exception("ID不合法");
        }
        if(!is_numeric($status) || !$status) {
            throw_exception("状态不合法");
        }
        // 更新
        $data['status'] = $status;
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    /**
     * updateMenuListorderById
     * 通过ID更新排序
     * 
     * @access public 
     * @param int  $id 指定id
     * @param int  $listorder 排序值 
     * @return mixed
     **/
    public function updateMenuListorderById($id, $listorder) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }
        $data = array(
            'listorder' => intval($listorder),
        );
        return $this->_db->where('menu_id='.$id)->save($data);
    }

    /**
     * getMenus
     * 获取菜单表数据
     * 
     * @access public 
     * @param array  $data 查询条件
     * @param int    $page 查询页数
     * @param int    $pageSize 每页显示数据数
     * @return mixed
     **/
    public function getMenus($data,$page,$pageSize=10) {
        // 显示被删除菜单
        // $data['status'] = array('neq',-1);
        $offset = ($page - 1) * $pageSize;
        $list = $this->_db
            ->where($data)
            ->order('listorder desc,menu_id desc')
            ->limit($offset,$pageSize)
            ->select();
        return $list;
    }

    /**
     * getMenusCount
     * 获取菜单数据总计
     * 
     * @access public 
     * @param array  $data 查询条件
     * @return int
     **/
    public function getMenusCount($data= array()) {
        // 计算被删除菜单
        // $data['status'] = array('neq',-1);
        return $this->_db->where($data)->count();
    }

    /**
     * getAdminMenus
     * 获取导航菜单
     * 
     * @access public 
     * @return array
     **/
    public function getAdminMenus() {
        // 隐藏逻辑删除菜单
        $data = array(
            'status' => array('neq',-1),
            'type' => 1,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    /**
     * getBarMenus
     * 获取前端菜单列表
     * 
     * @access public 
     * @return array
     **/
    public function getBarMenus() {
        $data = array(
            'status' => 1,
            'type' => 0,
        );
        $res = $this->_db->where($data)
            ->order('listorder desc,menu_id desc')
            ->select();
        return $res;
    }
}