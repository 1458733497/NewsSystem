<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台主页面控制器
 * @author 常明
 */
class IndexController extends CommonController {

    public function index() {
        $news = D('News')->maxcount();
        $newscount = D('News')->getNewsCount(array('status'=>1));
        $positionCount = D('Position')->getCount(array('status'=>1));
        $adminCount = D("Admin")->getLastLoginUsers();

        $this->assign('news', $news);
        $this->assign('newscount', $newscount);
        $this->assign('positioncount', $positionCount);
        $this->assign('admincount', $adminCount);
        $this->display();
    }

}