define("action/base", [], function(){
    var _self;
    var _model = {
        _options: null,
        _target : null,
        parse : function(target, options) {
            if ($(target).data("action_init")) {
                return;
            }
            _self._options = options;
            _self._target = target;
            if (_self['init']) {
                _self.init();
            }
            $(target).data("action_init", true);
        },
        extend : function(object) {
            for (var property in _model) {
                object[property] = _model[property];
            }
            _self = object;

            return object;
        }
    };


    return _model;
});