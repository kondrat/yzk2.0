    <?php echo $this->Html->script(array('dev/file2')); ?>





    <script id="cmp-clientCompListHdTmpl" type="text/x-jquery-tmpl">
         

         <div  class="clt-clientHd  span-18">
             
            <div class="cmp-nameHd span-4"><?php echo __('Campaing'); ?></div>
            
            <div class="cmp-showsHd span-2"><?php echo __('Shows'); ?></div>
            
            <div class="cmp-sumHd span-2"><?php echo __('Sum'); ?></div>
            <div class="cmp-restHd span-2"><?php echo __('Rest'); ?></div>
            <div class="cmp-dayBudHd span-2"><?php echo __('Day Budget');?></div>
            <div class="cmp-daySpendHd span-2"><?php echo __('Day spend');?></div>
            <div class="cmp-stopStartHd spna-2"><?php echo __('Stop/Resume');?></div>

        </div> 
            {{tmpl(data) "#cmp-clientCompListTmpl"}}
    </script>
    <script id="cmp-clientCompListTmpl" type="text/x-jquery-tmpl">
       
         
         {{if StatusShow == "Yes"}}
            <div  class="cmp-client  span-18">
         {{else StatusShow == "No" && stoped == 0 }}
            <div  class="cmp-client cmp-clientStop  span-18">
         {{else stoped == 1 }}
            <div  class="cmp-client cmp-clientDayLim  span-18">
         {{/if}}
            <div class="cmp-name span-4">
            <?php echo $this->Html->link('${Name}', array(
                'plugin' => false,
                'controller' => 'campaigns','action' => 'campaign','client'=>$clientName,'campid' => '${CampaignID}','campname' => '${Name}'),
                    array('class'=>'cmp-campLnk')
            );
            ?>
            </div>
            
            <div class="cmp-shows span-2">${Shows}</div>
            
            <div class="cmp-sum span-2">${Sum}</div>
            <div class="cmp-rest span-2">${Rest}</div>
            
            {{if dayLim == 0}}
                <div class="cmp-dayBud span-2"><?php echo __('Unlimit');?></div>
            {{else}}
                 <div class="cmp-dayBud span-2">${dayLim}</div>
            {{/if}}

            <div class="cmp-daySpend span-2">${daySpend}</div>
           
            
            {{if StatusShow == "Yes"}}
                <div class="cmp-startStop span-2"><?php echo __('Stop');?></div>
            {{else StatusShow == "No" && stoped == 0 }}
                <div class="cmp-startStop span-2"><?php echo __('Resume');?></div>
            {{else stoped == 1 }}
                <div class="cmp-dayLimit span-2"><?php echo __('Day Limit!');?></div>
            {{/if}}
        </div>       
       
    </script>

    <script id="cmp-clientCompBugTmpl" type="text/x-jquery-tmpl">
       
        
         <div  class="cmp-client cmp-clientBud  span-18" style="background-color:gainsboro;">

            <div class="cmp-name span-3">${Name}</div>

            
            <div class="cmp-dayBud span-2"><input class="cmp-budInput" name="cmp-dayBud" /></div>
            <div class="span-14 prepend-3">
                <div class="cmp-budOk  span-1">OK</div>
                <div class="cmp-budClose span-2"><?php echo __('Close');?></div>
            </div>
        </div>       
       
    </script>





        

 <script id="cmp-clientCompInfoHdTmpl" type="text/x-jquery-tmpl"> 
 
    <div class="clt-clientHd span-18">
        
        <div class="span-1"><?php echo $this->Form->checkbox('toMode', array('disabled' => 'disabled')); ?></div>
        
        <div class="cmp-titleHd span-12"><?php echo __('Title'); ?></div>

        <div class="cmp-phraseHd span-4"><?php echo __('Phrase'); ?></div>

    </div>
    {{tmpl(data) "#cmp-clientCompInfoTmpl"}}
</script>      
<script id="cmp-clientCompInfoTmpl" type="text/x-jquery-tmpl">  
    <div  class="cmp-client  span-18">
        <div class="span-1"><?php echo $this->Form->checkbox('toMode', array('id'=>'ch-${BannerID}' ,'disabled'=>'disabled'));?></div>
        <div class="cmp-title span-12">${Title}</div>
        <div class="cmp-text span-12">${Text}</div>

        <div class="cmp-phrase span-4 last">
            <?php
            echo $this->Html->link(__('Phrases'), array(
                'plugin' => false,
                'controller' => 'campaigns', 'action' => 'banner', 'campid' => '${CampaignID}', 'bannid' => '${BannerID}'
                    ), array(
                        'class'=>'cmp-bannLnk'
                    )
            );
            ?>
        </div>
    </div>  
