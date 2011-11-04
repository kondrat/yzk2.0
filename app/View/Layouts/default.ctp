<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $_SERVER['HTTP_HOST']; ?>
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css(
                array(
                    'tipsy/stylesheets/tipsy',
                    'screen',
                    'yzk-lt',
                    'yzk-com',
                    'yzk-clt',
                    'yzk-cmp',
                    'yzk-ur'
                )
        );
        echo '<!--[if IE]>';
        echo $this->Html->css('ie');
        echo '<![endif]-->';



        echo $this->Html->script(array(
            'jq/jquery-1.6.1.min',
            'plug/tipsy/javascripts/jquery.tipsy',
            'jq/jquery.tmpl.min',
            'plug/jquery.universalpaginate',
            'dev/comfunc'
            
        ));


        echo $this->Html->scriptBlock(
                'var path = "' . Configure::read('path') . '";'
        );

        echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <div class="container" style="position:relative;">
            <div class="lt-pageheader span-18">
                <div class="span-18">cakephp2.0</div>
                <div style="position:absolute;top:0;left:-138px;display: none;">
                    <?php
                    //changing of the home link depends on user's status to avoid redirection
                    if($this->Session->read('Auth.User.group_id') == 3){
                        $whereTheHomePage = array('plugin'=>null,'controller'=>'clients','action'=>'index');
                    } elseif ($this->Session->read('Auth.User.group_id') == 4) {
                        $whereTheHomePage = array('plugin'=>null,'controller'=>'campaigns','action'=>'index','client'=>$this->Session->read('Auth.User.ynLogin'));
                    } else {
                        $whereTheHomePage = '/';
                    }
                    echo $this->Html->link($this->Html->image(
                                    'pic/logo_1.png'
                            ), $whereTheHomePage, array('escape' => false));
                    ?>
                </div>
                <?php
                
                if (!isset($menuType) || !in_array($menuType, array('reg','settings', 'login', 'index', 'regged'), true)) {
                    $menuType = null;
                }
                
                echo $this->element('pageHead/topMenu/TopMenu', array('menuType' => $menuType));
                ?>
                
             <div class="" style="position: absolute;top: 4px;left:660px;">
                <?php echo $this->Html->link($this->Html->image(
                                    'pic/logo_1.png'
                            ), '/', array('escape' => false));?>
            </div>               
            </div>
        </div>

        <div class="container">

            <div class="span-18" style="position:relative;">
                <?php echo $this->Session->flash(); ?>
            </div>

            <?php echo $content_for_layout; ?>

        </div>

        <div class="container" style="">
            <div class="lt-pagefooter"> 
                <div class="span-18">
                    <div class="lt-footerNote">
                        <?php echo $this->Html->link($_SERVER['HTTP_HOST'], "/"); ?> &copy;<?php echo date('Y'); ?>
                    </div>
                </div>        
            </div>
        </div>
        <?php //echo $this->element('sql_dump');  ?>
    </body>
</html>