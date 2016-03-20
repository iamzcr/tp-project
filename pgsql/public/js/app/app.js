define("app", [], function(){

    function parse() {

        $("*[data-mvc-action]").each(function(index, ele){
            var action_name = $(ele).data("mvc-action"),
                action_option = $(ele).data("action-options");
            action_name = "action/" + action_name;
            require([action_name], function(action){
                if (action['parse']) {
                    action.parse(ele, action_option);
                }
            });
        });
    }

    parse();


    var $win = $(window), $body = $("body"), $doc = $(document);


    return {
        render: function() {
            parse();
        }
    }
});