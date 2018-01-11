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
     * showKind
     * 将处理完毕的数据以json格式返回给前端(KindEditor专用)
     * 
     * @param int    $status   处理状态
     * @param mixed  $data     将要处理的数据
     * @return string json格式化后的字符串
     **/
    function showKind($status,$data) {
        // 设定header
        header('Content-type:application/json;charset=UTF-8');
        if ($status == 0) {
            exit(json_encode(array('error'=>0,'url'=>$data)));
        }
        exit(json_encode(array('error'=>1,'message'=>'上传失败')));
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
     * getLoginUsername
     * 获取当前用户名，借此判断用户是否已登录
     * 
     * @return string
     **/
    function getLoginUsername() {
        return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username']: '';
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

    /**
     * getAdminMenuUrl
     * 获取当前正在访问的菜单，将其在导航标注以反映用户访问轨迹
     * 
     * @param int $navc 前端传入的控制器名
     * @return string
     **/
    function getActive($navc){
        $c = strtolower(CONTROLLER_NAME);
        if(strtolower($navc) == $c) {
            return 'class="active"';
        }
        return '';
    }

    /**
     * getCatName
     * 获取分类中文名
     * 
     * @param array $navs 导航信息
     * @param int   $id 分类id
     * @return mixed
     **/
    function getCatName($navs, $id) {
        foreach($navs as $nav) {
            $navList[$nav['menu_id']] = $nav['name'];
        }
        return isset($navList[$id]) ? $navList[$id] : '';
    }

    /**
     * getCopyFromById
     * 获取文章信息来源
     * 
     * @param int   $id 信息来源id
     * @return mixed
     **/
    function getCopyFromById($id) {
        $copyFrom = C("COPY_FROM");
        return $copyFrom[$id] ? $copyFrom[$id] : '';
    }

    /**
     * isThumb
     * 根据缩略图标识，并返回对应html信息
     * 
     * @param int $thumb 缩略图标识
     * @return string
     **/
    function isThumb($thumb) {
        if($thumb) {
            return '<span style="color:red">有</span>';
        }
        return '无';
    }





