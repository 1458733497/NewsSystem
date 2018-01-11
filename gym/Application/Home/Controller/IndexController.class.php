<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;

class IndexController extends CommonController {

    /**
     * index
     * 网站首页控制器。
     * 
     * @access public 
     * @return mixed
     **/
    public function index($type='') {
        //获取排行
        $rankNews = $this->getRank();
        // 获取首页大图数据
        $topPicNews = D("PositionContent")->select(array('status'=>1,'position_id'=>2),1);
        // 首页3小图推荐
        $topSmailNews = D("PositionContent")->select(array('status'=>1,'position_id'=>3),3);
        // 获取首页文章列表数据
        $listNews = D("News")->select(array('status'=>1,'thumb'=>array('neq','')),30);

        $advNews = D("PositionContent")->select(array('status'=>1,'position_id'=>5),2);
        $this->assign('result', array(
            'topPicNews' => $topPicNews,
            'topSmailNews' => $topSmailNews,
            'listNews' => $listNews,
            'advNews' => $advNews,
            'rankNews' => $rankNews,
            'catId' => 0,
        ));
        // 生成静态化页面
        if($type == 'buildHtml') {
            // 生成的页面名为index.html，路径为根目录，调用的模板为Index模块下的index模版
            $this->buildHtml('index',HTML_PATH,'Index/index');
        } else {
            $this->display();
        }
    }

    /**
     * build_html
     * 生成首页缓存页面
     * 
     * @access public 
     * @return mixed
     **/
    public function build_html() {
        $this->index('buildHtml');
        return show(1, '首页缓存生成成功');
    }

    /**
     * build_html
     * 生成首页缓存页面（自动化）
     * 在服务器上创建crontab自动任务，调用此方法生成html文件，以实现定时更新静态文件的效果  
     * 
     * @access public 
     * @return mixed
     **/
    public function crontab_build_html() {
        if(APP_CRONTAB != 1) {
            die("the_file_must_exec_crontab");
        }
        $result = D("Basic")->select();
        if(!$result['cacheindex']) {
            die('系统没有设置开启自动生成首页缓存的内容');
        }
        $this->index('buildHtml');
    }

    /**
     * getCount
     * 获取文章计数（首页）
     * 
     * @access public 
     * @return string
     **/
    public function getCount() {
        if(!$_POST) {
            return show(0, '没有任何内容');
        }
        $newsIds =  array_unique($_POST);
        try {
            $list = D("News")->getNewsByNewsIdIn($newsIds);
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        if(!$list) {
            return show(0, 'notdataa');
        }
        $data = array();
        foreach($list as $k=>$v) {
            $data[$v['news_id']] = $v['count'];
        }
        return show(1, 'success', $data);
    }
}