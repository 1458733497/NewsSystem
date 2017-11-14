<?php if (!defined('THINK_PATH')) exit(); echo '<?'; ?>
xml version="1.0" encoding="UTF-8" ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>欢迎使用媒资系统管理平台</title>

<link href="/t3/Public/css/admin.css" rel="stylesheet" type="text/css" />

<link href="/t3/Public/css/login.css" rel="stylesheet" type="text/css" />

<link href="/t3/Public/css/png.css" rel="stylesheet" type="text/css" />

<link href="/t3/Public/css/validator.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="/t3/Public/js/jquery_last.js" charset="utf-8"></script>

<script language="javascript" type="text/javascript" src="/t3/Public/js/formValidator.js" charset="utf-8"></script>

<script language="javascript" type="text/javascript" src="/t3/Public/js/formValidatorRegex.js" charset="utf-8"></script>

<script language="JavaScript"> 

function fleshVerify(){

    //重载验证码 

    var time = new Date().getTime(); 

    document.getElementById('verifyImg').src= '/Admin/Index/verify/'+time; 

} 

</script> 

<style>

.content h1{

	background:url(/t3/Public/image/loginlogo2.png) no-repeat !important;background:none;

	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/t3/Public/image/loginlogo2.png' ,sizingMethod='crop');

}

.content .loginbtn{

	background:url(/t3/Public/image/loginbt.png) no-repeat 0 0px!important;background:none;

	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/t3/Public/image/loginbt.png' ,sizingMethod='crop');

}

.content .loginbtnfocus{

	background:url(/t3/Public/image/loingbt2.png) no-repeat 0 0px!important;background:none;

	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/t3/Public/image/loingbt2.png' ,sizingMethod='crop');

}

.content .body ul li {margin: 11px 0;}

</style>

</head>

<body>

<div style="position:absolute; left:20px; top:20px;"></div>

<div style="font-size:30px;color:#fff;width: 100%;

position:absolute;top: 50%;margin-top: -195px; font-family:'微软雅黑'">

<img src="/t3/Public/images/text.jpg" />

</div>

<div class="content" style="margin-top:-90px;">
  <div class="body">
    <form id="form1" name="form1" method="post" action="/t3/Admin/Index/checklogin">
    <ul style="margin-top:26px;">
      <li>
        <input id="usrname" type="text" name="usrname" class="inputstyle" maxlength="50" autocomplete="off"
		        value="请输入用户名"  
                onfocus="if(this.value=='请输入用户名') this.value='';this.className='inputstylefocus';return true;"

                onblur="if(this.value=='') this.value='请输入用户名';this.className='inputstyle';return true;" />

      </li>

      <li>

        <input id="password" type="password" name="password" class="inputstyle" maxlength="20"

				value="请输入密码"  

                onfocus="if(this.value=='请输入密码') this.value='';this.className='inputstylefocus';return true;"

                onblur="if(this.value=='') this.value='请输入密码';this.className='inputstyle';return true;" />

      </li>



      <?php if($verify == 1): ?><li style="position:relative">

        <input class="inputstyle" autocomplete="off" size="8" name="verifycode" id="verifycode" value="请输入验证码"

                onfocus="if(this.value=='请输入验证码') this.value=''; this.className='inputstylefocus';return true;"

                onblur="if(this.value=='') this.value='请输入验证码'; this.className='inputstyle';return true;" />

        <a href="#"> <img id="verifyImg"  onclick="fleshVerify()"  src="/t3/Admin/Index/verify" border="0" alt=""style="width: 100px;height: 25px;vertical-align: middle;margin-top: -5px;"/></a>

        </li><?php endif; ?>

      <li class="btn png">

        <input name="login" type="submit" class="loginbtn" value=" "

		onmouseover="this.blur();return true;"

	    onmousedown="this.className='loginbtnfocus';return true;"/>

      </li>
     

	  </ul>
    </form>
      <?php echo ($error); ?>
    </div>
</body>
</html>