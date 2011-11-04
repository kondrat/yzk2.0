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
        'password1' => array('betweenRus' => __d('users', 'Password must be between 4 and 15 chars', true)),
        'password2' => array('passidentity' => __d('users', 'Please verify your password again', true)),
        'email' => array(
            'notEmpty' => __d('users', 'This field cannot be left blank', true),
            'email' => __d('users', 'Should look like an email address', true),
            'checkUnique' => __d('users', 'This Email has already been taken', true),
        ),
        'captcha' => array(
            'notEmpty' => __d('users', 'This field cannot be left blank', true),
            'alphanumeric' => __d('users', 'Only alphabets and numbers allowed', true),
            'equalCaptcha' => __d('users', 'Please, correct the code.', true),
        ),
        'tos' => array(
            'custom' => __d('users', 'You must verify you have read the Terms of Service', true)
        )
    );


    $errorsObj = $this->Js->object($errors);
    echo $this->Html->scriptBlock('var rErr = ' . $errorsObj . ';', array('inline' => false));
    ?>

    
    
    
    <div class="ur-inputFormWrap">	
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__d('users', 'Email', true)); ?>
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
                <?php __d('users', 'Enter valid Email'); ?>								  																	
            </div>							

            <div id="rEmailCheck" class="rCheck hide">
                <span class="markCheck"></span>
                <span><?php __d('users', 'Checking Email'); ?></span>
            </div>

            <div id="rEmailError" class="rError <?php echo $errEmailClass; ?>">
                <?php echo $this->Form->error('email', $errors['email'], array('wrap' => null)); ?>
            </div>

            <div id="rEmailOk" class="rOk <?php echo $okEmailClass; ?>">
                <span class="mark"></span>
                <span><?php __d('users', 'Ok'); ?></span>
            </div>	

        </div>					
    </div>
 

    <div class="ur-inputFormWrap">

        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__d('users', 'Password', true)); ?>
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
                <?php __d('users', '6 characters or more'); ?>								  																	
            </div>

            <div id="rPass1Check" class="rCheck hide">
                <span class="mark"></span>
                <span><?php __d('users', 'Checking password'); ?></span>
            </div>

            <div id="rPass1Error" class="rError <?php echo $errPass1Class; ?>">
                <?php echo $this->Form->error('password1', $errors['password1'], array('wrap' => null)); ?>
            </div>

        </div>
    </div>	

    <div class="ur-inputFormWrap">	
        <div class="ur-formWrapLabel">
            <?php echo $this->Form->label(__d('users', 'Confirm Password', true)); ?>
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
                <?php __d('users', 'Passwords must be equal'); ?>								  																	
            </div>							

            <div id="rPass2Check" class="rCheck hide">

                <?php __d('users', 'Checking password'); ?>
            </div>

            <div id="rPass2Error" class="rError <?php echo $errPass2Class; ?>">
                <?php echo $errors['password2']['passidentity']; ?>
            </div>

            <div id="rPass2Ok" class="rOk <?php echo $okPass2Class; ?>">
                <span class="mark"></span>
                <span><?php __d('users', 'Ok'); ?></span>
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
            <div class="capPlace"><?php echo $this->Html->image(array('plugin' => 'users', 'controller' => 'users', 'action' => 'kcaptcha', time()), array('id' => 'capImg')); ?></div>				
            <div class="span-4 capReset">
                <?php echo $this->Html->image("icons/ajax-loader1-stat.png"); ?>
                <span><?php __d('users', 'Couldn\'t see'); ?></span>
            </div>								
        </div>					
        <div class="" style="float:left;margin:0 5px 0 0;">	
            <div><?php __d('users', 'Please type in the code'); ?></div>				
            <?php echo $this->Form->input('captcha', array('id' => 'ur-userCapchaReg', 'error' => false)); ?>								
        </div>
        <div id="rCap" class="ur-formWrapTip" style="width:185px;margin-top:17px;">	
            <div id="rCapTip" class="rTip hide">																	
                <?php __d('users', 'Type the letters from picture'); ?>								  																	
            </div>
            <div id="rCapError" class="rError <?php echo $errCapClass; ?>">
                <?php __d('users', 'Please, correct the code.') ?>
            </div>
        </div>


    </div>

    <div class="ur-inputFormWrap">

        <div class="ur-formWrapIn">
            <?php echo $this->Form->input('tos', array('id' => 'ur-userTosReg', 'type' => 'checkbox', 'label' => false)); ?>
        </div>
        <div class="">
            <?php echo $this->Form->label('User', __d('users', 'I have read and agreed to ', true) . $this->Html->link(__d('users', 'Terms of Service', true), array('plugin'=>false,'controller' => 'pages', 'action' => 'tos'))); ?>
        </div>							
        <div id="reg_tosError" class="rError <?php echo $errEmailClass; ?>">
            <?php echo $this->Form->error('tos', $errors['tos'], array('wrap' => null)); ?>
        </div>							

    </div>
    <div class="ur-formSubmitReg">			
        <span><?php echo $this->Form->button(__d('users', 'Submit', true), array('type' => 'submit', 'id' => 'ur-userSubmitReg')); ?></span>
    </div>

    <?php echo $this->Form->end(); ?>

</div>

