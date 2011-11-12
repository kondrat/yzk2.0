<?php

/**
 * incluging all plugin's js and css files
 */
/**
 * js inclusion
 */
echo $this->Html->script(
        array(
            'dev/func',
            'dev/det'
        ), array('inline' => false)
);


/**
 *  css inclusion
 */
echo $this->Html->css(
        array('yzk-ur'), null, array('inline' => false)
);
?> 