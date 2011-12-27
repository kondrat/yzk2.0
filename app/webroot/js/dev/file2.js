/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function(){
    
    var $file2_campResWrp = $("#cmp-campResWrp");
    var $file2_cmpLoaderWrp = $("#cmp-loaderWrp");   
    var $file2_cmpNavBarCtrl = $("#cmp-navBarCtrl");  
    var $file2_selectedItem = null;
 
 
    // step 1. getting list of all clients campaigns
    
    var f_file2_getClientsCampList = function(){
         
        $file2_cmpLoaderWrp.show();
        
        
        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/getYnCampList",
            //url: "http:\/\/yzk.go\/campaigns\/getYnCampList",
            type: "POST",
            data: {
                "data[clname]":$file2_campResWrp.data("clname")
            },
            success:function (data, textStatus) {
                
                $file2_cmpLoaderWrp.hide();
                
                if( data.data) {
                    
                    $file2_campResWrp.empty();                                      
                    
                    $("#cmp-clientCompListHdTmpl").tmpl(data).appendTo($file2_campResWrp);
                    
                    $file2_cmpNavBarCtrl.find("span").removeClass("cmp-navBarAct").removeClass("cmp-navBarLnk").eq(0).addClass("cmp-navBarAct");

                    
                    $("#cmp-navBarCamName").hide().find("span").empty();
                    $("#cmp-navBarBnTlt").hide().find("span").empty();
                    $("#cmp-navBarBnTxt").hide().find("span").empty();



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
                $file2_cmpLoaderWrp.hide();
            }
        }); 

    };
 
    //srart of the campings content
    f_file2_getClientsCampList();






    // step 2. getting info about banners of given campaing

    var f_file2_getClientsCampInfo = function(){
        
        $file2_cmpLoaderWrp.show();

        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/getYnCampInfo",
            type: "POST",
            data: {                
                "data[campid]":$("#cmp-navBarCam").data("cmpId")
            },
            success:function (data, textStatus) {
                if( data.data) {
                    
                    $file2_cmpLoaderWrp.hide(); 
                    
                    $file2_campResWrp.empty();
                    
                    $("#cmp-clientCompInfoHdTmpl").tmpl(data).appendTo($file2_campResWrp);
                     
                    $file2_cmpNavBarCtrl.find("span").removeClass("cmp-navBarAct").removeClass("cmp-navBarLnk").eq(0).addClass("cmp-navBarLnk").end().eq(1).addClass("cmp-navBarAct");
                    
                    $("#cmp-navBarCamName").show().find("span").text($("#cmp-navBarCam").data("cmpName"));
                    
                    $("#cmp-navBarBnTlt").hide().find("span").empty();
                    $("#cmp-navBarBnTxt").hide().find("span").empty();                    
                    

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
                $file2_cmpLoaderWrp.hide();
            }
        }); 

    };




    $file2_campResWrp.delegate(".cmp-campLnk","click",function(event){
               
        event.preventDefault();
        
        var $thisParent = $(this).parents(".cmp-client");
        
        var $thisDataItem = $thisParent.tmplItem();
              
        $("#cmp-navBarCam").data({"cmpId":$thisDataItem.data.CampaignID,"cmpName":$thisDataItem.data.Name});
         
        f_file2_getClientsCampInfo();

        
    })


    




    // step 3. geting info about phrases of given banner

    var $file2_checkBoxes = null;
    var $file2_modeEditBtn = null;
    var $file2_modeDelBtn = null;
    
    var f_file2_getClientsBannInfo = function(){
         
        $file2_cmpLoaderWrp.show();
        
        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/getYnBanInfo",
            type: "POST",
            data: {
                "data[bannid]":$("#cmp-navBarBan").data("bnId")
            },
            success:function (data, textStatus) {
                if( data.data) {
                    
                    $file2_cmpLoaderWrp.hide();                    
                    $file2_campResWrp.empty();
                    
                    $("#cmp-clientBannerHdTmpl").tmpl(data).appendTo($file2_campResWrp);

                    //collect all edit/del buttons of each items
                    //$file2_modeEditBtn = $file2_campResWrp.find('.cmp-edit');
                    //$file2_modeDelBtn = $file2_campResWrp.find('.cmp-delete');
                    
                    $("#cmp-navBarCamName").show().find("span").text($("#cmp-navBarCam").data("cmpName"));
                    $("#cmp-navBarBnTlt").show().find("span").text($("#cmp-navBarBan").data("bnTtl"));
                    $("#cmp-navBarBnTxt").show().find("span").text($("#cmp-navBarBan").data("bnTxt"));
                    
                    $file2_cmpNavBarCtrl.find("span").removeClass("cmp-navBarAct").removeClass("cmp-navBarLnk")
                    .eq(0).addClass("cmp-navBarLnk").end()
                    .eq(1).addClass("cmp-navBarLnk").end()
                    .eq(2).addClass("cmp-navBarAct");
                    
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
                $file2_cmpLoaderWrp.hide();
            }
        }); 

    };



    $file2_campResWrp.delegate(".cmp-bannLnk","click",function(event){
               
        event.preventDefault();
        
        var $thisParent = $(this).parents(".cmp-client");
        
        var $thisDataItem = $thisParent.tmplItem();
                  
        $("#cmp-navBarBan").data({"bnId":$thisDataItem.data.BannerID,"bnTtl":$thisDataItem.data.Title,"bnTxt":$thisDataItem.data.Text});
          
        f_file2_getClientsBannInfo();

        
    })




    //control from navigation bar

    $("#cmp-navBar").delegate(".cmp-navBarLnk","click",function(){
       var $this = $(this);
       if($this.hasClass("cmp-navBarCam")){
         f_file2_getClientsCampList();  
       } else if($this.hasClass("cmp-navBarBan")){
         f_file2_getClientsCampInfo();  
       } else if($this.hasClass("cmp-navBarPhr")){
         f_file2_getClientsBannInfo();
       }
       
    })












    $file2_campResWrp.delegate(".cmp-edit","click",function(){
         
        var $this = $(this);
        var $thisParent = $this.parents(".cmp-client");
        var $thisModesEditor = $thisParent.find(".cmp-modesEditWrp");
        var thisTmplItem = $thisParent.tmplItem();
        
        
        $file2_campResWrp.find(".cmp-modes").remove().end().find(".cmp-client").removeClass("cmp-clientActive");
        $thisParent.addClass("cmp-clientActive");
       
        $("#cmp-modesTmpl").tmpl().appendTo($thisModesEditor);
        $thisParent.find("input:checkbox").prop({"checked":true});       
        $thisParent.find("option").each(function(){
            if($(this).val() == thisTmplItem.data.modeCode){
                $(this).prop("selected","selected");
                return;
            }
        });
        $thisParent.find("input.cmp-xinput").val(thisTmplItem.data.modeX);
        $thisParent.find("input.cmp-maxPrInput").val(thisTmplItem.data.maxPrice);
         
    })


    $file2_campResWrp.delegate(".cmp-close","click",function(){
        var $this = $(this);
        var $thisParent = $this.parents(".cmp-client");
        
        $thisParent.find(".cmp-modes").hide().end().removeClass("cmp-clientActive");
        $thisParent.find("input:checkbox").prop({"checked":false});
    })



 


    var f_file2_delPhraseMode = function(){
       
        if (confirm('Are you sure to delete?')) {
            var $this = $(this);
            var $thisParent = $this.parents(".cmp-client");
        
            $file2_selectedItem  = $.tmplItem($thisParent);

            var campId = $file2_selectedItem.data.CampaignID;
            var banId = $file2_selectedItem.data.BannerID;
            var phrId = $file2_selectedItem.data.PhraseID; 
        
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
                        
                        $file2_selectedItem.data.mode = null;
                        $file2_selectedItem.data.modeCode = null;
                        $file2_selectedItem.data.modeX = null;
                        $file2_selectedItem.data.maxPrice = null;
                        $file2_selectedItem.update();
                        $file2_selectedItem = null;     



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
    };

    $file2_campResWrp.delegate(".cmp-delete","click",f_file2_delPhraseMode);


    //mass or single update phrases. edit and delete

    $file2_campResWrp.delegate("#cmp-setModeBtn","click", function(){
        
        var $this = $(this);
        var checkBoxSet = $file2_campResWrp.find("input:checkbox");
        
        if( $this.hasClass("cmp-setModeBtnAct") ){
           
           $this.removeClass("cmp-setModeBtnAct");
           
           checkBoxSet.prop({"disabled":"disabled"}); 
           checkBoxSet.prop({"checked":false}); 
                          
           $file2_campResWrp.find('.cmp-editDis').removeClass('cmp-editDis').addClass('cmp-edit');
           $file2_campResWrp.find('.cmp-deleteDis').removeClass('cmp-deleteDis').addClass('cmp-delete');
           
           $("#cmp-setModeWrp").hide();
          
        } else {
 
           $this.addClass("cmp-setModeBtnAct");
            
           checkBoxSet.prop({"disabled":false});
           checkBoxSet.prop({"checked":true});
           
           $file2_campResWrp.find('.cmp-edit').removeClass('cmp-edit').addClass('cmp-editDis');
           $file2_campResWrp.find('.cmp-delete').removeClass('cmp-delete').addClass('cmp-deleteDis');
           $file2_campResWrp.find(".cmp-modes").remove().end().find(".cmp-client").removeClass("cmp-clientActive");
           
          
           $("#cmp-modesTmpl").tmpl().appendTo($("#cmp-setModeWrp"));           
           
           $("#cmp-setModeWrp").show();          

        }
        

    });    

    $file2_campResWrp.delegate("#cmp-setModeWrp .cmp-close","click", function(){
        $("#cmp-setModeBtn").trigger("click");
    });


    $file2_campResWrp.delegate("#toMode","click",function(){
        var $this = $(this);
        if($this.prop("checked") == true){
           $file2_campResWrp.find('input[id|="ch"]').prop({"checked":true});
        } else if($this.prop("checked") == false) {
           $file2_campResWrp.find('input[id|="ch"]').prop({"checked":false}); 
        }      
    })








    var f_file2_savePhraseModeAll = function(){
                
        var $phrases = $file2_campResWrp.find(".cmp-client");
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
        
        
        var mode = $(".cmp-modes").find("select").val();        
        var modeX = $(".cmp-modes").find(".cmp-xinput").val();
        var maxPrice = $(".cmp-modes").find(".cmp-maxPrInput").val();
        
        if(mode ==""){
            alert("Please, select the mode");
            return;
        }
        if(modeX ==""){
            alert("Please, select the number");
            return;
        }       
        if(maxPrice ==""){
            alert("Please, select the maximum price");
            return;
        }
        
        var modeData = {
            "data[mode]":mode,
            "data[modeX]":modeX,
            "data[maxPr]":maxPrice,
            "data[ph]":allData
        };
        
        
        $.ajax({
            dataType:"json",
            //url: path+"\/phrases\/savePhrModeAll",
            url: "http:\/\/yzk.go\/phrases\/savePhrModeAll",
            type: "POST",
            data: modeData,
            success:function (data, textStatus) {
                if( data.data) {
                                      
                    $phrases.each(function(i){
                        
                        if(updated[i] == 1){
                           var $item  = $.tmplItem(this);
                           $item.data.mode = data.data.mode;
                           $item.data.modeCode = data.data.modeCode;
                           $item.data.modeX = data.data.modeX;
                           $item.data.maxPrice = data.data.maxPrice;
                           $item.update(); 
                          
                        }
                                               
                    });

                    if($("#cmp-setModeBtn").hasClass("cmp-setModeBtnAct")){
                       $("#cmp-setModeBtn").trigger("click"); 
                    }
                       
                       
                       
                       

                    
    


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
 
    
    $file2_campResWrp.delegate(".cmp-save","click",f_file2_savePhraseModeAll);
    
 
    $("#cmp-unSetModeBtn").click(function(){
        alert('Not done yet, sorry');
    });


// im working here now
//to check the returned status before update

    $file2_campResWrp.delegate(".cmp-startStop","click",function(){
        
        var $this = $(this);
        var $thisParernt = $this.parents(".cmp-client");
        var $item  = $.tmplItem($thisParernt);
        var campID = $item.data.CampaignID;
        var campStatus = $item.data.StatusShow;
        var campData = {
            "data[campId]":campID,
            "data[statShow]":campStatus
        };
        $.ajax({
            dataType:"json",
            url: path+"\/campaigns\/startStop",
            type: "POST",
            data: campData,
            success:function (data, textStatus) {
                if( data.data) {
                  if($item.data.StatusShow == 'Yes'){
                    $item.data.StatusShow = "No";   
                  } else {
                     $item.data.StatusShow = "Yes"; 
                  }
                                     
                  $item.update();
              
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
    })


    $file2_campResWrp.delegate(".cmp-dayBud","click",function(){
        var $this = $(this);
        var $thisParernt = $this.parents(".cmp-client");
        var $item  = $.tmplItem($thisParernt);
        $item.tmpl = $("#cmp-clientCompBugTmpl").template();
        $item.update();
        var dayLimCurVal = '';
        if($item.data.dayLim > 0 ){
            dayLimCurVal = $item.data.dayLim;
        }
        $($item.nodes).find('input').css({"color":"red"}).val(dayLimCurVal).focus();
    })

    $file2_campResWrp.delegate(".cmp-budClose","click",function(){
        var $this = $(this);
        var $thisParernt = $this.parents(".cmp-client");
        var $item  = $.tmplItem($thisParernt);
        $item.tmpl = $("#cmp-clientCompListTmpl").template();
        $item.update();
        
    })


    $file2_campResWrp.delegate(".cmp-budOk","click",function(){
        
        var $this = $(this);
        var $thisParernt = $this.parents(".cmp-client");
        var $item  = $.tmplItem($thisParernt);
        var dayBud = 0;
        var dayBudInputVal = $thisParernt.find(".cmp-budInput").val();
        if(dayBudInputVal != ''){
            dayBud = parseFloat($thisParernt.find(".cmp-budInput").val()); 
        }
            
        
        
        
        
        var dayBudData = {
            "data[campId]": $item.data.CampaignID,
            "data[dbLim]":dayBud
        }; 
        if(!isNaN(dayBud) ){
            $.ajax({
                dataType:"json",
                url: path+"\/campaigns\/dayBud",
                type: "POST",
                data: dayBudData,
                success:function (data, textStatus) {
                    if( data.dayLim || data.dayLim === 0) {
                        
                       $item.data.dayLim = data.dayLim;
                       $item.data.stoped = data.stoped;
                       $item.data.StatusShow = data.StatusShow;
                      //$item.data

                    } else if(data.error){
                        alert("Error here: "+data.error);
                    } else if(data.error_code){
                        alert(data.error_code+' | '+data.error_detail+' | '+data.error_str);
                    } else {
                        //flash_message("Couldn't be deleted", "fler" );
                        alert('No Data')
                    }
                    
                    $item.tmpl = $("#cmp-clientCompListTmpl").template();
                    $item.update();                   
                                       
                },

                error:function(){
                    alert('Problem with the server. Try again later.');

                }
            });
        } else {
            alert('Incorrect data');
        }
        

        
    })





















    $file2_campResWrp.delegate(".cmp-client","mouseenter",function(){
        $(this).addClass("clt-clientHgl");
    })
    $file2_campResWrp.delegate(".cmp-client","mouseleave",function(){
        $(this).removeClass("clt-clientHgl");
    })
 
     /* Declare the functions for getting the items and subfolders, etc. 
       These could be simple global functions. 
       (Here we are adding them to the window object, which is equivalent). */

 
    $.extend( window, { 
        returnCtr: function( tmplItem ) {
            var res = 0;
            if(tmplItem.data.Shows > 0){
              res = tmplItem.data.Clicks/tmplItem.data.Shows*100;  
            }
            
            return res.toFixed(2);
        }
    });
    
    
    
    
 
});