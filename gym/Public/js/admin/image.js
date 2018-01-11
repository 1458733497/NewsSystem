/**
 * 通过uploadify插件实现图片上传功能
 */
$(function() {
    // 获取上传表单项地址
    $('#file_upload').uploadify({
        // 采用控件
        'swf'      : SCOPE.ajax_upload_swf,
        // 上传地址
        'uploader' : SCOPE.ajax_upload_image_url,
        // 按钮上的文字
        'buttonText': '上传图片',
        // 可选文件的描述（chrome不可用）
        'fileTypeDesc': 'Image Files',
        // 提交到后台脚本时，该上传文件名的key值
        'fileObjName' : 'file',
        //允许上传的文件后缀
        'fileTypeExts': '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            if(response) {
                //由JSON字符串转换为JSON对象
                var obj = JSON.parse(data);
                // 转换地址
                var longImgPath = '/gym' + obj.data;
                // 成功时显示的提示信息
                $('#' + file.id).find('.data').html(' 上传完毕');
                // 在前台显示已经上传成功的缩略图
                $("#upload_org_code_img").attr("src", longImgPath);
                $("#upload_org_code_img").show();
                // 在前台将上传成功的图片URL地址提交到表单
                $("#file_upload_image").attr('value',obj.data);
            }else{
                alert('上传失败');
            }
        },
    });
});





