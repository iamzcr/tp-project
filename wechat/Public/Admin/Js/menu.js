/**
 * ajax添加菜单
 */
$(".menu-submit").click(function(){
    var data = $("#menu-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})
/**
 * ajax同步菜单
 */
$("#button-sys").click(function(){
    var path = $(this).data("url");
    alert(path);
    $.get(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})
