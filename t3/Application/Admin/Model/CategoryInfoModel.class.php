<?php

namespace Admin\Model;

use Think\Model;

/**
 * 分类模型，对应t_categoryinfo表
 */
class CategoryInfoModel extends Model
{

    protected $trueTableName = 't_categoryinfo';
    protected $_validate = [
        ['name', 'require', "请确认以下错误信息：分类名称不能为空！", self::MUST_VALIDATE],
        ['name', '', "请确认以下错误信息：该分类名已存在！", self::MUST_VALIDATE, 'unique'],
        ['name', '1, 32', "请确认以下错误信息：分类名称超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['aliasname', '0, 128', "请确认以下错误信息：分类别名超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['contentsubject', 'require', "请确认以下错误信息：所属内容科目不能为空！", self::MUST_VALIDATE],
        ['contentsubject', '1, 11', "请确认以下错误信息：所属内容科目超出指定长度！", self::EXISTS_VALIDATE, 'length'],
        ['inuse', 'require', "请确认以下错误信息：是否可用不能为空！", self::MUST_VALIDATE],
        ['inuse', '1, 11', "请确认以下错误信息：是否可用超出指定长度！", self::EXISTS_VALIDATE, 'length'],
    ];
}
