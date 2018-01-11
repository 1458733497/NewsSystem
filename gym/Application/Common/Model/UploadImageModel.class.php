<?php
namespace Common\Model;
use Think\Model;

/**
 * 文件上传模型
 * @author 常明
 */
class UploadImageModel extends Model {
    // TP自带文件上传类
    private $_uploadObj = '';
    // 上传路径
    const UPLOAD = 'upload';

    // 初始化文件上传类
    public function __construct() {
        $this->_uploadObj = new  \Think\Upload();
        // 设定上传路径规则
        $this->_uploadObj->rootPath = './'.self::UPLOAD.'/';
        // 设定上传文件名规则
        $this->_uploadObj->subName = date(Y) . '/' . date(m) .'/' . date(d);
    }

    /**
     * upload
     * 处理kindeditor提交的图片
     * 
     * @access public 
     * @return mixed
     **/
    public function upload() {
        $res = $this->_uploadObj->upload();
        if($res) {
            //补全路径
            return '/' .self::UPLOAD . '/' . $res['imgFile']['savepath'] . $res['imgFile']['savename'];
        }else{
            return false;
        }
    }

    /**
     * imageUpload
     * 处理uploadify提交的图片
     * 
     * @access public 
     * @return mixed
     **/
    public function imageUpload() {
        $res = $this->_uploadObj->upload();
        if($res) {
            // 补全路径
            return '/' .self::UPLOAD . '/' . $res['file']['savepath'] . $res['file']['savename'];
        }else{
            return false;
        }
    }
}
