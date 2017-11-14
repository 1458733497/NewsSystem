<?php
namespace Admin\Controller;

use Think\Model;
use Think\Controller;

/**
 * 人工处理界面- 爬虫库/原始库管理
 */
class CrawlerController extends Controller
{

    /**
     * 模型初始化
     * @return void
     */
    public function _initialize()
    {

        $this->categoryModel        = M('t_categoryinfo'); // 分类表模型
        $this->regionModel          = M('t_regioninfo');   // 区域表模型
        $this->cpModel              = M('t_cpinfo');       // CP表模型
        $this->grabResultModel      = new \Admin\Model\GrabResultModel(); // 抓取库模型
        $this->OwnController        = new \Mam\Controller\OwnController(); // 自有库控制器
        C(load_config(APP_PATH.'Mam/Conf/config.php'));
    }

    /**
     * 爬虫库人工处理节目页面
     */
    public function rtbManProgram()
    {
        $grabModel = $this->grabResultModel;
        $this->assign('srcInfo', $grabModel::$originalSrcsMulti);
        $this->assign('reasonInfo', $grabModel::$processResultsMulti);
        $this->display();
    }

    /**
     * 爬虫库影片搜索处理
     *
     * @return void 为ajax返回json数组
     */
    public function ajaxGrabResultSearch()
    {
        $grabModel = $this->grabResultModel;
        // 生成搜索条件并执行搜索
        $searchConditions = $this->__createSearchCondition($_POST);
        // 排序处理
        if (isset($_POST['sort'])) {
            $this->grabResultModel->order([$_POST['sort'] => $_POST['order']]);
        }
        // 分页处理
        if (isset($_POST['rows']) && isset($_POST['page'])) {
            $this->grabResultModel->limit(($_POST['page'] - 1) * $_POST['rows'], $_POST['rows']);
        }
        $searchResult = $this->grabResultModel->where($searchConditions)->select();
        if (!is_null($searchResult)) {
            foreach ($searchResult as $key => $value) {
                $searchResult[$key]['source'] = $grabModel::$originalSrcs[$value['src']];
                $searchResult[$key]['handled'] = $grabModel::$processResults[$value['handled']];
            }
            // 追加总条数
            $returnResult['rows'] = $searchResult;
            $returnResult['total'] = $this->grabResultModel->where($searchConditions)->count();
        } else {
            $returnResult = [];
        }

        echo json_encode($returnResult);
    }

    /**
     * 原始库人工处理节目页面
     */
    public function tobManProgram()
    {
        $categoryModel        = M('t_categoryinfo'); // 分类表模型
        $this->assign('category_ret', $categoryModel->where(['inuse' => 0])->select());
        $this->display('TobManProgram');
    }
    /**
     * 原始库子集
     */
    public function tobManSubset()
    {
        $categoryModel        = M('t_categoryinfo'); // 分类表模型
        $this->assign('category_ret', $categoryModel->where(['inuse' => 0])->select());
        $this->display('TobManSubset');
    }
    /**
     * 子集管理开始*
     */
    public function ajaxSubsetTobList()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $from = ($page - 1 ) * $rows ;

