<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 前端页面公共控制器
 * @author 常明
 */
class CommonController extends Controller {

    public function __construct() {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct();
    }

    /**
     * getRank
     * 获取首页排行数据
     * 
     * @access public 
     * @return array
     **/
    public function getRank() {
        $conds['status'] = 1;
        $news = D("News")->getRank($conds,10);
        return $news;
    }

    /**
     * error
     * 404页面
     * 
     * @access public 
     * @param string $message 错误信息
     * @return int
     **/
    public function error($message = '') {
        $message = $message ? $message : '系统发生错误';
        $this->assign('message',$message);
        $this->display("Index/error");
    }
}