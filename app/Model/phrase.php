<?php

/*
 * 
 */


class Phrase extends AppModel {

    public $name = 'Phrase';
    //@todo to create proper datasource
    // public $useDbConfig = 'yandex';
    
    


    public  $belongsTo = array(
        
//            'User' => array(
//                'className' => 'User',
//                'foreignKey' => 'user_id'
//            )
    );
    
 
    

}

?>
