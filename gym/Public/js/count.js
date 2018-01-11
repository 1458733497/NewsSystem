/**
 * 计数器
 * @author changming
 */

var newsIds = {};
// 轮询获取页面计数器对象数组
$(".news_count").each(function(i) {
    newsIds[i] = $(this).attr("news-id");
});

url = "/index.php?c=index&a=getCount";

$.post(url, newsIds, function(result) {
    if(result.status  == 1) {
        counts = result.data;
        $.each(counts, function(news_id,count) {
            $(".node-"+news_id).html(count);
        });
    }
}, "JSON");