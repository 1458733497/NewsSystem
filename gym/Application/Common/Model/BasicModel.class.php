<?php
namespace Common\Model;
use Think\Model;

/**
 * 网站基本配置模型（无对应表）
 * @author 常明
 */
class BasicModel extends Model {

    public function __construct() {
    }

    /**
     * save
     * 基本配置保存方法。通过TP自带的F方法缓存数据到文件
     * 
     * @access public 
     * @param array  $data 准备保存的数据
     * @return int
     **/
    public function save($data = array()) {
        if (!$data) {
            throw_exception('没有提交的数据');
        }
        // 此语句将产生一个名为basic_web_config.php的文件,里面的内容为序列化的网站基本信息
        $id = F('basic_web_config', $data);
        return $id;
    }

    /**
     * select
     * 从缓存文件读取网站基本配置
     * @access public 
     * @return array
     **/
    public function select() {
        return F("basic_web_config");
    }
}
