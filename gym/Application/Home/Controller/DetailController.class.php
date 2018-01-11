<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 文章最终页（详情页）控制器
 * @author 常明
 */
class DetailController extends CommonController {

    /**
     * index
     * 文章详情页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        $id = intval($_GET['id']);
        if (!$id || $id<0) {
            return $this->error("ID不合法");
        }
        $news =  D("News")->find($id);
        if(!$news || $news['status'] != 1) {
            return $this->error("ID不存在或者资讯被关闭");
        }
        // 每打开页面一次，访问数加一
        $count = intval($news['count']) + 1;
        D('News')->updateCount($id, $count);
        $content = D("NewsContent")->find($id);
        // 解码文章内容
        $news['content'] = htmlspecialchars_decode($content['content']);
        $advNews = D("PositionContent")->select(array('status'=>1,'position_id'=>5),2);
        $rankNews = $this->getRank();
        $this->assign('result', array(
            'rankNews' => $rankNews,
            'advNews' => $advNews,
            'catId' => $news['catid'],
            'news' => $news,
        ));
        // 指定视图，以供预览时能提供正确的视图
        $this->display("Detail/index");
    }

    /**
     * view
     * 预览控制器
     * 
     * @access public 
     * @return void
     **/
    public function view() {
        // 权限控制：若用户已登录，则显示与正常文章页面一样的预览页面，否则报错。
        if(!getLoginUsername()) {
            $this->error("您没有权限访问该页面");
        }
        $this->index();
    }
}