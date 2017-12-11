/**
 * 向后台推送用户登录信息，根据返回的验证结果分别处理：若成功则跳转至用户后台，若不成功则返回登录页面
 * @author changming
 */
var login = {
    check : function() {
        // 获取登录页面中的用户名 和 密码
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        // 非空判断
        if (!username) {
            dialog.error('用户名不能为空');
        }
        if (!password) {
            dialog.error('密码不能为空');
        }
        // 后台接受地址为相同控制器的check方法
        var url = "./check";
        console.log(url);
        var data = {'username':username,'password':password};
        // 执行异步请求  $.post。第一参数为post地址，第二参数为提交的数据，第三参数为返回值处理，第四参数为格式
        $.post(url, data, function(result) {
            // 若验证失败，则弹出错误提示
            if(result.status == 0) {
                return dialog.error(result.message);
            }
            // 若验证成功，则弹出成功提示并在点击确定后跳转页面
            if(result.status == 1) {
                // 此处url跳转写了特化的/gym，目前没想到什么好方法（17.12.11）
                return dialog.success(result.message, '/gym/Admin');
            }
        },'JSON');

    }
}