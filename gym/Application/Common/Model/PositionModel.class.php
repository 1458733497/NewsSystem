<?php
namespace Common\Model;
use Think\Model;

/**
 * 推荐位表Position模型
 * @author 常明
 */
class PositionModel extends Model {

    private $_db = '';
    public function __construct() {
        $this->_db = M('position');
    }

    /**
     * select
     * 获取推荐位数据
     * 
     * @access public 
     * @param array  $data 搜索条件
     * @return array
     **/
    public function select($data = array()) {
        $conditions = $data;
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }

    /**
     * find
     * 获取一条推荐位数据
     * 
     * @access public 
     * @param int  $id 推荐位id
     * @return array
     **/
    public function find($id) {
        $data = $this->_db->where('id='.$id)->find();
        return $data;
    }

    /**
     * getCount
     * 获取推荐位总数
     * 
     * @access public 
     * @param array  $data 查询条件
     * @return array
     **/
    public function getCount($data = array()) {
        $conditions = $data;
        $list = $this->_db->where($conditions)->count();
        return $list;
    }

    /**
     * insert
     * 推荐位表插入操作
     * 
     * @access public 
     * @param array  $res 要插入的数据
     * @return int
     **/
    public function insert($res=array()) {
        if(!$res || !is_array($res)) {
            return 0;
        }
        // 添加创建时间
        $res['create_time'] = time();
        return $this->_db->add($res);
    }

    /**
     * updateStatusById
     * 通过ID更新推荐位状态
     * 
     * @access public 
     * @param int    $id 指定id
     * @param int  $status 状态 
     * @return mixed
     **/
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
     * getNormalPositions
     * 获取正常推荐位的内容
     * 
     * @access public
     * @return array
     **/
    public function getNormalPositions() {
        $conditions = array('status'=>1);
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
    }

}
