/**
 * ajax添加菜单
 */
$(".text-submit").click(function(){
    var data = $("#text-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})