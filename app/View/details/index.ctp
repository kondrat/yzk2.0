<?php echo $this->element('webroot_for_det'); ?>
<?php //debug($this->Session->read('Auth.User'));?>
<div class="span-18">
    <div class="span-18">
        User Emai: <?php echo $this->Session->read('Auth.User.email');?>
    </div>
</div>
<div class="span-18">
    <?php __d('users','Chandge password');?>
</div>
<div calss="span-18">
    <div class="span-18"><?php __d('users','Current sertifitate:');?></div>
    <div class="span-18">
        <?php 
            __d('users','Valid till: ');
            
        ?>
        <?php if($notAfter != null): ?>
            <?php    echo $notAfter;?>
        <?php else: ?>
            <span style="color:red;"><?php  __d('users','You haven\'t uploaded certificate yet');?></span>   
        <?php endif ?>
    </div>
</div>
<div class="span-18">
    
    <div class="span-18">
        <div id="ur-uploadBtn" class="ur-uploadBtn">
            <?php __d('users','upload sertificate');?>
        </div>
            
    </div>
    <div id="ur-uploadCert" class="span-18 hide">
        <?php echo $this->Form->create(null,array(
            'url'=>array('plugin'=>'users','controller'=>'details','action'=>'certupload'),
            'type'=>'file'
        ));?>
        <?php echo $this->Form->input('cert',array('type' => 'file','label'=>false));?>
        <?php echo $this->Form->end('Submit');?>
    </div>
</div>