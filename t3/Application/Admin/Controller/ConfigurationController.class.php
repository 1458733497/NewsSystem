<?php

namespace Admin\Controller;

use Think\Model;
use Think\Controller;

/**
 * 人工处理界面- 配置管理
 */
class ConfigurationController extends Controller
{

    /**
     * 初始化
     * @return void
     */
    public function _initialize()
    {
        header('Content-Type:text/html;charset=utf-8 ');
        // 分类模型
        $this->tCategoryInfoModel = new \Admin\Model\CategoryInfoModel();
        // CP模型
        $this->tCPInfoModel = new \Admin\Model\CPInfoModel();
        // 区域模型
        $this->tRegionInfoModel = new \Admin\Model\RegionInfoModel();
        // 语言模型
        $this->tLanguageInfoModel = new \Admin\Model\LanguageInfoModel();
        // 清晰度模型
        $this->tDefinitionInfoModel = new \Admin\Model\DefinitionInfoModel();
        // 标签模型
        $this->tTagInfoModel = new \Mam\Model\TagModel();
        // xml
        $this->xmlCon = new \Adi\Controller\MkXmlController();
    }

// 分类信息管理 STA--------------------------------------------------------------------------------------
    /**
     * 分类信息管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function categoryList()
    {
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;
        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        $re = $this->tCategoryInfoModel->limit($from, $rows)->where($condition)->order('cid desc')->select();
        $count = $this->tCategoryInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * 分类信息管理 - 添加分类
     * @return void 为ajax返回json数组
     */
    public function ajaxCategoryAdd()
    {
        // 接收参数
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['contentsubject'] = $_REQUEST['xscontentsubject'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tCategoryInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tCategoryInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            if ($re > 0) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'category', 'id'=> $re]);
            }
        } else {
            $retInfo = $this->tCategoryInfoModel->getError();
        }
        adminlog('添加分类', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 分类信息管理 - 修改分类
     * @return void 为ajax返回json数组
     */
    public function ajaxCategoryEdit()
    {
        // 接收参数
        $data['cid'] = $_REQUEST['xscid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['contentsubject'] = $_REQUEST['xscontentsubject'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tCategoryInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tCategoryInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            if ($re > -1) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'category', 'id'=> $data['cid']]);
            }
        } else {
            $retInfo = $this->tCategoryInfoModel->getError();
        }
        adminlog('修改分类', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 分类信息管理 - 删除分类
     * @return void 为ajax返回json数组
     */
    public function ajaxCategoryDel()
    {
        $cid = I('cid');
        $cids = explode(",", $cid);
        $count = count($cids);
        if ($count == 2) {
            $data = $this->tCategoryInfoModel->where('cid=' . $cids[0])->delete();
        } else {
            for ($i = 0; $i < (count($cids) - 1); $i++) {
                $data = $this->tCategoryInfoModel->where('cid=' . $cids[$i])->delete();
            }
        }
        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// 分类信息管理 END--------------------------------------------------------------------------------------
// 标签管理 STA------------------------------------------------------------------------------------------

    /**
     * 标签信息管理 - 显示
     */
    public function taginfo()
    {
        $result = $this->tCategoryInfoModel->where('inuse = \'0\'')->select();
        $this->assign('cate', $result); // cate对应前端的volist name="cate"
        $this->display();
    }

    /**
     * 标签信息管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function taginfoList()
    {
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;

        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        if (trim($_REQUEST['aliasname'])) {
            $condition['aliasname'] = array('LIKE', '%' . trim($_REQUEST['aliasname'] . '%'));
        }

        $re = $this->tTagInfoModel->where($condition)->limit($from, $rows)->order('tid desc')->select();
        $count = $this->tTagInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * 标签信息管理 - 添加标签
     * @return void 为ajax返回json数组
     */
    public function ajaxTaginfoAdd()
    {
        // 接收参数
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['categoryid'] = $_REQUEST['xscategoryid'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tTagInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tTagInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            if ($re > 0) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'tag', 'id'=> $re]);
            }
        } else {
            $retInfo = $this->tTagInfoModel->getError();
        }
        adminlog('添加标签', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 标签信息管理 - 编辑标签
     * @return void 为ajax返回json数组
     */
    public function ajaxTaginfoEdit()
    {
        $data['tid'] = $_REQUEST['xstid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['categoryid'] = $_REQUEST['xscategoryid'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tTagInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tTagInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            if ($re > -1) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'tag', 'id'=> $data['tid']]);
            }
        } else {
            $retInfo = $this->tTagInfoModel->getError();
        }
        adminlog('编辑标签', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 标签信息管理 - 删除标签
     * @return void 为ajax返回json数组
     */
    public function ajaxTaginfoDel()
    {
        $tid = I('tid');
        $tids = explode(",", $tid);
        $count = count($tids);
        if ($count == 2) {
            $data = $this->tTagInfoModel->where('tid=' . $tids[0])->delete();
        } else {
            for ($i = 0; $i < (count($tids) - 1); $i++) {
                $data = $this->tTagInfoModel->where('tid=' . $tids[$i])->delete();
            }
        }
        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// 标签管理 END----------------------------------------------------------------------------------------
// 清晰度管理 STA--------------------------------------------------------------------------------------

    /**
     * 清晰度管理 - 显示
     */
    public function definitioninfo()
    {
        $result = $this->tCPInfoModel->where('inuse = \'0\'')->select();
        $this->assign('cate', $result); // cate对应前端的volist name="cate"
        $this->display();
    }

    /**
     * 清晰度管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function definitioninfoList()
    {
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;
        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        $re = $this->tDefinitionInfoModel->limit($from, $rows)->where($condition)->order('id desc')->select();
        $count = $this->tDefinitionInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * 清晰度管理 - 添加清晰度
     * @return void 为ajax返回json数组
     */
    public function ajaxDefinitioninfoAdd()
    {
        $array = array();
        $data['name'] = $_REQUEST['xsname'];
        $data['definitionid'] = $_REQUEST['xsdefinitionid'];
        $data['cpid'] = $_REQUEST['xscpid'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tDefinitionInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tDefinitionInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            //暂不同步
        } else {
            $retInfo = $this->tDefinitionInfoModel->getError();
        }
        adminlog('添加清晰度', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 清晰度管理 - 修改清晰度
     * @return void 为ajax返回json数组
     */
    public function ajaxDefinitioninfoEdit()
    {
        // 接收参数
        $data['id'] = $_REQUEST['xsid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['definitionid'] = $_REQUEST['xsdefinitionid'];
        $data['cpid'] = $_REQUEST['xscpid'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tDefinitionInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tDefinitionInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            //暂不同步
        } else {
            $retInfo = $this->tDefinitionInfoModel->getError();
        }
        adminlog('编辑清晰度', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 清晰度管理 - 删除清晰度
     * @return void 为ajax返回json数组
     */
    public function ajaxDefinitioninfoDel()
    {
        $id = I('id');
        $ids = explode(",", $id);
        $count = count($ids);
        if ($count == 2) {
            $data = $this->tDefinitionInfoModel->where('id=' . $ids[0])->delete();
        } else {
            for ($i = 0; $i < (count($ids) - 1); $i++) {
                $data = $this->tDefinitionInfoModel->where('id=' . $ids[$i])->delete();
            }
        }
        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// 清晰度管理 END--------------------------------------------------------------------------------------
// 区域信息管理 STA------------------------------------------------------------------------------------

    /**
     * 区域信息管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function regioninfoList()
    {
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;
        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        $re = $this->tRegionInfoModel->limit($from, $rows)->where($condition)->order('rid desc')->select();
        $count = $this->tRegionInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * 区域信息管理 - 添加区域信息
     * @return void 为ajax返回json数组
     */
    public function ajaxRegioninfoAdd()
    {
        // 接收参数
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tRegionInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tRegionInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            if ($re > 0) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'region', 'id'=> $re]);
            }
        } else {
            $retInfo = $this->tRegionInfoModel->getError();
        }
        adminlog('添加区域', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 区域信息管理 - 编辑区域信息
     * @return void 为ajax返回json数组
     */
    public function ajaxRegioninfoEdit()
    {
        // 接收参数
        $data['rid'] = $_REQUEST['xsrid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tRegionInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tRegionInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            if ($re > -1) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'region', 'id'=> $data['rid']]);
            }
        } else {
            $retInfo = $this->tRegionInfoModel->getError();
        }
        adminlog('编辑区域', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 区域信息管理 - 删除区域信息
     * @return void 为ajax返回json数组
     */
    public function ajaxRegionDel()
    {
        $rid = I('rid');
        $rids = explode(",", $rid);
        $count = count($rids);
        if ($count == 2) {
            $data = $this->tRegionInfoModel->where('rid=' . $rids[0])->delete();
        } else {
            for ($i = 0; $i < (count($rids) - 1); $i++) {
                $data = $this->tRegionInfoModel->where('rid=' . $rids[$i])->delete();
            }
        }

        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// 区域信息管理 END------------------------------------------------------------------------------------
// 语言版本管理 STA------------------------------------------------------------------------------------
    /**
     * 语言版本管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function languageinfoList()
    {
        $array = array();
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;
        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        $re = $this->tLanguageInfoModel->limit($from, $rows)->where($condition)->order('id desc')->select();
        $count = $this->tLanguageInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * 语言版本管理 - 添加语言信息
     * @return void 为ajax返回json数组
     */
    public function ajaxLanguageinfoAdd()
    {
        // 接收参数
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tLanguageInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tLanguageInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            //暂无同步
        } else {
            $retInfo = $this->tLanguageInfoModel->getError();
        }
        adminlog('添加语言', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }
    /**
     * 语言版本管理 - 编辑语言信息
     * @return void 为ajax返回json数组
     */
    public function ajaxLanguageinfoEdit()
    {
        // 接收参数
        $data['id'] = $_REQUEST['xsid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tLanguageInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tLanguageInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            //暂不同步
        } else {
            $retInfo = $this->tLanguageInfoModel->getError();
        }
        adminlog('编辑语言', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * 语言版本管理 - 删除语言版本
     * @return void 为ajax返回json数组
     */
    public function ajaxLanguageinfoDel()
    {
        $id = I('id');
        $ids = explode(",", $id);
        $count = count($ids);
        if ($count == 2) {
            $data = $this->tLanguageInfoModel->where('id=' . $ids[0])->delete();
        } else {
            for ($i = 0; $i < (count($ids) - 1); $i++) {
                $data = $this->tLanguageInfoModel->where('id=' . $ids[$i])->delete();
            }
        }
        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// 语言版本管理 END------------------------------------------------------------------------------------
// CP信息管理 STA--------------------------------------------------------------------------------------

    /**
     * CP信息管理 - 显示和搜索
     * @return void 为ajax返回json数组
     */
    public function cpinfoList()
    {
        $array = array();
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $from = ($page - 1 ) * $rows;

        if (trim($_REQUEST['name'])) {
            $condition['name'] = array('LIKE', '%' . trim($_REQUEST['name'] . '%'));
        }
        $re = $this->tCPInfoModel->limit($from, $rows)->where($condition)->order('id desc')->select();
        $count = $this->tCPInfoModel->where($condition)->count();
        if ($re) {
            $this->ajaxReturn(['total' => $count, 'rows' => $re]);
        }
        $this->ajaxReturn(['total' => 0, 'rows' => 0]);
    }

    /**
     * CP信息管理 - 添加CP信息
     * @return void 为ajax返回json数组
     */
    public function ajaxCpinfoAdd()
    {
        // 获取参数
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['cpcode'] = $_REQUEST['xscpcode'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tCPInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tCPInfoModel->add();
            $retInfo = $re > 0 ? '添加成功' : '添加失败';
            if ($re > 0) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'cp', 'id'=> $re]);
            }
        } else {
            $retInfo = $this->tCPInfoModel->getError();
        }
        adminlog('添加CP', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * CP信息管理 - 编辑CP信息
     * @return void 为ajax返回json数组
     */
    public function ajaxCpinfoEdit()
    {
        $data['id'] = $_REQUEST['xsid'];
        $data['name'] = $_REQUEST['xsname'];
        $data['aliasname'] = $_REQUEST['xsaliasname'];
        $data['cpcode'] = $_REQUEST['xscpcode'];
        $data['inuse'] = $_REQUEST['xsinuse'];

        $istrue = $this->tCPInfoModel->create($data);
        $retInfo = '';
        if ($istrue) {
            $re = $this->tCPInfoModel->save();
            $retInfo = $re > -1 ? '编辑成功' : '编辑失败';
            if ($re > -1) {
                //生成同步xml文件
                $this->xmlCon->curlMake(['asset_class'=>'cp', 'id'=>$data['id']]);
            }
        } else {
            $retInfo = $this->tCPInfoModel->getError();
        }
        adminlog('编辑CP', $retInfo . ' name:' . $data['name'], 1, 0, 0);
        $this->ajaxReturn($retInfo);
    }

    /**
     * CP信息管理 - 删除CP信息
     * @return void 为ajax返回json数组
     */
    public function ajaxCpinfoDel()
    {
        $id = I('id');
        $ids = explode(",", $id);
        $count = count($ids);
        if ($count == 2) {
            $data = $this->tCPInfoModel->where('id=' . $ids[0])->delete();
        } else {
            for ($i = 0; $i < (count($ids) - 1); $i++) {
                $data = $this->tCPInfoModel->where('id=' . $ids[$i])->delete();
            }
        }
        if ($data) {
            $result["success"] = true;
        } else {
            $result["errorMsg"] = "删除失败";
        }
        $this->ajaxReturn($result);
    }
// CP信息管理 END--------------------------------------------------------------------------------------
}
