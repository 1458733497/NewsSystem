<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 后台文章管理控制器
 * @author 常明
 */
class ContentController extends CommonController {

    /**
     * index
     * 文章管理首页
     * 
     * @access public 
     * @return void
     **/
    public function index() {
        // 整理搜索条件用于查询，并保留搜索值
        $conds = array();
        $title = $_GET['title'];
        if ($title) {
            $conds['title'] = $title;
            $this->assign('srhTitle', $title);
        }
        if ($_GET['catid']) {
            $conds['catid'] = intval($_GET['catid']);
            $this->assign('srhCat', $conds['catid']);
        }
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 10;
        // 获取文章数据和分页
        $news = D("News")->getNews($conds,$page,$pageSize);
        $count = D("News")->getNewsCount($conds);
        $res  =  new \Think\Page($count,$pageSize);
        $pageres = $res->show();
        // 获取推荐位信息
        $positions = D("Position")->getNormalPositions();
        $this->assign('pageres',$pageres);
        $this->assign('news',$news);
        $this->assign('positions', $positions);
        // 获取分类，用于分类搜索
        $this->assign('webSiteMenu',D("Menu")->getBarMenus());
        $this->display();
    }

    /**
     * add
     * 文章添加/修改方法
     * 
     * @access public 
     * @return mixed
     **/
    public function add() {
        // 如果有提交内容，进行数据验证
        if($_POST) {
            if(!isset($_POST['title']) || !$_POST['title']) {
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']) {
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']) {
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']) {
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']) {
                return show(0,'content不存在');
            }
            // 如果为修改，直接更新数据库
            if($_POST['news_id']) {
                return $this->save($_POST);
            }
            $newsId = D("News")->insert($_POST);
            if ($newsId) {
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                if ($cId) {
                    return show(1,'新增成功');
                } else {
                    return show(1,'主表插入成功，副表插入失败');
                }
            } else {
                return show(0,'新增失败');
            }
        } else {
            $webSiteMenu = D("Menu")->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C("COPY_FROM");
            $this->assign('webSiteMenu', $webSiteMenu);
            $this->assign('titleFontColor', $titleFontColor);
            $this->assign('copyfrom', $copyFrom);
            $this->display();
        }
    }

    /**
     * edit
     * 文章编辑方法。获取文章id，通过此id获取对应数据返回给前端进行展示
     * 
     * @access public 
     * @return void
     **/
    public function edit() {
        $newsId = $_GET['id'];
        // 若参数不合法或无对应数据，跳转至文章列表
        if(!$newsId) {
            $this->redirect('Content/Index');
        }
        // 获取文章数据
        $news = D("News")->find($newsId);
        if(!$news) {
            $this->redirect('Content/Index');
        }
        // 获取文章内容数据
        $newsContent = D("NewsContent")->find($newsId);
        if($newsContent) {
            $news['content'] = $newsContent['content'];
        }
        // 缩略图路径补全
        $news['thumb'] = '/gym' . $news['thumb'];
        // 获取分类
        $webSiteMenu = D("Menu")->getBarMenus();
        $this->assign('webSiteMenu', $webSiteMenu);
        $this->assign('titleFontColor', C("TITLE_FONT_COLOR"));
        $this->assign('copyfrom', C("COPY_FROM"));
        $this->assign('news',$news);
        $this->display();
    }

    /**
     * save
     * 文章保存方法。将整理好的数据存储到数据库。此方法应该移至model层
     * 
     * @access public 
     * @param array  $data 准备插入的数据
     * @return string
     **/
    public function save($data) {
        // 删除id
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try {
            // 分别更新文章表、文章内容表和推荐位－内容关联表
            // 此处$data里含有News表里面没有的字段，此情况下save方法会忽略这些字段
            $id = D("News")->updateById($newsId, $data);
            $newsContentData['content'] = $data['content'];
            $condId = D("NewsContent")->updateNewsById($newsId, $newsContentData);
            if($id === false || $condId === false) {
                return show(0, '更新失败');
            }
            return show(1, '更新成功');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * setStatus
     * 更新文章状态(逻辑删除)
     * 
     * @access public 
     * @return mixed
     **/
    public function setStatus() {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                if (!$id) {
                    return show(0, 'ID不存在');
                }
                $res = D("News")->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }
            return show(0, '没有提交的内容');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    /**
     * listorder
     * 排序操作，支持复数记录操作
     * 
     * @access public 
     * @return mixed
     **/
    public function listorder() {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        try {
            if ($listorder) {
                foreach ($listorder as $newsId => $v) {
                    // 执行更新
                    $id = D("News")->updateNewsListorderById($newsId, $v);
                    if ($id === false) {
                        $errors[] = $newsId;
                    }
                }
                if ($errors) {
                    return show(0, '排序失败-' . implode(',', $errors), array('jump_url' => $jumpUrl));
                }
                return show(1, '排序成功', array('jump_url' => $jumpUrl));
            }
        }catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(0,'排序数据失败',array('jump_url' => $jumpUrl));
    }

    /**
     * push
     * 推荐操作，将文章推送到推荐位
     * 
     * @access public 
     * @return mixed
     **/
    public function push() {
        // 获取链接来源
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        // 获取推荐位id
        $positonId = intval($_POST['position_id']);
        // 获取文章id
        $newsId = $_POST['push'];
        if(!$newsId || !is_array($newsId)) {
            return show(0, '请选择推荐的文章ID进行推荐');
        }
        if(!$positonId) {
            return show(0, '没有选择推荐位');
        }
        try {
            // 获取id对应的文章
            $news = D("News")->getNewsByNewsIdIn($newsId);
            if (!$news) {
                return show(0, '没有相关内容');
            }
            // 赋值,逐条进行插入
            foreach ($news as $new) {
                $data = array(
                    'position_id' => $positonId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' => $new['create_time'],
                );
                $position = D("PositionContent")->insert($data);
            }
        }catch (Exception $e) {
            return show(0, $e->getMessage());
        }
        return show(1, '推荐成功',array('jump_url'=>$jumpUrl));
    }
}