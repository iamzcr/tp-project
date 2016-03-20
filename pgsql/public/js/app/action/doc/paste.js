define("action/doc/paste", ["action/base"], function(base){

    var _model = base.extend({});

    var reader = new FileReader();
    $(reader).on('load', function(e){
        console.log(e.target.result);
    });

    _model.init = function() {
        var $doc = $(document), $body = $("body");
        function onpaste(e) {
            console.log(e);
            var items = (e.clipboardData || e.originalEvent.clipboardData).items;
            if (items.length > 0 ) {
                var target = items[0], _url = _model._options["url"];
                if (target["kind"] == "file" && target["type"] == "image/png") {
                    var _data = new FormData();
                    _data.append("upload", target.getAsFile(), "upload.png");
                    $('.loading').removeClass('hidden');
                    $body.addClass("loading-status");
                    $body.off("paste", onpaste);
                    $.ajax({
                        type: 'post',
                        url: _url,
                        data: _data,
                        dataType : 'json',
                        processData: false,
                        contentType: false,
                        success : function(res) {
                            if (res && res['status']) {
                                console.log(res['item']);
                                $doc.trigger("action.upload.success", [res]);
                            }
                        },
                        complete: function (xhr) {
                            var res = xhr.responseJSON;
                            if (res && res['error'] && !res['status']) {
                                var msg = '';
                                if (res['error'] instanceof Object) {
                                    for (var k in res['error']) {
                                        msg += res["error"][k]+"\n";
                                    }
                                } else{
                                    msg = "" + res["error"];
                                }
                                alert(msg);

                                $('.loading').addClass('hidden');
                                $body.removeClass("loading-status");
                                $body.on("paste", onpaste);
                            }
                        }
                    });
                }
            }
        }
        $body.on("paste", onpaste);
        $body.focus();
    };

    return _model;
});