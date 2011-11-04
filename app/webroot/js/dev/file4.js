/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    var $file4_cmpClientBannerWrp = $("#cmp-clientBannerWrp");
    var $file4_selectedItem = null;
    var $file4_checkBoxes = null;
    var $file4_checkBoxesCh = null;
    var $file4_modeEditBtn = null;
    var $file4_modeDelBtn = null;
    
    var f_file4_getClientsCampInfo = function(){
                
        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/getYnBanInfo",
            type: "POST",
            data: {
                "data[method]":'GetBannerPhrases',
                "data[bannid]":$('#cmp-clientBannerWrp').data("bannid")
            },
            success:function (data, textStatus) {
                if( data.data) {
                    $file4_cmpClientBannerWrp.find(".clt-loader").hide();
                    $file4_cmpClientBannerWrp.find(".clt-clientHd").show();
                    $("#cmp-clientBannerTmpl").tmpl(data.data).appendTo($file4_cmpClientBannerWrp);

                    $file4_checkBoxes = $file4_cmpClientBannerWrp.find("input:checkbox");
                    //$file4_checkBoxesCh = $file4_cmpClientBannerWrp.find('input[id|="ch"]');
                    $file4_modeEditBtn = $file4_cmpClientBannerWrp.find('.cmp-edit');
                    $file4_modeDelBtn = $file4_cmpClientBannerWrp.find('.cmp-delete');
                    
                    
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
 
    f_file4_getClientsCampInfo();
 
 
 
 
 
 
 
    $file4_cmpClientBannerWrp.delegate(".cmp-client","mouseenter",function(){
        $(this).addClass("clt-clientHgl");
    })
    $file4_cmpClientBannerWrp.delegate(".cmp-client","mouseleave",function(){
        $(this).removeClass("clt-clientHgl");
    })
 
 
 
 
 
 
 
 
 
    $file4_cmpClientBannerWrp.delegate(".cmp-edit","click",function(){
         
        var $this = $(this);
        var $thisParent = $this.parents(".cmp-client");
        var $thisModesEditor = $thisParent.find(".cmp-modesEditWrp");
        $file4_cmpClientBannerWrp.find(".cmp-modes").remove().end().find(".cmp-client").removeClass("cmp-clientActive");
        $thisParent.addClass("cmp-clientActive");
       
        $("#cmp-modesTmpl").tmpl().appendTo($thisModesEditor);
        
    })

    $file4_cmpClientBannerWrp.delegate(".cmp-close","click",function(){
        var $this = $(this);
        var $thisParent = $this.parents(".cmp-client");
        
        $thisParent.find(".cmp-modes").hide().end().removeClass("cmp-clientActive");

    })



    //saving phrase.
    var f_file4_savePhraseMode = function(action){

        var $this = $(this);
        var $thisParent = $this.parents(".cmp-client");

        $file4_selectedItem  = $.tmplItem($thisParent);
        
        var mode = $thisParent.find("select").val();
        
        var modeX = $thisParent.find(".cmp-xinput").val();

        if(mode ==""){
            alert("Please, select the mode");
            return;
        }
        if(modeX ==""){
            alert("Please, select the number");
            return;
        }


        //var tmplItem = $thisParent.tmplItem();
        
        //        var campId = tmplItem.data.CampaignID;
        //        var banId = tmplItem.data.BannerID;
        //        var phrId = tmplItem.data.PhraseID;
        
        var campId = $file4_selectedItem.data.CampaignID;
        var banId = $file4_selectedItem.data.BannerID;
        var phrId = $file4_selectedItem.data.PhraseID; 
        
        var modeData = {
            "data[mode]":mode,
            "data[modeX]":modeX,
            "data[campId]":campId,
            "data[banId]":banId,
            "data[phrId]":phrId
        };
 
        
           
        $.ajax({
            dataType:"json",
            url: path+"\/phrases\/savePhrMode",
            type: "POST",
            data: modeData,
            success:function (data, textStatus) {
                if( data.data) {
                    //alert(data.data.mode);
                    $file4_selectedItem.data.mode = data.data.mode;
                    $file4_selectedItem.update();
                    $file4_selectedItem = null;     


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

    $file4_cmpClientBannerWrp.delegate(".cmp-save","click",f_file4_savePhraseMode);
         


        
        
 
     
    

    
    $file4_cmpClientBannerWrp.delegate(".cmp-delete","click",function(){
       
        if (confirm('Are you sure to delete?')) {
            var $this = $(this);
            var $thisParent = $this.parents(".cmp-client");
        
            $file4_selectedItem  = $.tmplItem($thisParent);

            var campId = $file4_selectedItem.data.CampaignID;
            var banId = $file4_selectedItem.data.BannerID;
            var phrId = $file4_selectedItem.data.PhraseID; 
        
            var modeData = {
                "data[campId]":campId,
                "data[banId]":banId,
                "data[phrId]":phrId
            }; 
        
            $.ajax({
                dataType:"json",
                url: path+"\/phrases\/delPhr",
                type: "POST",
                data: modeData,
                success:function (data, textStatus) {
                    if( data.data) {
                        //alert(data.data.mode);
                        $file4_selectedItem.data.mode = null;
                        $file4_selectedItem.update();
                        $file4_selectedItem = null;     

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
        }
    })
    








    var $file4_cmpSetModeWrp = $("#cmp-setModeWrp");
    
    $("#cmp-modesTmpl").tmpl().appendTo($file4_cmpSetModeWrp);
    
    $("#cmp-setModeBtn").click(function(){
        var $this = $(this);
        
        if( $file4_cmpSetModeWrp.is(":hidden") ){
           $file4_checkBoxes.prop({"disabled":false});
           $file4_checkBoxes.prop({"checked":true});
           $file4_cmpSetModeWrp.show();
           $file4_modeEditBtn.removeClass('cmp-edit').addClass('cmp-editDis');
           $file4_modeDelBtn.removeClass('cmp-delete').addClass('cmp-deleteDis');
           $file4_cmpClientBannerWrp.find(".cmp-modes").remove().end().find(".cmp-client").removeClass("cmp-clientActive");
        } else {
           $file4_checkBoxes.prop({"disabled":"disabled"}); 
           $file4_checkBoxes.prop({"checked":false}); 
           $file4_cmpSetModeWrp.hide();
           $file4_modeEditBtn.removeClass('cmp-editDis').addClass('cmp-edit');
           $file4_modeDelBtn.removeClass('cmp-deleteDis').addClass('cmp-delete');
        }
        

    })    

    $("#toMode").click(function(){
        var $this = $(this);
        if($this.prop("checked") == true){
           $file4_cmpClientBannerWrp.find('input[id|="ch"]').prop({"checked":true});
        } else if($this.prop("checked") == false) {
           $file4_cmpClientBannerWrp.find('input[id|="ch"]').prop({"checked":false}); 
        }      
    })

 
    var f_file4_savePhraseModeAll = function(){
        
        var $this = $(this);
        
        var $phrases = $file4_cmpClientBannerWrp.find(".cmp-client");
        var allData = new Object;
        var updated = new Object;
        $phrases.each(function(i){
            updated[i] = 0;
            if( $(this).find('input[id|="ch"]').next().prop("checked") == true ){
                
                var $item  = $.tmplItem(this);
                allData[i] = new Object;
                allData[i].phId = $item.data.PhraseID;
                allData[i].bnId = $item.data.BannerID;
                allData[i].cmId = $item.data.CampaignID;
                updated[i] = 1;
            } 
        }) 
        //console.log(allData);
        
        var mode = $("#cmp-setModeWrp").find("select").val();
        
        var modeX = $("#cmp-setModeWrp").find(".cmp-xinput").val();

        if(mode ==""){
            alert("Please, select the mode");
            return;
        }
        if(modeX ==""){
            alert("Please, select the number");
            return;
        }       
        
        var modeData = {
            "data[mode]":mode,
            "data[modeX]":modeX,
            "data[ph]":allData
        };
        
        
        $.ajax({
            dataType:"json",
            url: path+"\/phrases\/savePhrModeAll",
            type: "POST",
            data: modeData,
            success:function (data, textStatus) {
                if( data.data) {
                    //alert(data.data.mode);
                    
                    $phrases.each(function(i){
                        
                        if(updated[i] == 1){
                           var $item  = $.tmplItem(this);
                           $item.data.mode = data.data.mode;
                           $item.update();                           
                           
                        }
                        
                    });
                    
                    

    


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
 
    $("#cmp-setModeWrp").find(".cmp-save").click(f_file4_savePhraseModeAll);
 
    $("#cmp-unSetModeBtn").click(function(){
        alert('Not done yet, sorry');
    });
    
});