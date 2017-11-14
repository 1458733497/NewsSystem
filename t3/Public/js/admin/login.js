// 前端登陆控制相关js
var login = {
    check : function() {
        // 获取登陆页面中的用户名和密码
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();

        // 前端检测非空
         if (!password) {
            dialog.error('密码不能为空！');
        }
        if (!username) {
            dialog.error('用户名不能为空！');
        }
        alert(username+1);

                alert(password+2);

    }
}