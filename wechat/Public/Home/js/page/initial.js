/**
 * Created by Administrator on 15-11-11.
 */
var $container = $('.js_container');
function hideActionSheet(weuiActionsheet, mask) {
    weuiActionsheet.removeClass('weui_actionsheet_toggle');
    mask.removeClass('weui_fade_toggle');
    weuiActionsheet.on('transitionend', function () {
        mask.hide();
    }).on('webkitTransitionEnd', function () {
        mask.hide();
    })
}
$container.on('click','#showActionCycle', function () {

    var mask = $('#mask_cycle');
    var weuiActionsheet = $('#weui_actionsheet_cycle');
    weuiActionsheet.addClass('weui_actionsheet_toggle');

    $('.click_cell_cycle').click(function(){
        var val_date =  $(this).data('daily');
        $('#cycle').val(val_date);
        hideActionSheet(weuiActionsheet, mask);
    });

    mask.show().addClass('weui_fade_toggle').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    $('#actionsheet_cycle_cancel').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');

});

$container.on('click','#showActionSheet', function () {

    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    weuiActionsheet.addClass('weui_actionsheet_toggle');

    $('.click_cell').click(function(){
        var val_date =  $(this).data('daily');
        $('#sheet').val(val_date);
        hideActionSheet(weuiActionsheet, mask);
    });

    mask.show().addClass('weui_fade_toggle').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    $('#actionsheet_cancel').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
});
$('.initial-footer-btn').click(function(){

    var path = $(this).data('url');
    var come_date =  $('.in-date').val();
    var cycle = $('#cycle').val();
    var stay_date = $('#sheet').val();

    $.post(path ,{come_date:come_date,cycle:cycle,stay_date:stay_date},function(json){
        if(json.status === 1){
            alert('记录成功');
            location.href = json.path;
        }else{
            alert(json.info);
        }
    })
})