</script>



<script id="cmp-clientBannerHdTmpl" type="text/x-jquery-tmpl"> 

    <div class="span-18">
        <div id="cmp-setModeBtn" class="cmp-setModeBtn"><?php echo __('Set mode for all phrases'); ?></div>
        <div id="cmp-unSetModeBtn" class="cmp-unSetModeBtn"><?php echo __('UnSet mode for all phrases'); ?></div>
    </div>
    <div id="cmp-setModeWrp" class="cmp-setModeWrp span-18 hide"></div>

    <div  class="clt-clientHd  span-18">
        <div class="span-1"><?php echo $this->Form->checkbox('toMode', array('disabled' => 'disabled')); ?></div>
        <div class="cmp-phraseHd span-5"><?php echo __('Phrase'); ?></div>
        <div class="cmp-strHd span-2"><?php echo __('CTR'); ?></div>
        <div class="cmp-priceHd span-2"><?php echo __('Price'); ?></div>
        <div class="cmp-minMaxPricesHd span-3"><?php echo __('Position price'); ?></div>
        <div class="cmp-modeHd span-3"><?php echo __('Mode'); ?></div>
        <div class="cmp-actionHd span-2 last"><?php echo __('Action'); ?></div>        
    </div>
    {{tmpl(data) "#cmp-clientBannerTmpl"}}
</script>
<script id="cmp-clientBannerTmpl" type="text/x-jquery-tmpl">
    
    {{if Min }}
    
    <div  class="cmp-client  span-18">
        <div class="span-1"><?php echo $this->Form->checkbox('toMode', array('id'=>'ch-${PhraseID}' ,'disabled'=>'disabled'));?></div>
        <div class="cmp-phrase span-5">${Phrase}</div>       
        <div class="cmp-ctr span-2">
            <div class="cmp-clicksCtr span-2 last"><?php echo __('Clicks:');?>&nbsp;${Clicks}</div>
            <div class="cmp-showsCtr span-2 last"><?php echo __('Shows:');?>&nbsp;${Shows}</div>           
            <div class="cmp-ctrCtr span-2 last"><?php echo __('CTR:');?>&nbsp;${returnCtr($item)}%</div>
        </div>
        <div class="cmp-price span-2 last">${Price}</div>
        <div class="cmp-minMaxPrices span-3">
            
            <div class="cmp-maxPremPr"><?php echo __('Premium Max:');?>&nbsp;${PremiumMax}</div> 
            <div class="cmp-minPremPr"><?php echo __('Premium Min:');?>&nbsp;${PremiumMin}</div>
            <div class="cmp-maxPr"><?php echo __('Guaranty Max:');?>&nbsp;${Max}</div>
            <div class="cmp-minPr"><?php echo __('Guaranty Min:');?>&nbsp;${Min}</div>
                                    
        </div>
        <div class="span-3 cmp-string">
            {{if mode}}
                <div class="span-3 cmp-modeStr">${mode}</div>
            {{else}}
                <?php echo __("no mode set yet");?><br />
            {{/if}}
            {{if maxPrice}}
            <div class="span-3 cmp-maxPriceStr"><?php echo __('Max price:');?>&nbsp;${maxPrice}&#36;</div>
            {{else}}
                <?php echo __("no maxPrice set yet");?>
            {{/if}}
        </div>
        {{if mode}}
            <div class="span-2 last">
                <div class="span-2 last cmp-edit"><?php echo __('eidt');?></div>       
                <div class="span-2 last cmp-delete"><?php echo __('del');?></div>
            </div>
        {{else}}
            <div class="span-2 last cmp-edit"><?php echo __('create');?></div>
        {{/if}}
        <div class="cmp-modesEditWrp span-18"></div>
    </div> 
    
    {{else}}
        
    <div  class="cmp-clientLowCtr  span-18">
        <div class="cmp-lowCtrFrst span-1">#</div>
        <div class="cmp-phrase span-5">${Phrase}</div>       
        <div class="cmp-ctr span-2 last">
            <div class="cmp-clicksCtr span-2 last"><?php echo __('Clicks:');?>&nbsp;${Clicks}</div>
            <div class="cmp-showsCtr span-2 last"><?php echo __('Shows:');?>&nbsp;${Shows}</div>           
            <div class="cmp-ctrCtr span-2 last"><?php echo __('CTR:');?>&nbsp;${returnCtr($item)}%</div>
        </div>
        <div class="cmp-price span-2 last">${Price}</div>                     
        <div class="cmp-lowCtr span-3"><?php echo __('Low CTR!');?></div>
                 
        <div class="span-3 cmp-string">
            {{if mode}}
                <span class="cmp-modeStr">${mode}</span>
            {{else}}
                <?php echo __("no mode set yet");?><br />
            {{/if}}
            {{if maxPrice}}
            <div class="span-3 cmp-maxPriceStr"><?php echo __('Max price:');?>&nbsp;${maxPrice}&#36;</div>
            {{else}}
                <?php echo __("no maxPrice set yet");?>
            {{/if}}
        </div>
        <div class="span-2 cmp-noedit">----</div>       
                         
    </div>
    {{/if}}
    
