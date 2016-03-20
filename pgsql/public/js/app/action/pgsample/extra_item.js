define("action/pgsample/extra_item", ["action/base"], function(base){
    var _model = base.extend({});

    _model.init = function() {
        var $target = $(_model._target), $add_btn = $target.find("a.add-btn"), $sub_btn = $target.find("a.sub-btn"), $parent = $target.parent();
        $add_btn.on("click", function(e){
            e.stopImmediatePropagation();

            $target.clone().appendTo($parent);
            require(["app"], function(app){
                app.render();
            });
        });

        $sub_btn.on("click", function(e){
            e.stopImmediatePropagation();

            $target.remove();
        });
    }

    return _model;
});