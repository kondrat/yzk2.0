<?php

/**
 * js inclusion
 */
echo $this->Html->script(
        array(
            '/users/js/dev/reg',
            '/users/js/dev/func'
        ), array('inline' => false)
);


/**
 *  css inclusion
 */
echo $this->Html->css(
        array('/users/css/yzk-ur'), null, array('inline' => false)
);
?> 