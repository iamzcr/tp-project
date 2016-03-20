/**
 * ajax添加菜单
 */
$(".mass-submit").click(function(){
    var data = $("#mass-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})