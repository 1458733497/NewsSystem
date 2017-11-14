<?php
namespace Admin\Model;

use Think\Model;

/**
 * 日志记录模型，对应adming_log表
 */
class AdminLogModel extends Model
{

    public function getLogCount($condition)
    {
        return $this->count($condition);
    }

    public function getLogPage($conditon, $startIndex, $count)
    {
        return $this->order('id desc')
                    ->limit("$startIndex,$count")
                    ->findAll($conditon);
    }

    public function delLogByIds($idlist)
    {
        return $this->deleteByIds($idlist);
    }

    public function getPubResourceStatus($title, $usercode, $startTime, $endTime, &$resourcenum, &$grouplist)
    {
        $loglist = $this->findAll(array('title'=>$title, 'usercode'=>$usercode, 'addtime'=>array(array('egt', $startTime),array('lt', $endTime), 'and')));
        $arrGroup = array();
        foreach ($loglist as $log) {
            $arrData = explode('|', $log['content']);
            $resourcenum += intval($arrData[1]);
            $arrGroup[] = $arrData[0];
        }
        $grouplist = join(',', $arrGroup);
    }
}
