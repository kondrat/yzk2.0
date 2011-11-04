<?php echo $this->element('users/WebrootForUr'); ?>
<div class="ur-formPageLog span-18">
	
    <?php
    echo $this->Form->create('User', array(
        'url' => array(
            'admin' => false,
            'plugin' => null,
            'controller' => 'users',
            'action' => 'login'
        ),
        'id'=> 'ur-loginForm',
        'inputDefaults' => array(
            'label' => false,
            'div' => false
        )
    ));
    ?>

    <div class="ur-inputFormWrap">
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__('Email', true)); ?>
        </div>
        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('email', array('id'=>'ur-userEmailLog')); ?>	
        </div>
    </div>	

    <div class="ur-inputFormWrap">
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__('Password', true)); ?>
        </div>
        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('password', array('id'=>'ur-userPassLog','type' => 'password')); ?>
        </div>
        <div class="ur-formWrapTip">
            <div style="margin-top: 5px;">
                <?php echo $this->Html->link(__('Forgot?', true), array('admin' => false, 'action' => 'reset_password'), array('class' => '')); ?>
            </div>
        </div>
    </div>

    <div class="ur-inputFormWrap">
        <div class="ur-autoLogin" style="float:left;margin:0 0 0 175px;">
            <?php
            echo $this->Form->input('remember_me', array('type' => 'checkbox',
                'label' => __('Remember Me', true),
                'div' => false)
            );
            ?>
        </div>
    </div>


    <div class="ur-formSubmitLog">			
        <span><?php echo $this->Form->button(__('Submit', true), array('type' => 'submit', 'id' => 'ur-userSubmitLog')); ?></span>
    </div>

    <?php echo $this->Form->end(); ?>




    <div class="reg">
        <?php echo $this->Html->link(__('SignUp now', true), array('plugin'=>null,'controller' => 'users', 'action' => 'reg')); ?>
    </div>

</div>