<?php
/**
 * 公共函数
 * @author 常明
 */

    /**
     * show
     * 将处理完毕的数据以json格式返回给前端
     * 
     * @param int    $status   处理状态
     * @param string $message  说明文字 
     * @param mixed  $data     将要处理的数据
     * @return string json格式化后的字符串
     **/
    function show($status, $message, $data = array()) {
        $reuslt = array(
            'status' => $status,
            'message' => $message,
            'data' => $data,
        );
        exit(json_encode($reuslt));
    }

    /**
     * getMd5Password
     * 用md5加密用户密码
     * 
     * @param string $password 明文密码
     * @return string 加密后的字符串
     **/
    function getMd5Password($password) {
        return md5($password . C('MD5_SUFFIX'));
    }

    /**
     * getMenuType
     * 获取菜单中文名
     * 
     * @param int $type 菜单名对应数字
     * @return string 中文菜单名
     **/
    function getMenuType($type) {
        return $type == 1 ? '后台菜单' : '前端导航';
    }

    /**
     * status
     * 获取状态中文名
     * 
     * @param int $status 状态对应数字
     * @return string 中文状态名
     **/
    function status($status) {
        if($status == 0) {
            $str = '关闭';
        }elseif($status == 1) {
            $str = '正常';
        }elseif($status == -1) {
            $str = '删除';
        }
        return $str;
    }

    /**
     * getAdminMenuUrl
     * 获取后台菜单地址
     * 
     * @param array $nav 从数据库中取出的导航信息
     * @return string 拼接后的字符串
     **/
    function getAdminMenuUrl($nav) {
        // 拼接方式为 模块名/控制器名/方法名;若方法为index则可省略
        $url = __ROOT__ . '/' . ucfirst($nav['m']) . '/' . $nav['c'];
        if ($nav['f'] != 'index') {
            $url .= '/' . $nav['f'];
        }
        return $url;
    }

    function getActive($navc){
        $c = strtolower(CONTROLLER_NAME);
        if(strtolower($navc) == $c) {
            return 'class="active"';
        }
        return '';
    }
    function showKind($status,$data) {
        header('Content-type:application/json;charset=UTF-8');
        if($status==0) {
            exit(json_encode(array('error'=>0,'url'=>$data)));
        }
        exit(json_encode(array('error'=>1,'message'=>'上传失败')));
    }
    function getLoginUsername() {
        return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username']: '';
    }
    function getCatName($navs, $id) {
        foreach($navs as $nav) {
            $navList[$nav['menu_id']] = $nav['name'];
        }
        return isset($navList[$id]) ? $navList[$id] : '';
    }
    function getCopyFromById($id) {
        $copyFrom = C("COPY_FROM");
        return $copyFrom[$id] ? $copyFrom[$id] : '';
    }
    function isThumb($thumb) {
        if($thumb) {
            return '<span style="color:red">有</span>';
        }
        return '无';
    }

    /**
    +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
    +----------------------------------------------------------
     * @static
     * @access public
    +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
    +----------------------------------------------------------
     * @return string
    +----------------------------------------------------------
     */
    function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
    {
        $len = substr($str);
        if(function_exists("mb_substr")){
            if($suffix)
                return mb_substr($str, $start, $length, $charset)."...";
            else
                return mb_substr($str, $start, $length, $charset);
        }
        elseif(function_exists('iconv_substr')) {
            if($suffix && $len>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
            else
                return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
    }





