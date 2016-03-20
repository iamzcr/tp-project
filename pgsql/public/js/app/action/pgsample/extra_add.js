define("action/pgsample/extra_add", ["action/base"], function(base){
    var _model = base.extend({});

    _model.init = function() {
        var $target = $(_model._target), $add_btn = $target.find("a.btn-default"), $template_script = $target.find("script"), $wrapper = $("#extra_wrapper");
        $add_btn.on("click", function(e){
            e.stopImmediatePropagation();

            $($template_script.html()).appendTo($wrapper);
            require(["app"], function(app){
                app.render();
            });
        });
    }

    return _model;
});