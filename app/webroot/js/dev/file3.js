/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    var $file3_cltClientCampInfoWrp = $("#cmp-clientCampInfoWrp");
    
    
    var f_file3_getClientsCampInfo = function(){
                
        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/getYnCampInfo",
            type: "POST",
            data: {
                "data[method]":'GetClientsInfo',
                "data[campid]":$('#cmp-clientCampInfoWrp').data("campid")
            },
            success:function (data, textStatus) {
                if( data.data) {
                    $file3_cltClientCampInfoWrp.find(".clt-loader").hide();
                    $file3_cltClientCampInfoWrp.find(".clt-clientHd").show();
                    $("#cmp-clientCompInfoTmpl").tmpl(data.data).appendTo($file3_cltClientCampInfoWrp);
                         


                } else if(data.error){
                    alert("Error here: "+data.error);
                } else if(data.error_code){
                    alert(data.error_code+' | '+data.error_detail+' | '+data.error_str);
                } else {
                    //flash_message("Couldn't be deleted", "fler" );
                    alert('No Data')
                }
            },
                    
            error:function(){
                alert('Problem with the server. Try again later.');
            }
        }); 

    };
 
    f_file3_getClientsCampInfo();
    
    $file3_cltClientCampInfoWrp.delegate(".cmp-client","mouseenter",function(){
        $(this).addClass("clt-clientHgl");
    })
    $file3_cltClientCampInfoWrp.delegate(".cmp-client","mouseleave",function(){
        $(this).removeClass("clt-clientHgl");
    })
 
 
});