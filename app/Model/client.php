<?php

/*
 * 
 */
App::import('Model', 'Users.User');

class Client extends AppModel {

    public $name = 'Client';
    //@todo to create proper datasource
    // public $useDbConfig = 'yandex';
    
    


    public  $belongsTo = array(
        
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id'
            )
    );
    
    /**
     *
     * @param type $ynLog
     * @param type $email
     * @return type 
     */
    public function regclient($email = null) {
        
        $password = NULL;
        
        $this->data['User']['group_id'] = '4';
        
        $this->data['User']['email'] = $email;
        //@todo password hash
        $password = $this->User->generatePassword();
        if($password){
            $this->data['User']['password'] = sha1(Configure::read('Security.salt') . $password);
        }

        $this->User->set($this->data);
        if($this->User->validates(array( 'fieldList'=>array('email') ) ) ) {
            
            $this->User->create();            
            if($this->User->save($this->data,false)){              
                return array('savedUserId'=>$this->User->id,'savedUserPass'=>$password,'savedUserEmail'=>$email);
            }           
            return FALSE;

        } else {
            $errors = $this->User->invalidFields();
            return $errors;
        }
        
        return FALSE;
    }
}

?>
