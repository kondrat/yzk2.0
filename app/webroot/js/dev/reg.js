jQuery(document).ready( function(){
	
    var $reg_username = $("#ur-userNameReg");
    var $reg_email = $("#ur-userEmailReg");
    var $reg_token = $("input[id^='Token']");
    var $reg_validEmail = '';
    var $reg_usercaptcha = $("#ur-userCapchaReg");
    var $reg_userpass = {
        pass1: $("#ur-userPassReg1"),
        pass2: $("#ur-userPassReg2")
    };




    //getting new captcha
    $('.capReset span, #capImg').click( function() {
        var Stamp = new Date();
        $('#capImg').attr( {
            src: path+"/users/kcaptcha/"+Stamp.getTime()
        } );
    });


	
    $("#UserRegForm input").blur(function(){
        if( $(this).val().length === 0 ) {

            $(this).parents(".ur-inputFormWrap").find(".ur-formWrapTip div").hide();
        }
    });	
    
    $("#UserRegForm input").focus(function(){	
        if( $(this).val().length === 0 ) {
            $(this).parents(".ur-inputFormWrap").find(".ur-formWrapTip div").hide();
            $(this).parents(".ur-inputFormWrap").find(".ur-formWrapTip div:first").show();
        }	
    });
	

	
    var inpStrTimer;
    $reg_email.keyup( function(e) {
		
        //alert(e.which);
        /*
	  var chr = (String.fromCharCode(e.which));
	  rexp = /([^a-zA-Z0-9])/; 
	  if( rexp.test(chr) && e.which !== 8 ) {
	    return false;
	  } 
	  */
		
        var InputStr = $(this).val();
		
        $("#rEmail div").hide();	
        $("#rEmailCheck").show();
				
        window.clearInterval(inpStrTimer);
        inpStrTimer = window.setInterval( function() {
			
            if( InputStr.length > 0 ){
                
                var emailRegEx =/.+@.+\..+/; 
			
                if( !InputStr.match(emailRegEx) ) {				
                    $("#rEmail div").hide();				
                    $("#rEmailError").show().text(rErr.email.email);
                    return false;
                }             
                
                
                $.ajax({
                    type: "POST",
                    url: path+"/users/userNameCheck/",
                    data: {
                        "data[User][email]": InputStr, 
                        "data[_Token][key]": $reg_token.val()
                    },
                    dataType: "json",
									
                    success: function (data) {
                        if (data.stat == 1) {
										  	
                            $('#rEmail div').hide();
                            $("#rEmailOk").show();
                        } else {
                            $('#rEmail div').hide();
                            $("#rEmailError").show();
                            $.each(rErr.email , function(key,value){
                                if( key === data.error ) {
                                    $("#rEmailError").text(value);
                                }
                            });
										  	
                        }
                    },
                    error: function(response, status) {
                        alert('An unexpected error has occurred!');
                    //$('.tempTest').html('Problem with the server. Try again later.');
                    }

									
                });
					
            } else {
                $("#rEmailCheck").hide();
                $("#rEmailTip").show();
            }
					
            window.clearInterval(inpStrTimer);
        }, 1000
        );
		


    });





    $reg_userpass.pass1.passStrengthCheck(
        "#rPass1Check",															        		
        {
            username: function(){
                return $reg_username.val();
            },
            minlength: 4,
            maxlength: 16
        }																				
        ).passIdentCheck(1,$reg_userpass);






    $reg_userpass.pass2.passIdentCheck(2,$reg_userpass);
																        	
    // ur-headLayoutLogin
   
    
    var $testInput = $("#ur-topLogEmal,#ur-topLogPass");
    
    
    
    $testInput.each(function(){
        var $this = $(this);
        $this.val("");      
    });
    
    var $reg_headLayoutInputWrp = $(".ur-headLayoutInputWrp");
    

    $reg_headLayoutInputWrp.mouseenter(function() {
        var $this = $(this);
        var $thisInput = $this.find("input");
        if($thisInput.val().length == 0){
            $this.addClass("ur-topLogInputAct");
        }
    }).mouseleave(function() {
        var $this = $(this);
        $this.removeClass("ur-topLogInputAct");        
    });   



    $reg_headLayoutInputWrp.focusin(function(){
        var $this = $(this);
        var $thisInput = $this.find("input");
        if($thisInput.val().length == 0){
            $this.removeClass("ur-topLogInputAct");
            $this.addClass("ur-topLogInputFocus");
        } else {
            $this.removeClass("ur-topLogInputAct");
            $this.removeClass("ur-topLogInputFocus");
             $this.find("label").hide();
             $this.addClass("ur-topLogInputDone");
        }
    }).focusout(function(){        
        $reg_headLayoutInputWrp.each(function(){
            var $this = $(this);
            var $thisInput = $this.find("input");
            if($thisInput.val().length == 0){
                $this.find("label").show();
                $this.removeClass("ur-topLogInputDone");
                $this.removeClass("ur-topLogInputFocus");
            }      
        });       
    });
    $reg_headLayoutInputWrp.keyup(function(){
        var $this = $(this);
        var $thisInput = $this.find("input");
        if($thisInput.val().length > 0){
            $this.find("label").hide();
            $this.removeClass("ur-topLogInputFocus");
            $this.addClass("ur-topLogInputDone");
            $thisInput.attr({"autocomplete":"on"});
        } else {
            $thisInput.attr({"autocomplete":"off"});
        }
       
    });

    //???????????????????
    $("img").error(function(){
        $(this).hide();
    });

									
}
);