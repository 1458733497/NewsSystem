/**
 * 后台管理公用JS
 * @author changming
 */

// 打开添加页面
$("#button-add").click(function() {
    var url = SCOPE.add_url;
    window.location.href = url;
});

// 编辑操作
$('.singcms-table #singcms-edit').on('click', function() {
    // 获取要编辑记录的id，作为URL参数
    var id = $(this).attr('attr-id');
    var url = SCOPE.edit_url + '&id='+id;
    window.location.href = url;
});

// 逻辑删除操作
$('.singcms-table #singcms-delete').on('click',function() {
    var id = $(this).attr('attr-id');
    var a = $(this).attr("attr-a");
    var message = $(this).attr("attr-message");
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;
    data['status'] = -1;

    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定" + message,
        scrollbar: true,
        yes: function(){
            // 实际跳转
            todelete(url, data);
        },
    });
});
function todelete(url, data) {
    $.post(url, data, function(s){
            if(s.status == 1) {
                return dialog.success(s.message,'');
            }else {
                return dialog.error(s.message);
            }
        }
    ,"JSON");
}

// 点击状态按钮修改状态
$('.singcms-table #singcms-on-off').on('click', function() {
    // 获取id和当前状态
    var id = $(this).attr('attr-id');
    var status = $(this).attr("attr-status");
    var url = SCOPE.set_status_url;
    data = {};
    data['id'] = id;
    data['status'] = status;
    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定更改状态",
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data);
        },

    });
});

// 排序操作
$('#button-listorder').click(function() {
    var data = $("#singcms-listorder").serializeArray();
    postData = {};
    $(data).each(function(i){
       postData[this.name] = this.value;
    });
    var url = SCOPE.listorder_url;
    $.post(url,postData,function(result) {
        if(result.status == 1) {
            //成功
            return dialog.success(result.message,result['data']['jump_url']);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.message,result['data']['jump_url']);
        }
    },"JSON");
});

//提交表单操作
$("#singcms-button-submit").click(function() {
    // 将表单对象转化为数组
    var data = $("#singcms-form").serializeArray();
    // 数据规范化，用于post方式提交
    postData = {};
    $(data).each(function(i) {
       postData[this.name] = this.value;
    });
    // 将获取到的数据post给服务器
    url = SCOPE.save_url;
    jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result) {
        if(result.status == 1) {
            //成功
            return dialog.success(result.message,jump_url);
        }else if(result.status == 0) {
            // 失败
            return dialog.error(result.message);
        }
    },"JSON");
});

// 推送操作
$("#singcms-push").click(function() {
    // 获取推荐位id
    var id = $("#select-push").val();
    if(id==0) {
        return dialog.error("请选择推荐位");
    }
    // 要推送的文章id列表
    push = {};
    postData = {};
    // 获取选中的文章id
    $("input[name='pushcheck']:checked").each(function(i){
        push[i] = $(this).val();
    });

    postData['push'] = push;
    postData['position_id']  =  id;
    var url = SCOPE.push_url;
    $.post(url, postData, function(result) {
        if(result.status == 1) {
            // TODO
            return dialog.success(result.message,result['data']['jump_url']);
        }
        if(result.status == 0) {
            // TODO
            return dialog.error(result.message);
        }
    },"json");
});