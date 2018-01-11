<?php
namespace Common\Model;
use Think\Model;

/**
 * 文章内容表NewsContent模型
 * @author 常明
 */
class NewsContentModel extends Model {
    // 数据库变量
    private $_db = '';
    // 引用NewsContent表
    public function __construct() {
        $this->_db = M('news_content');
    }

    /**
     * insert
     * 文章内容表插入操作
     * 
     * @access public 
     * @param array  $data 要插入的数据
     * @return int
     **/
    public function insert($data=array()) {
        if(!$data || !is_array($data)) {
            return 0;
        }
        // 添加更新时间
        $data['create_time'] = time();
        if(isset($data['content']) && $data['content']) {
            // 内容转换，替换特殊字符（'"<>&等）
            $data['content'] = htmlspecialchars($data['content']);
        }
        return $this->_db->add($data);
    }

    /**
     * find
     * 获取一条文章内容数据
     * 
     * @access public 
     * @param int  $id 文章id(非文章内容id)
     * @return array
     **/
    public function find($id) {
        return $this->_db->where('news_id='.$id)->find();
    }

    /**
     * updateById
     * 通过文章ID更新数据
     * 
     * @access public 
     * @param int    $id 文章id(非文章内容id)
     * @param array  $data 要插入的数据 
     * @return mixed
     **/
    public function updateNewsById($id, $data) {
        // 数据验证
        if(!$id || !is_numeric($id) ) {
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新数据不合法');
        }
        if(isset($data['content']) && $data['content']) {
            // 内容转换，替换特殊字符（'"<>&等）
            $data['content'] = htmlspecialchars($data['content']);
        }
        return $this->_db->where('news_id='.$id)->save($data);
    }
}
