
$(".login-submit").click(function(){
    var data = $("#login-form").serialize();
    var path = $(this).data("url");
    $.post(path,data,function(json){
        alert(json.info);
        window.location.replace(json.url);
    });
})