/**
 * 调用layui插件，实现前端弹出层标准化控制
 * @author changming
 */
var dialog = {
    // 错误弹出层,传参为消息提示
    error : function(message) {
        layer.open({
            content:message,
            icon:2,
            title : '错误提示',
        });
    },

    //成功弹出层，传参1为消息提示，传参2为跳转链接
    success : function(message,url) {
        layer.open({
            content : message,
            icon : 1,
            yes : function() {
                // 跳转至指定链接
                location.href = url;
            },
        });
    },

    // 确认弹出层，传参1为消息提示，传参2为跳转链接。确认弹出层有“确认”和“取消”选项
    confirm : function(message, url) {
        layer.open({
            content : message,
            icon:3,
            btn : ['是','否'],
            yes : function() {
                location.href = url;
            },
        });
    },
}

