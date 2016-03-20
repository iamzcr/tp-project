/**
 * Created by jfengjiang on 2015/9/11.
 */
$(function () {
    var stack = [];
    var path, checkbox2;
    path = $('.container').attr('data-url');
    var $container = $('.js_container');
    $container.on('click', '.js_cell[data-id]', function () {
        var id = $(this).data('id');
        var $tpl = $($('#tpl_' + id).html()).addClass('slideIn').addClass(id);
        $container.append($tpl);
        stack.push($tpl);
        history.pushState({id: id},'', '?step='+ id);
        $($tpl).on('webkitAnimationEnd', function (){
            $(this).removeClass('slideIn');
        }).on('animationend', function (){
            $(this).removeClass('slideIn');
        });
        // tooltips
        if (id === "step_5") {
            var data = $('#my-form').serialize();
            $('#loadingToast').show();
            $.post( path,data,function(json){
                    if(json.status == 1){
                        setTimeout(function () {
                            $('#loadingToast').hide();
                        }, 2000);
                        setTimeout(function () {
                            $('#toast').show();
                        }, 2000);
                        setTimeout(function () {
                            window.location.replace(json.url);
                        }, 3000);
                    }
             })
        }
    });
    // webkit will fired popstate on page loaded
    $(window).on('popstate', function () {
        var $top = stack.pop();
        if (!$top) {
            return;
        }
        $top.addClass('slideOut').on('animationend', function () {
            $top.remove();
        }).on('webkitAnimationEnd', function () {
            $top.remove();
        });
    });

});