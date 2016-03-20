/**
 * ajax添加菜单
 */
$(".temp-submit").click(function(){
    var data = $("#temp-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})