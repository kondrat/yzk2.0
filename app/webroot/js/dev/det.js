/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready( function(){
    $("#ur-uploadBtn").click(function(){
        var $this = $(this);
        if($this.hasClass("ur-uploadBtnAct")){
            $this.removeClass("ur-uploadBtnAct");
        }else {
            $this.addClass("ur-uploadBtnAct");
        }
        $("#ur-uploadCert").slideToggle();
    });
});
