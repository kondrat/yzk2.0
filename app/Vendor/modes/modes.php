<?php
/**
 * Set of modes - functions for ajasting the price of compaign
 *
 * @author kondratyev
 */

class modes {
    //put your code here
    
    
    /**
     * Max grantee plus P - precent
     * 
     * @param double $max
     * @param int $percent 
     * 
     * @return double
     * @access public
     */
    public function maxP($max = null,$percent = NULL){
        return $max/100*$percent+$max;
    }
    /**
     * Max grantee plus C - cent
     * 
     * @param double $max
     * @param double $cent
     * 
     * @return double
     * @access public
     */
    public function maxC($max = null,$cent = NULL){
        return $max+$percent;
    }   
    
}

?>
