<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台主页面控制器
 * @author 常明
 */
class IndexController extends CommonController {

    /**
     * index
     * 后台主页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        // 获取最大阅读数文章
        $news = D('News')->maxcount();
        // 获取文章总数
        $newscount = D('News')->getNewsCount(array('status'=>1));
        // 获取推荐位总数
        $positionCount = D('Position')->getCount(array('status'=>1));
        // 获取今日登录用户
        $adminCount = D("Admin")->getLastLoginUsers();

        $this->assign('news', $news);
        $this->assign('newscount', $newscount);
        $this->assign('positioncount', $positionCount);
        $this->assign('admincount', $adminCount);
        $this->display();
    }

}