//flash message
function flash_message(message, classs ) {

		$('div.fl').empty();
		$('div.fl').append('<div id="flashMessage" class="'+classs+'">'+message+'</div>');
				var $alert = $('#flashMessage');
				var alerttimer = window.setTimeout(function () {
					$alert.trigger('click');
				}, 4500);
				$alert.animate(
					{
						height: $alert.css('line-height') || '52px'
					}, 
					800
				).click(function () {
							window.clearTimeout(alerttimer);
							$alert.animate({height: '0'}, 400);
							$alert.css({'border':'none'});							
				});
}







//forms functions
$.fn.passStrengthCheck = function(strDiv,optionsObj){
		
	return this.each(function(){
	
		if(!strDiv){
			return;
		}
		
		if(!optionsObj){optionsObj={}}
		
		var passInput=$(this);
		
		var passStatusDomElem = $(strDiv);
		

		//passCheck block
		function strengthChecker(passStr){
				var point = 0;
				var minLengthPass = optionsObj.minlength?optionsObj.minlength:6;
				var maxLengthPass = optionsObj.maxlength?optionsObj.maxlength:15;
				
				if( passStr.length < minLengthPass ){
					return {
							score:passStr.length,
							message:"Too short",
							className:"notOkPass"
							}
				}
				if( passStr.length > maxLengthPass ){
					return {
							score:passStr.length,
							message:"Too Long",
							className:"notOkPass"
							}
				}
								
				if(optionsObj.username){
					var tt = (typeof (optionsObj.username)=="function")?optionsObj.username():optionsObj.username;
					if( tt && ( passStr.toLowerCase() == tt.toLowerCase() ) ){
						return{
								score:0,
								message:"Too obvious",
								className:"notOkPass"}
							}
				}
					
				//list of banned passwords
				/*
				if( $.inArray(passStr.toLowerCase(),var.BANNED_PASSWORDS)!=-1 ){
					return{score:0,message:"Too obvious",className:"notOkPass"}
				}
				*/
				//strong pass requaried
				/*
				if(optionsObj.requireStrong){
					size=10;
					var K="# ` ~ ! @ $ % ^ & * ( ) - _ = + [ ] { }  | ; : ' \" , . < > / ?".split(" ");
					K=$.map(K,function(R){return"\\"+R}).join("");
					var M=["\\d","[a-z]","[A-Z]","["+K+"]"];
					var O=$.map(M,function(R){return"(?=.*"+R+")"}).join("");
					if(!passStr.match(new RegExp("("+O+"){10,}"))){
						return{score:0,message:"Too Weak",className:"notOkPass"}
					}
				}
				*/
				
				point+=passStr.length*4;
				point+=( sameLetterCheck(1,passStr).length - passStr.length )*1;
				point+=( sameLetterCheck(2,passStr).length - passStr.length )*1;
				point+=( sameLetterCheck(3,passStr).length - passStr.length )*1;
				point+=( sameLetterCheck(4,passStr).length - passStr.length )*1;
				
				if( passStr.match(/(.*[0-9].*[0-9].*[0-9])/) ) {
					point+=5
				}
				if(passStr.match(/(.*[!@#$%^&*?_~].*[!@#$%^&*?_~])/)){
					point+=5
				}
				if( passStr.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) ) {
					point+=10
				}
				if( passStr.match(/([a-zA-Z])/)&&passStr.match(/([0-9])/) ) {
					point+=15 
				}
				if( passStr.match(/([!@#$%^&*?_~])/)&&passStr.match(/([0-9])/) ) {
					point+=15
				}
				if( passStr.match(/([!@#$%^&*?_~])/)&&passStr.match(/([a-zA-Z])/) ) { 
					point+=15
				}
				if( passStr.match(/^\w+$/)||passStr.match(/^\d+$/) ) { 
					point-=10
				}
				if(point < 0 ){point=0;}
				
				if(point > 100){point=100;}
				
				if(point < 34){return {score:point,message:"Weak",className:"weakPass"}}
				
				if(point < 50){return {score:point,message:"Good",className:"goodPass"}}
				
				if(point < 75) { 
					return {score:point,message:"Strong",className:"strongPass"}
				}
				
				return {score:point,message:"Very Strong",className:"bestPass"}
		}		
		function sameLetterCheck( num, passStr ){
			var finalStr = "";
			for( var i=0; i < passStr.length; i++ ){				
				var flag = true;				
				for( var j=0; j < num && (j+i+num) < passStr.length; j++ ){
					flag = flag && (passStr.charAt(j+i)==passStr.charAt(j+i+num))
				}					
				if( j < num ){flag=false;}				
				if( flag ){					
					i += num-1;
					flag = false;				
				}else{				
					finalStr += passStr.charAt(i);
				}					
			}
			return finalStr;
		}

		//removing an status class to the password statsus dom element
		function removeStatusClass( statusClass ){
			if( statusClass && passStatusDomElem.hasClass( statusClass ) ){
				return false
			}
			passStatusDomElem.removeClass("weakPass").removeClass("goodPass").removeClass("strongPass").removeClass("bestPass").removeClass("notOkPass");
			return true
		}
		
		function passCheckOutput(){
			
				var passStr = passInput.val();
								
				if( passStr.length === 0 ){
					removeStatusClass();
					passStatusDomElem.hide().prev().show();					
				}else{
					if( passStr.length ){
						passStatusDomElem.show().prev().hide();
					}
				}
				
				if( passStr.length > 0 ){
					//getting information about pass strength from checker
					var passCheckObj = strengthChecker( passStr );
					//adding message returned from checker
					passStatusDomElem.find("span:last").html(passCheckObj.message);				
					//adding class to dom element corresponding to the pass strength if needed
					if( removeStatusClass( passCheckObj.className ) ){
						passStatusDomElem.addClass(passCheckObj.className)
					}
					
				}
		}
				
		passInput.keyup( function(){ 
			passCheckOutput(); 
		});
	
		passInput.blur(function(){ 
				if(this.value.length === 0){
					removeStatusClass();
				}
		});
		
		if( passInput.val() ){			
				passCheckOutput();
				passStatusDomElem.show().next().hide();
		} 
		
	})
	
};    			


$.fn.passIdentCheck = function( passType, optionsObj ){
	
	return this.each(function(){
			
			if(!passType){ passType = 2; }
			if(!optionsObj){optionsObj={}}
			
			var timer;
		
			$(this).blur( function() {tt(passType,1);})
			$(this).keyup( function() {tt(passType,2);})

		
			function tt(passType,eventType) {

					window.clearInterval(timer);
					
					if( optionsObj.pass2.val().length > 0 ) {						
						if(  optionsObj.pass1.val() === optionsObj.pass2.val() ) {	
							//pass are equal, so OK.													
							$("#rPass2 div").hide();
							$("#rPass2Ok").show();							
						} else {		
							//only for field pass2					
							if( passType === 2 ) {
								$("#rPass2 div").hide();
								$("#rPass2Check").show();									
								timer = window.setInterval( function() { 
									//for interval period val may be changed
									if(  optionsObj.pass1.val() !== optionsObj.pass2.val() ) {															
										$("#rPass2 div").hide();
										$("#rPass2Error").show();	
									}
									window.clearInterval(timer);	
									}, 1000
								)									
							} else {
								$("#rPass2 div").hide();
								$("#rPass2Error").show();	
							}														
						}						
					} else {
						$("#rPass2 div").hide();
						//we show tip only for keyup, not for blur
						if(eventType === 2 && passType === 2){
							$("#rPass2Tip").show();
						} 
					}
					
			}		
	});
	
};

