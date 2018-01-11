<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

/**
 * 计划任务实际控制器
 * @author 常明
 */
class CronController {

    /**
     * dumpmysql
     * 自动备份数据库
     * 
     * @access public 
     * @return void
     **/
    public function dumpmysql() {
        // 读取系统配置
        $result = D("Basic")->select();
        if(!$result['dumpmysql']) {
            die("系统没有设置开启自动备份数据库的内容");
        }
        // 拼接shell命令并执行
        $shell = "mysqldump -u".C("DB_USER")." " .C("DB_NAME")." > /tmp/cms".date("Ymd").".sql";
        exec($shell);
    }
}