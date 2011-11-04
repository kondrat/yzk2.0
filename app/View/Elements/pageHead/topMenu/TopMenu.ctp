<?php
if (!isset($menuType)) {
    $menuType = null;
}
?>

<?php switch ($menuType):
    case 'regged': ?>
        <div class="lt-topMenu">
            <?php echo $this->Html->link(__('Profile', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'profile'), array('onclick' => 'return false')); ?>
        </div>	 	
        <div class="lt-topMenu">
            <?php echo $this->Html->link(__('Settings', true), array('plugin' => 'users', 'controller' => 'details', 'action' => 'index'), array()); ?>
        </div>	 	
        <div class="lt-topMenu">
            <?php echo $this->Html->link(__('LogOut now', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
        </div>
        <?php break; ?>

    <?php case 'settings'; ?>
        <div class="lt-topMenu">
            <?php echo $this->Html->link(__('Home', true), '/', array()); ?>
        </div>   
        <div class="lt-topMenu">
            <?php echo $this->Html->link(__('LogOut now', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
        </div>   
        <?php break; ?>
    <?php case 'reg': ?>
        <div class="ur-layotHead span-18">

            <div class="span-15">

                
                   
                    <?php
                    echo $this->Form->create(null, array(
                        'url' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'),
                        'inputDefaults' => array('label' => false, 'div' => false,),
                        'id' => 'ur-headLayoutLogin'
                            )
                    );
                    ?>
                    <div class="span-10">
                        <div class="span-10">
                            <div class="ur-headLayoutInputWrp span-5"> 
                                <?php echo $this->Form->input('email', array('label' => array('text' => 'Email'), 'id' => 'ur-topLogEmal', 'autocomplete'=>"off")); ?>
                            </div>
                            <div class="ur-headLayoutInputWrp span-5 last">
                                <?php echo $this->Form->input('password', array('label' => __d('users', 'Password', true), 'id' => 'ur-topLogPass')); ?>
                            </div>
                        </div>
                    
                        <div class="ur-headLayoutRemMe span-5">
                            <?php
                                echo $this->Form->input('remember_me', array('type' => 'checkbox',
                                    'label' => __('Remember Me', true),
                                    'div' => false)
                                );
                                ?>                  
                        </div>
                        <div class="span-5 last">
                            <?php echo $this->Html->link(__('Forgot it?', true), array('admin' => false, 'action' => 'reset_password'), array('class' => '')); ?>
                        </div>
                        
                    </div>
                
                <div class="span-2">
                        <?php
                        $endOptions = array(
                            'label' => 'LogIn',
                            'name' => 'LogIn',
                            'div' => FALSE,
                            'class'=>'ur-headLayoutLoginBtn'
                        );
                        echo $this->Form->end($endOptions);
                        ?>
                </div>
     

      
                
            </div>

        </div>
            <?php break; ?>
        <?php case 'login': ?>
        <div class="lt-topMenu">										
            <?php echo $this->Html->link(__('Home', true), '/', array()); ?>			
        </div>
        <div class="lt-topMenu">										
        <?php echo $this->Html->link(__('SignUp now', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'reg')); ?>
        </div>		
        <?php break; ?>
        <?php case 'index': ?>

        <div id="logInNow" class="lt-topMenu">										
        <?php //echo $this->Html->link('<span>'.__('LogIn now',true).'</span><span class="upDownSmallArrow"></span>', array('controller'=>'users','action'=>'login'),array('escape'=>false) ); ?>
            <?php echo $this->Html->link('<span class="upDownArr">' . __('LogIn now', true) . '</span>', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'), array('escape' => false)); ?>			
        </div>
        <div class="lt-topMenu">										
        <?php echo $this->Html->link(__('SignUp now', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'reg')); ?>
        </div>			
        <?php echo $this->element('pageHead/quickLogin/quick_login', array('cache' => false)); ?>


        <?php break; ?>
    <?php default: ?>

        <div><?php echo $this->Html->link($_SERVER['HTTP_HOST'], "/"); ?></div>

        <?php break; ?>


<?php endswitch ?>	


