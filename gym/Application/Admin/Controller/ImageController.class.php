<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

/**
 * 图片上传控制器
 * @author 常明
 */
class ImageController extends CommonController {
    public function __construct() {
    }

    /**
     * ajaxuploadimage
     * 处理ajax方式的图片上传
     * 
     * @access public 
     * @return string
     **/
    public function ajaxuploadimage() {
        $upload = D("UploadImage");
        $res = $upload->imageUpload();
        if($res===false) {
            return show(0,'上传失败','');
        }else{
            return show(1,'上传成功',$res);
        }
    }

    /**
     * kindupload
     * 处理图片上传(KindEditor)
     * 
     * @access public 
     * @return string
     **/
    public function kindupload() {
        $upload = D("UploadImage");
        $res = $upload->upload();
        if($res === false) {
            return showKind(1,'上传失败');
        }
        return showKind(0,$res);
    }
}