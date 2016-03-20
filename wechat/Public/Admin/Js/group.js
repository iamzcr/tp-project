/**
 * ajax添加菜单
 */
$(".group-submit").click(function(){
    var data = $("#group-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})