<?php echo $this->element('users/WebrootForUr'); ?>
<div class="ur-regInfo span-18">
    <h3>Wellcome to <?php echo $_SERVER['HTTP_HOST'];?></h3>
    <p>Info about this site here</p>
</div>
<div class="ur-formPageReg span-18">

    <?php
    echo $this->Form->create('User', array(
        'action' => 'reg',
        'inputDefaults' => array(
            'label' => false,
            'div' => false
        )
            )
    );

    $errors = array(
        'password1' => array('betweenRus' => __( 'Password must be between 4 and 15 chars', true)),
        'password2' => array('passidentity' => __( 'Please verify your password again', true)),
        'email' => array(
            'notEmpty' => __( 'This field cannot be left blank', true),
            'email' => __( 'Should look like an email address', true),
            'checkUnique' => __( 'This Email has already been taken', true),
        ),
        'captcha' => array(
            'notEmpty' => __( 'This field cannot be left blank', true),
            'alphanumeric' => __( 'Only alphabets and numbers allowed', true),
            'equalCaptcha' => __( 'Please, correct the code.', true),
        ),
        'tos' => array(
            'custom' => __( 'You must verify you have read the Terms of Service', true)
        )
    );


    $errorsObj = $this->Js->object($errors);
    echo $this->Html->scriptBlock('var rErr = ' . $errorsObj . ';', array('inline' => false));
    ?>

    
    
    
    <div class="ur-inputFormWrap">	
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__('Email')); ?>
        </div>
        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('email', array('id' => 'ur-userEmailReg', "class" => "email required", 'error' => false)); ?>	
        </div>
        <div id="rEmail" class="ur-formWrapTip">

            <?php
            $errEmailClass = 'hide';
            $okEmailClass = 'hide';
            if (isset($this->validationErrors['User']['email'])) {
                $errEmailClass = '';
            } else {
                if (isset($this->data['User']['email'])) {
                    $okEmailClass = '';
                }
            }
            ?>

            <div id="rEmailTip" class="rTip hide">																	
                <?php __( 'Enter valid Email'); ?>								  																	
            </div>							

            <div id="rEmailCheck" class="rCheck hide">
                <span class="markCheck"></span>
                <span><?php echo __('Checking Email'); ?></span>
            </div>

            <div id="rEmailError" class="rError <?php echo $errEmailClass; ?>">
                <?php echo $this->Form->error('email', $errors['email'], array('wrap' => null)); ?>
            </div>

            <div id="rEmailOk" class="rOk <?php echo $okEmailClass; ?>">
                <span class="mark"></span>
                <span><?php echo __( 'Ok'); ?></span>
            </div>	

        </div>					
    </div>
 

    <div class="ur-inputFormWrap">

        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label( __( 'Password') ); ?>
        </div>

        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('password1', array('id' => 'ur-userPassReg1', 'type' => 'password', 'error' => false)); ?>
        </div>

        <div id="rPass1" class="ur-formWrapTip">	

            <?php
            $errPass1Class = 'hide';
            if (isset($this->validationErrors['User']['password1'])) {
                $errPass1Class = '';
            }
            ?>

            <div id="rPass1Tip" class="rTip hide">																	
                <?php __( '6 characters or more'); ?>								  																	
            </div>

            <div id="rPass1Check" class="rCheck hide">
                <span class="mark"></span>
                <span><?php __( 'Checking password'); ?></span>
            </div>

            <div id="rPass1Error" class="rError <?php echo $errPass1Class; ?>">
                <?php echo $this->Form->error('password1', $errors['password1'], array('wrap' => null)); ?>
            </div>

        </div>
    </div>	

    <div class="ur-inputFormWrap">	
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__( 'Confirm Password', true)); ?>
        </div>
        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('password2', array('id' => 'ur-userPassReg2', 'type' => 'password', 'error' => false)); ?>
        </div>

        <div id="rPass2" class="ur-formWrapTip">
            <?php
            $errPass2Class = 'hide';
            $okPass2Class = 'hide';
            if (isset($this->validationErrors['User']['password2'])) {
                $errPass2Class = '';
            } else {
                if (isset($this->data['User']['password2']) && $this->data['User']['password2'] !== '') {
                    $okPass2Class = '';
                }
            }
            ?>

            <div id="rPass2Tip" class="rTip hide">																	
                <?php __( 'Passwords must be equal'); ?>								  																	
            </div>							

            <div id="rPass2Check" class="rCheck hide">

                <?php __( 'Checking password'); ?>
            </div>

            <div id="rPass2Error" class="rError <?php echo $errPass2Class; ?>">
                <?php echo $errors['password2']['passidentity']; ?>
            </div>

            <div id="rPass2Ok" class="rOk <?php echo $okPass2Class; ?>">
                <span class="mark"></span>
                <span><?php __( 'Ok'); ?></span>
            </div>								

        </div>
    </div>
    
    
    
    
    <div class="ur-inputFormWrap">

        <?php
        $errCapClass = 'hide';

        if (isset($this->validationErrors['User']['captcha'])) {
            $errCapClass = '';
        }
        ?>

        <div class="span-4" style="padding-left: 175px;">	
            <div class="capPlace"><?php echo $this->Html->image(array('plugin' => null, 'controller' => 'users', 'action' => 'kcaptcha', time()), array('id' => 'capImg')); ?></div>				
            <div class="span-4 capReset">
                <?php echo $this->Html->image("icon/ajax-loader1-stat.png"); ?>
                <span><?php echo __( 'Couldn\'t see'); ?></span>
            </div>								
        </div>					
        <div class="" style="float:left;margin:0 5px 0 0;">	
            <div><?php __( 'Please type in the code'); ?></div>				
            <?php echo $this->Form->input('captcha', array('id' => 'ur-userCapchaReg', 'error' => false)); ?>								
        </div>
        <div id="rCap" class="ur-formWrapTip" style="width:185px;margin-top:17px;">	
            <div id="rCapTip" class="rTip hide">																	
                <?php __( 'Type the letters from picture'); ?>								  																	
            </div>
            <div id="rCapError" class="rError <?php echo $errCapClass; ?>">
                <?php __( 'Please, correct the code.') ?>
            </div>
        </div>


    </div>

    <div class="ur-inputFormWrap">

        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('tos', array('id' => 'ur-userTosReg', 'type' => 'checkbox', 'label' => false)); ?>
        </div>
        <div class="">
            <?php echo $this->Form->label('User', __( 'I have read and agreed to ', true) . $this->Html->link(__( 'Terms of Service', true), array('plugin'=>false,'controller' => 'pages', 'action' => 'tos'))); ?>
        </div>							
        <div id="reg_tosError" class="rError <?php echo $errEmailClass; ?>">
            <?php echo $this->Form->error('tos', $errors['tos'], array('wrap' => null)); ?>
        </div>							

    </div>
    <div class="ur-formSubmitReg">			
        <span><?php echo $this->Form->button(__( 'Submit', true), array('type' => 'submit', 'id' => 'ur-userSubmitReg')); ?></span>
    </div>

    <?php echo $this->Form->end(); ?>

</div>