</script>


<script id="cmp-modesTmpl" type="text/x-jquery-tmpl">
        <div class="cmp-modes">
            <?php
            if (isset($modes) && $modes != array()) {
                $modesOptions = array();
                foreach ($modes as $k1 => $v1) {
                    foreach ($v1 as $k2=>$v2){
                      $modesOptions[$k1][$v2['name']] = sprintf($v2['desc'], 'X');  
                    }
                    
                }
                echo $this->Form->input('mode', array(
                    'id'=>FALSE,
                    'default' => 'two',
                    'empty' => '(choose one)',
                    'options' => $modesOptions,
                    'label' => FALSE,
                    'div'=>FALSE
                        )
                );
            }
            ?>
            <span class="cmp-xspan">&nbsp;X&nbsp;=&nbsp;</span><input class="cmp-xinput" name="cmp-x" />&nbsp;
            <span class="cmp-maxPrice">&nbsp;<?php echo __('Max price');?>&nbsp;=&nbsp;</span><input class="cmp-maxPrInput" name="cmp-maxpr" />&nbsp;
            <span class="cmp-save"><?php echo __('Save');?></span>
            <span class="cmp-close"><?php echo __('Close');?></span>

        </div>
</script>




        
<div id="cmp-navBar"class="span-18 cmp-navBar">
    
    <div class="span-18">
        <div id="cmp-navBarCtrl" class="cmp-navBarCtrl">

            <?php if($this->Session->read('Auth.User.group_id') == 3):?> 
                <?php echo $this->Html->link(__("Clients"), array('plugin' => null, 'controller' => 'clients', 'action' => 'index')); ?>&nbsp;/&nbsp;
            <?php endif ?>
        
            <span id="cmp-navBarCam" class="cmp-navBarCam" data-cmpId="" data-cmpName=""><?php echo __('Campagins'); ?></span>&nbsp;/&nbsp;
            <span id="cmp-navBarBan" class="cmp-navBarBan" data-bnId="" data-bnTtl="" data-banTxt=""><?php echo __('Banners'); ?></span>&nbsp;/&nbsp;
            <span id="cmp-navBarPhr" class="cmp-navBarPhr"><?php echo __('phrases'); ?></span>
        </div>
        <div id="cmp-loaderWrp" class="cmp-loaderWrp span-5">
            <div class="clt-loader"><?php echo $this->Html->image('pic/clt-loader.gif'); ?></div> 
        </div>
    </div>
    
    <div class="span-18"><?php echo __('Client'); ?>:&nbsp;<span class="cmp-navBarData"><?php echo $clientName; ?></span></div>
    <div id="cmp-navBarCamName" class="span-18 hide"><?php echo __('Campaign'); ?>:&nbsp;<span class="cmp-navBarData"></span></div>
    <div id="cmp-navBarBnTlt" class="span-18 hide"><?php echo __('Title'); ?>:&nbsp;<span class="cmp-navBarData"></span></div>
    <div id="cmp-navBarBnTxt" class="span-18 hide"><?php echo __('Text'); ?>:&nbsp;<span class="cmp-navBarData"></span></div>
    
</div>


<?php 
//    echo (double)"drrf";
//    echo '<br />';
//    echo $this->Number->precision("18!@#$~#$#%%27776.9sdf1873645", 2 );
//    echo '<br />';
//    echo round(99.111, 2)

?>


<div id="cmp-campResWrp" class="cmp-campResWrp span-19" data-clname="<?php echo $clientName; ?>"></div>