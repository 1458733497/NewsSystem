<?php

namespace Admin\Model;

use Think\Model;

class DefinitionInfoModel extends Model
{

    protected $trueTableName = 't_definitioninfo';
    protected $_validate = [
        ['name', 'require', "请确认以下错误信息：清晰度名称不能为空！", self::MUST_VALIDATE],
        ['name', '', "请确认以下错误信息：该清晰度名称已存在！", self::MUST_VALIDATE, 'unique'],
        ['name', '1, 32', "请确认以下错误信息：清晰度名称超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['definitionid', 'require', "请确认以下错误信息：清晰度代码标识不能为空！", self::MUST_VALIDATE],
        ['definitionid', '1, 8', "请确认以下错误信息：清晰度代码标识超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['cpid', 'require', "请确认以下错误信息：CP不能为空！", self::MUST_VALIDATE],
        ['cpid', '1, 11', "请确认以下错误信息：CP超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['inuse', 'require', "请确认以下错误信息：请填写是否可用！", self::MUST_VALIDATE],
        ['inuse', '1, 11', "请确认以下错误信息：inuse超出指定长度！", self::EXISTS_VALIDATE, 'length'],
    ];
}
