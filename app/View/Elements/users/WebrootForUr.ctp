<?php

/**
 * js inclusion
 */
echo $this->Html->script(
        array(
            'dev/reg',
            'dev/func'
        ), array('inline' => false)
);


/**
 *  css inclusion
 */
echo $this->Html->css(
        array('yzk-ur'), null, array('inline' => false)
);
?> 