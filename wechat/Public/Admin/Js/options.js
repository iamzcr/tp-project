/**
 * Created by Administrator on 16-1-19.
 */
/**
 * Created by Administrator on 15-12-5.
 */
$(".options-submit").click(function(){
    var data = $("#options-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})