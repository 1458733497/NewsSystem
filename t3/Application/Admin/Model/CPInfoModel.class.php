<?php

namespace Admin\Model;

use Think\Model;

class CPInfoModel extends Model
{

    protected $trueTableName = 't_cpinfo';
    protected $_validate = [
        ['name', 'require', "请确认以下错误信息：CP名称不能为空！", self::MUST_VALIDATE],
        ['name', '', "请确认以下错误信息：该CP名称已存在！", self::MUST_VALIDATE, 'unique'],
        ['name', '0, 32', "请确认以下错误信息：CP名称超出指定长度！", self::EXISTS_VALIDATE, 'length'],
//        ['aliasname', 'require', "请确认以下错误信息：aliasname不能为空！", self::MUST_VALIDATE],
        ['aliasname', '0, 512', "请确认以下错误信息：CP别名超出指定长度！", self::EXISTS_VALIDATE, 'length'],
//        ['cpcode', 'require', "请确认以下错误信息：cpcode不能为空！", self::MUST_VALIDATE],
        ['cpcode', '0, 512', "请确认以下错误信息：cp代码超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['inuse', 'require', "请确认以下错误信息：请选择是否可用！", self::MUST_VALIDATE],
        ['inuse', '1, 11', "请确认以下错误信息：inuse超出指定长度！", self::EXISTS_VALIDATE, 'length'],
    ];
}
