<?php
namespace Common\Model;
use Think\Model;

/**
 * 菜单表Menu模型
 * @author 常明
 */
class NewsModel extends Model {
    // 数据库变量
    private $_db = '';
    // 引用News表
    public function __construct() {
        $this->_db = M('news');
    }

    /**
     * select
     * 获取首页文章列表数据
     * 
     * @access public 
     * @param array  $data 搜索条件
     * @param int    $limit 搜索条数
     * @return mixed
     **/
    public function select($data = array(), $limit = 100) {
        $list = $this->_db->where($data)->order('news_id desc')->limit($limit)->select();
        return $list;
    }

    /**
     * insert
     * 文章表插入操作
     * 
     * @access public 
     * @param array  $data 要插入的数据
     * @return int
     **/
    public function insert($data = array()) {
        if(!is_array($data) || !$data) {
            return 0;
        }
        // 添加修改时间和修改人信息
        $data['create_time']  = time();
        $data['username'] =  getLoginUsername();
        // 修改缩图路径
        $data['thumb'] = strstr($data['thumb'], '/upload');
        return $this->_db->add($data);
    }

    /**
     * getNews
     * 获取文章列表数据
     * 
     * @access public 
     * @param array  $data 查询条件
     * @param int    $page 查询页数
     * @param int    $pageSize 每页显示数据数
     * @return array 获取到的文章列表数据
     **/
    public function getNews($data,$page,$pageSize=10) {
        $conditions = $data;
        // 整理搜索条件
        if(isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid'])  {
            $conditions['catid'] = intval($data['catid']);
        }
        // 过滤掉已逻辑删除的数据
        $conditions['status'] = array('neq',-1);

        $offset = ($page - 1) * $pageSize;
        $list = $this->_db->where($conditions)
            ->order('listorder desc ,news_id desc')
            ->limit($offset,$pageSize)
            ->select();
        return $list;
    }

    /**
     * getMenusCount
     * 获取文章数据总计
     * 
     * @access public 
     * @param array  $data 查询条件
     * @return int
     **/
    public function getNewsCount($data = array()){
        $conditions = $data;
        // 整理搜索条件
        if(isset($data['title']) && $data['title']) {
            $conditions['title'] = array('like','%'.$data['title'].'%');
        }
        if(isset($data['catid']) && $data['catid'])  {
            $conditions['catid'] = intval($data['catid']);
        }
        // 过滤掉已逻辑删除的数据
        $conditions['status'] = array('neq',-1);
        return $this->_db->where($conditions)->count();
    }

    /**
     * find
     * 获取一条文章数据
     * 
     * @access public 
     * @param int  $id 文章id
     * @return array
     **/
    public function find($id) {
        $data = $this->_db->where('news_id='.$id)->find();
        return $data;
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
        if(!$id || !is_numeric($id) ) {
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新数据不合法');
        }
        return $this->_db->where('news_id='.$id)->save($data);
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
        if(!is_numeric($status)) {
            throw_exception('status不能为非数字');
        }
        if(!$id || !is_numeric($id)) {
            throw_exception('id不合法');
        }
        $data['status'] = $status;
        return $this->_db->where('news_id='.$id)->save($data);
    }

    /**
     * updateNewsListorderById
     * 通过ID更新排序
     * 
     * @access public 
     * @param int  $id 指定id
     * @param int  $listorder 排序值 
     * @return mixed
     **/
    public function updateNewsListorderById($id, $listorder) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }
        $data = array('listorder'=>intval($listorder));
        return $this->_db->where('news_id='.$id)->save($data);
    }

    /**
     * getNewsByNewsIdIn
     * 通过ID更新菜单状态（复数）
     * 
     * @access public 
     * @param array $newsIds 文章id组
     * @return mixed
     **/
    public function getNewsByNewsIdIn($newsIds) {
        if(!is_array($newsIds)) {
            throw_exception("参数不合法");
        }
        $data = array(
            'news_id' => array('in',implode(',', $newsIds)),
        );
        return $this->_db->where($data)->select();
    }

    /**
     * getRank
     * 获取排行的数据
     * 
     * @param array $data
     * @param int $limit
     * @return array
     */
    public function getRank($data = array(), $limit = 100) {
        $list = $this->_db->where($data)->order('count desc,news_id desc ')->limit($limit)->select();
        return $list;
    }

    /**
     * updateCount
     * 阅读量更新
     * 
     * @param int $id 文章id
     * @param int $count 当前阅读数
     * @return array
     */
    public function updateCount($id, $count) {
        if(!$id || !is_numeric($id)) {
            throw_exception("ID 不合法");
        }
        if(!is_numeric($count)) {
            throw_exception("count不能为非数字");
        }
        $data['count'] = $count;
        return $this->_db->where('news_id='.$id)->save($data);
    }

    /**
     * maxcount
     * 获取最大阅读数的文章
     * 
     * @return array 文章数据
     */
    public function maxcount() {
        $data = array(
            'status' => 1,
        );
        return $this->_db->where($data)->order('count desc')->limit(1)->find();
    }
}
