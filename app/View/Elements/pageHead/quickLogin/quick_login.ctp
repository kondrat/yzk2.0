<div id="quickLogin" class="hide">
    <div class="quickLoginInner">	
        <?php
        echo $form->create('User', array(
            'url' => array('plugin' => 'users','controller' => 'users', 'action' => 'login'),
            'inputDefaults' => array(
                'label' => true,
                'div' => false
            )
        ));
        ?>

        <div class="qLogInputWrap">

            <div class="formWrapIn">
<?php echo $form->input('username', array('label' => __('Username or email', true))); ?>	
            </div>
        </div>	

        <div class="qLogInputWrap">

            <div class="formWrapIn">
<?php echo $form->input('password', array('type' => 'password', 'label' => __('Password', true))); ?>
            </div>

        </div>

        <div class="" style="float:left;margin:0;">			
            <span><?php echo $form->button(__('Submit', true), array('type' => 'submit', 'id' => 'qLogSubmit')); ?></span>
        </div>




        <div class="">
            <div class="autoLogin" style="float:left;margin:0 0 0 5px;">
                <?php
                echo $form->input('auto_login', array('type' => 'checkbox',
                    'label' => __('Remember Me', true),
                    'div' => false)
                );
                ?>
            </div>
        </div>

        <div class="formWrapTip">
            <div style="margin-top: 5px;">
<?php echo $html->link(__('Forgot password?', true), array('admin' => false, 'plugin' => 'users', 'controller' => 'users', 'action' => 'reset'), array('class' => '')); ?>
            </div>
        </div>	


<?php echo $form->end(); ?>
    </div>
</div>