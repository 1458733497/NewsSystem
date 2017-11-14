<?php

namespace Admin\Model;

use Think\Model;

class LanguageInfoModel extends Model
{
    protected $trueTableName = 't_languageinfo';
    protected $_validate = [
        ['name', 'require', "请确认以下错误信息：语言版本名称不能为空！", self::MUST_VALIDATE],
        ['name', '', "请确认以下错误信息：该语言版本名称已存在！", self::MUST_VALIDATE, 'unique'],
        ['name', '1, 32', "请确认以下错误信息：语言版本名称超出指定长度！", self::EXISTS_VALIDATE, 'length'],
//        ['aliasname', 'require', "请确认以下错误信息：aliasname不能为空！", self::MUST_VALIDATE],
        ['aliasname', '0, 512', "请确认以下错误信息：别名超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['inuse', 'require', "请确认以下错误信息：请填写是否可用！", self::MUST_VALIDATE],
        ['inuse', '1, 11', "请确认以下错误信息：inuse超出指定长度！", self::EXISTS_VALIDATE, 'length'],
    ];
}
