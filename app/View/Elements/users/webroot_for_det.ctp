<?php

/**
 * incluging all plugin's js and css files
 */
/**
 * js inclusion
 */
echo $this->Html->script(
        array(
            '/users/js/dev/func',
            '/users/js/dev/det'
        ), array('inline' => false)
);


/**
 *  css inclusion
 */
echo $this->Html->css(
        array('/users/css/yzk-ur'), null, array('inline' => false)
);
?> 