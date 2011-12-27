<?php if(!$this->Session->read('Auth.User.id')):?>
    <?php echo $this->element('login_form',array('plugin'=>'users'));?>
<?php else: ?>
    <?php echo $this->Html->link('LogOut',array('plugin'=>'users','controller'=>'users','action'=>'logout'));?>
<?php endif ?>