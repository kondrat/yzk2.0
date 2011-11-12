<?php echo $this->element('users/WebrootForDet'); ?>
<?php //debug($this->Session->read('Auth.User'));?>
<div class="span-18">
    <div class="span-18">
        User Emai: <?php echo $this->Session->read('Auth.User.email');?>
    </div>
</div>
<div class="span-18">
    <?php echo __('Chandge password');?>
</div>
<div calss="span-18">
    <div class="span-18"><?php echo __('Current sertifitate:');?></div>
    <div class="span-18">
        <?php 
            __d('users','Valid till: ');
            
        ?>
        <?php if(isset($notAfter) && $notAfter != null): ?>
            <?php    echo $notAfter;?>
        <?php else: ?>
            <span style="color:red;"><?php echo __('You haven\'t uploaded certificate yet');?></span>   
        <?php endif ?>
    </div>
</div>
<div class="span-18">
    
    <div class="span-18">
        <div id="ur-uploadBtn" class="ur-uploadBtn">
            <?php echo __('upload sertificate');?>
        </div>
            
    </div>
    <div id="ur-uploadCert" class="span-18 hide">
        <?php echo $this->Form->create(null,array(
            'url'=>array('plugin'=>null,'controller'=>'details','action'=>'certupload'),
            'type'=>'file'
        ));?>
        <?php echo $this->Form->input('cert',array('type' => 'file','label'=>false));?>
        <?php echo $this->Form->end('Submit');?>
    </div>
</div>