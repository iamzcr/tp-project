/**
 * Created by Administrator on 15-12-5.
 */
$(".submit").click(function(){
    var data = $("form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})