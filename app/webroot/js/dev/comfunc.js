/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    var $com1_alertMessage = $('#flashMessage'); 
    // flash alert message
    if($com1_alertMessage.length) {
        var alerttimer = window.setTimeout(function () {
            $com1_alertMessage.trigger('click');
        }, 4500);
        $com1_alertMessage.animate({
            height: [$com1_alertMessage.css("line-height") || '52', 'swing']
        }, 400).click(function () {
            window.clearTimeout(alerttimer);
            $com1_alertMessage.animate({
                height: '0'
            }, 400);
            $com1_alertMessage.css({
                'border':'none'
            });
        });
    } 
});