        if (trim(I('subname')) != '') {
            $where['s.name'] = array('like','%'.trim(I('subname')).'%');
        }
        if (trim(I('pid')) != '') {
            $where['s.pid'] = trim(I('pid'));
        }
        if (trim(I('sid')) != '') {
            $where['s.sid'] = trim(I('sid'));
        }
        if (I('ismatched') != '') {
            $where['s.ismatched'] = I('ismatched');
        }
        $subset = M('t_subsetinfo_tob s');
        $count = $subset->order('s.sequenceno')->field('s.*')->where($where)->count();
        if ($count <= $from) {
            $from = 0;
        }
        $re = $subset->order('s.sequenceno')->field('s.*')->where($where)->limit($from, $rows)->select();
        if ($re) {
            $array['total'] = $count;
            $array['rows'] = $re;
        } else {
            $array['total'] = 0;
            $array['rows'] = 0;
        }
        echo json_encode($array);
    }

    public function ajaxProgramTobList()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $from = ($page - 1 ) * $rows ;

        if (I('name') != '') {
            $where['p.name'] = array('like',I('name').'%');
        }
        if (I('ismatched') != '') {
            $where['p.ismatched'] = I('ismatched');
        }
        if (I('categoryname') != '') {
            $where['p.categoryname'] = I('categoryname');
        }
        // 日期处理
        if (I('issuedate_s') != '') {
            $where['p.issuedate'][] = array('EGT' ,I('issuedate_s'));
        }
        if (I('issuedate_e') != '') {
            $where['p.issuedate'][] = array('ELT' ,I('issuedate_e'));
        }
        $program = M('t_programinfo_tob p');
        $count = $program->join('LEFT JOIN t_languageinfo l on p.Languageid = l.id')->join('LEFT JOIN t_definitioninfo d on p.definitionid = d.definitionid')->order('p.pid desc')->field('p.*,l.name Languagename,d.name definitionname,p.cpname')->where($where)->count();
        if ($count<=$from) {
            $from = 0;
        }
        $re = $program->join('LEFT JOIN t_languageinfo l on p.Languageid = l.id')->join('LEFT JOIN t_definitioninfo d on p.definitionid = d.definitionid')->order('p.pid desc')->field('p.*,l.name Languagename,d.name definitionname,p.cpname')->where($where)->limit($from, $rows)->select();
        if ($re) {
            $array['total'] = $count;
            $array['rows'] = $re;
        } else {
            $array['total'] = 0;
            $array['rows'] = 0;
        }
        echo json_encode($array);
    }
    public function ajaxProgramKlbList()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $from = ($page - 1 ) * $rows ;

        $w_data = array();
        if (trim(I('name'))) {
            $w_data['name'] = array('LIKE','%'.trim(I('name')).'%');
        }
        $program = M('t_programinfo_klb');
        $re = $program->where($w_data)->limit($from, $rows)->select();
        $count = $program->where($w_data)->count();

        if ($re) {
            $array['total'] = $count;
            $array['rows'] = $re;
        } else {
            $array['total'] = 0;
            $array['rows'] = 0;
        }
        echo json_encode($array);
    }

    /**
     * 生成搜索条件
     *
     * @param $params 前端输入数据
     * @return array 拼接好的where数组 | bool false 如果没有输入值
     */
    private function __createSearchCondition($params)
    {
        $grabModel = $this->grabResultModel;
        $conditions = [];
        // 若无参数取得全部信息
        if (empty($params)) {
            return true;
        }
        // 根据以下类型检索：节目名称、来源、知识库入库状况、入库时间
        if (isset($params['name'])) {
            $conditions[] = 'name like \'%' . $params['name'] . '%\'';
        }
        if (!empty($params['source'])) {
            $oriSrcs = array_flip($grabModel::$originalSrcs);
            $conditions[] = ['src' => $oriSrcs[$params['source']]];
        }
        if (!empty($params['kbl_status'])) {
            $klbResults = array_flip($grabModel::$processResults);
            $conditions[] = ['handled' => $klbResults[$params['kbl_status']]];
        }
        // 入库日期支持单向和双向搜索
        $issueDateScope = [];
        if (!empty($params['startTime'])) {
            array_push($issueDateScope, ['egt', $params['startTime']]);
        }
        if (!empty($params['endTime'])) {
            array_push($issueDateScope, ['elt', $params['endTime']]);
        }
        if (!empty($issueDateScope)) {
            $conditions['created_at'] = $issueDateScope;
        }

        return $conditions;
    }
    /**
     * 原始库节目上传
     * @return [type] [description]
     */
    public function tobUpload()
    {
        $status = 0;
        $message = '';
        $cp = '-1';
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $cp = I('cp');
            $type = I('type');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     0 ;// 设置附件上传大小
            $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类型
            $upload->rootPath  =     APP_PATH.'../uploads/'; // 设置附件上传根目录
            $info = $upload->upload();
            if (!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }
            $file_path = $upload->rootPath.$info['file']['savepath'].$info['file']['savename'];
            //上传成功
            $res = $this->OwnController->importExcel($file_path, $cp, $type);
            
            if ($res['err']['num'] == 0) {
                $status = 1;
                $message = '导入成功,';
            } elseif ($res['err']['num'] > 0 && $res['suc']['num'] > 0) {
                $status = -1;
                $message = '部分成功，';
            } else {
                $status = -1;
                $message = '导入失败,';
            }
            $message .= '成功：'.$res['suc']['num'].'，失败：'.$res['err']['num'];
            $content = $message;
            if ($status < 0) {
                foreach ($res['err']['info'] as $k => $v) {
                    // if ($k > 2) {
                    //     break;
                    // }
                    $message .= '<br>';
                    if ($k == 0) {
                        $message .=  '失败原因：<br>';
                    }
                    $message .= $v['dbError'];
                }
            }
            adminlog('原始库导入', $content, 1, 0, 0);
        } elseif (!isset($_FILES['file']['tmp_name']) || $_FILES['file']['tmp_name'] == '') {

        } else {
            $status = -1;
            $message = '文件上传有误';
            $content = $message;
            adminlog('原始库导入', $content, 1, 0, 0);
        }

        $m = M('t_cpinfo');
        // 只能选择自有CP
        $where = [
            'inuse' => 0,
            'can_status' => 1,
        ];
        $cp_data = $m->where($where)->select();
        $this->assign("status", $status);
        $this->assign("message", $message);
        $this->assign("cp_data", $cp_data);
        $this->assign("cp".$type, $cp);
        $this->display("Crawler/tobUpload");
    }
}
