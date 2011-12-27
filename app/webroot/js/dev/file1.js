/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    
    var $file1_cltClientListWrp = $("#clt-clientListWrp");
    var $file1_selectedItem = null;
    
    var f_file1_getClientsList = function(){
                
        $.ajax({
            dataType:"json",
            url: path+"\/clients\/getYnClData",
            //url: "http:\/\/yzk.go\/clients\/getYnClData",
            type: "POST",
            data: {
               
               "hi":"test"         
            },
            success:function (data, textStatus) {
                

                if( data.data) {
                    //alert('success'); 
                    
                    $file1_cltClientListWrp.find(".clt-clientHd").show();
                    $("#clt-clientListTmpl").tmpl(data.data).appendTo($file1_cltClientListWrp);
                         


                } else if(data.error){
                    alert("Error here: "+data.error);
                } else if(data.error_code){
                    alert(data.error_code+' | '+data.error_detail+' | '+data.error_str);
                } else {
                    //flash_message("Couldn't be deleted", "fler" );
                    alert('No Data')
                }
                
                $("#clt-navBarWrp").find(".clt-loader").hide();
            },
                    
            error:function(){
                alert('Problem with the server. Try again later.');
                 $("#clt-navBarWrp").find(".clt-loader").hide();
            }
            
            
        }); 

    };
 
    f_file1_getClientsList();
    
    $file1_cltClientListWrp.delegate(".clt-client","mouseenter",function(){
        $(this).addClass("clt-clientHgl");
    })
    $file1_cltClientListWrp.delegate(".clt-client","mouseleave",function(){
        $(this).removeClass("clt-clientHgl");
    })
    
   
    
    
    var f_file1_usrRegView = function(event){
        
        event.preventDefault();
        
        //close previously selected item;
        if($file1_selectedItem){
            $file1_selectedItem.tmpl = $("#clt-clientListTmpl").template();
            $file1_selectedItem.update();
        }
 
 
        
        var $thisWrp = $(this).parent(".clt-client");
        $file1_selectedItem  = $.tmplItem(this);
        $file1_selectedItem.tmpl = $("#clt-clientRegtTmpl").template();
        $file1_selectedItem.update(); 
        
    };


    $file1_cltClientListWrp.delegate(".clt-viewUsr","click",f_file1_usrRegView);


    $file1_cltClientListWrp.delegate("#clt-closeFormBtn","click",function(){
        
         if($file1_selectedItem){
            $file1_selectedItem.tmpl = $("#clt-clientListTmpl").template();
            $file1_selectedItem.update();
            $file1_selectedItem = null;
        }       
        
        
    });





    $file1_cltClientListWrp.delegate("#clt-cltRegBtn","click",function(){
        
        $("#clt-regFormLoader").show();
        
        var $this = $(this);
        var $thisWrp = $this.parents(".clt-cltRegWrp");
        var tmplItem = $.tmplItem( $thisWrp );
        var userYnLogin = tmplItem.data.Login;
        var thisEmail = $("#clt-inputEmail").val();
        
        $.ajax({
            dataType:"json",
            url: path+"\/clients\/regYnCl",
            type: "POST",
            data: {
                "data[ynLogin]":userYnLogin,
                "data[email]":thisEmail                      
            },
            success:function (data, textStatus) {
                
                $("#clt-regFormLoader").hide();
                
                if( data.savedUserId) {
                    
                    if($file1_selectedItem){
                        $file1_selectedItem.tmpl = $("#clt-clientListTmpl").template();
                        $file1_selectedItem.data = tmplItem.data;
                        $file1_selectedItem.data.reg = "yes";
                        $file1_selectedItem.update();
                        //@todo add coloranimation
                        $($file1_selectedItem.nodes[0]).css({"background-color":"PaleGoldenRod"});
                        $file1_selectedItem = null;
                    } 
                    
                } else if(data.error){
                    alert("Error here: "+data.error);
                } else if(data.error_code){
                    alert(data.error_code+' | '+data.error_detail+' | '+data.error_str);
                } else {
                    //flash_message("Couldn't be deleted", "fler" );
                    alert('No Data');
                }
            },
                    
            error:function(){
                alert('Problem with the server. Try again later.');
                $("#clt-regFormLoader").hide();
            }
        });        
        
        
        
        
    });








 
});