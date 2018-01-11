<?php
namespace Common\Model;
use Think\Model;

/**
 * 推荐位-文章关联表PositionContent模型
 * @author 常明
 */
class PositionContentModel extends Model {
    // 数据库变量
    private $_db = '';
    // 引用PositionContent表
    public function __construct() {
        $this->_db = M('position_content');
    }

    /**
     * select
     * 获取推荐位内容数据
     * 
     * @access public 
     * @param array  $data  搜索条件
     * @param int    $limit 搜索条数限制
     * @return array
     **/
    public function select($data = array(),$limit=0) {
        if($data['title']) {
            $data['title'] = array('like', '%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder desc ,id desc');
        if($limit) {
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }

    /**
     * find
     * 获取一条推荐位内容数据
     * 
     * @access public 
     * @param int  $id 推荐位内容id
     * @return array
     **/
    public function find($id) {
        $data = $this->_db->where('id='.$id)->find();
        return $data;
    }

    /**
     * insert
     * 推荐位－文章关联表插入操作
     * 
     * @access public 
     * @param array  $res 要插入的数据 
     * @return mixed
     **/
    public function insert($res=array()) {
        if(!$res || !is_array($res)) {
            return 0;
        }
        // 添加修改时间
        if(!$res['create_time']) {
            $res['create_time'] = time();
        }
        return $this->_db->add($res);
    }

    /**
     * 通过id更新的状态
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateStatusById($id, $status) {
        if(!is_numeric($status)) {
            throw_exception("status不能为非数字");
        }
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return  $this->_db->where('id='.$id)->save($data);
    }

    /**
     * updateById
     * 通过ID更新数据
     * 
     * @access public 
     * @param int    $id 指定id
     * @param array  $data 要插入的数据 
     * @return mixed
     **/
    public function updateById($id, $data) {
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }
        return  $this->_db->where('id='.$id)->save($data);
    }

    /**
     * updateListorderById
     * 根据id进行排序
     * 
     * @access public 
     * @param int  $id
     * @param int  $listorder 
     * @return int
     **/
    public function updateListorderById($id, $listorder) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this->_db->where('id='.$id)->save($data);
    }
}
