<?php
namespace Admin\Model;

use Think\Model;

class GrabResultModel extends Model
{
    // 知识库入库状况
    const STATUS_NOT_HANDLED    = 0;    // 未处理
    const STATUS_HANDLE_FAIL    = 1;    // 入库失败
    const STATUS_HANDLE_SUCCESS = 2;    // 入库成功

    // 数据源
    const SOURCE_DOUBAN_MOVIE     = 'douban_movie';
    const SOURCE_DOUBAN_DRAMA     = 'douban_tv';
    const SOURCE_1905_MOVIE       = 'm1905_movie';
    const SOURCE_BAIDU_DRAMA      = 'baidu_v_tv';

    // 处理结果输出数组
    public static $processResults = [
        self::STATUS_NOT_HANDLED    => '未处理',
        self::STATUS_HANDLE_FAIL    => '处理失败',
        self::STATUS_HANDLE_SUCCESS => '处理完成',
    ];

    // 处理结果显示数组
    public static $processResultsMulti = [
        [
            'value' => '未处理',
        ],
        [
            'value' => '处理失败',
        ],
        [
            'value' => '处理完成',
        ],
    ];

    // 来源输出数组
    public static $originalSrcs = [
        self::SOURCE_DOUBAN_MOVIE    => '豆瓣电影',
        self::SOURCE_DOUBAN_DRAMA    => '豆瓣电视剧',
        self::SOURCE_1905_MOVIE      => 'M1905',
        self::SOURCE_BAIDU_DRAMA     => '百度电视剧',
    ];

    // 来源显示数组
    public static $originalSrcsMulti = [
        [
            'value' => '豆瓣电影',
        ],
        [
            'value' => '豆瓣电视剧',
        ],
        [
            'value' => 'M1905',
        ],
        [
            'value' => '百度电视剧',
        ],
    ];

    protected $trueTableName = 'video_info';
}
