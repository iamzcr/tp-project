define("action/focus/select", ["action/base"], function(base){
    var _model = base.extend({});


    _model.init = function() {
        var $target = $(_model._target);
        $target.on("focus mouseover", function(e){
            $target.blur();
            this.setSelectionRange(0, this.value.length)
            return false;
        });
    }

    return _model;
});
