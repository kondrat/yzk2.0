<?php

/**
 * Users Plugin User Model
 *
 * @package users
 * @subpackage users.models
 */
class User extends AppModel {

    /**
     * Name
     *
     * @var string
     */
    public $name = 'User';
    /**
     * Behaviors
     *
     * @var array
     */
    public $actsAs = array('Containable',
//        'SuperAuth.Acl' => array(
//            'type' => 'both',
//            'parentClass' => 'Group',
//            'foreignKey' => 'group_id'
//        ),
//        'Utils.Sluggable' => array(
//            'label' => 'username',
//            'method' => 'multibyteSlug'
//        )
    );
    function parentNode() {
        return 'Users';
    }

    /**
     * Displayfield
     *
     * @var string $displayField
     */
    public $displayField = 'username';
    /**
     * Validation parameters
     *
     * @var array
     */
    public $validate = array(

        'password1' => array(
            'between'=>array(
                'rule' => array('between', 4, 15),
            ),

        ),
        'password2' => array(
            'confirmPassword' => array(
                'rule' => array('confirmPassword', '$this->data'),
            //'message' => 'Please verify your password again'
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                //'message' => 'This field cannot be left blank!!!tt',
                'required' => true,
                'last' => true
            ),
            'email' => array(
                'rule' => array('email', false), //check the validity of the host. to set true.
                'message' => 'Your email address does not appear to be valid!!!',
            ),
            'checkUnique' => array(
                'rule' => array('checkUnique', 'email'),
                'required' => true,
                //'message' => 'This Email has already been taken!!!',
            ),
        ),
        'captcha' => array('notEmpty' => array(
                'rule' => 'notEmpty',
                //'message' => 'This field cannot be left blank',
                'last' => true,
            ),
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
            //'message' => 'Only contain letters and numbers'
            ),
            'equalCaptcha' => array(
                'rule' => array('equalCaptcha', '$this->data'),
            //'message' => 'Please, correct the code'
            ),
        ),
        'tos' => array('rule' => array('custom', '[1]')),
        'md5cert' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                //'message' => 'This field cannot be left blank!!!',
                'required' => true,
                'last' => true
            ),
            'checkUnique' => array(
                'rule' => array('checkUnique', 'md5cert'),
                'required' => true,
                'message' => 'This certificate has already been used',
            ),
        ),        
        
        
    );

//--------------------------------------------------------------------


    function checkUnique($data, $fieldName) {
        $valid = false;
        if (isset($fieldName) && $this->hasField($fieldName)) {
            $valid = $this->isUnique(array($fieldName => $data));
        }
        return $valid;
    }



    /**
     * Custom validation method to ensure that the two entered passwords match
     *
     * @param string $password Password
     * @return boolean Success
     */
    public function confirmPassword($password = null) {
        if ((isset($this->data[$this->alias]['password1']) && isset($password['password2'])) && !empty($password['password2']) && ($this->data[$this->alias]['password1'] === $password['password2'])) {
            return true;
        }
        return false;
    }

//--------------------------------------------------------------------														
    function equalCaptcha($data) {
        //return true;
        if ($this->data['User']['captcha'] != $this->data['User']['captcha2']) {
            return false;
        }
        return true;
    }

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id'
        )
    );
    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Detail' => array(
            'className' => 'Users.Detail',
            'foreign_key' => 'user_id'
        ),
        'Client' => array(
            'ClassName' => 'Client',
            'foreign_key' => 'user_id'
        )
    );

    /*
      var $hasAndBelongsToMany = array(
      'Project' => array(
      'className' => 'Project',
      'joinTable' => 'projects_users',
      'foreignKey' => 'user_id',
      'associationForeignKey' => 'project_id',
      'unique' => true,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'finderQuery' => '',
      'deleteQuery' => '',
      'insertQuery' => ''
      )
      );
     */



    function beforeSave() {
        if (!empty($this->data['User']['password1'])) {
            $this->data['User']['password'] = sha1(Configure::read('Security.salt') . $this->data['User']['password1']);
        }
        return true;
    }

//--------------------------------------------------------------------	
    function getUserData($userName=null) {
        $userDataOutput = false;
        $this->recursive = 0;
        $userData = $this->findByUsername($userName, array('fildes' => 'User.username'));
        if ($userData) {
            $userDataOutput['ID'] = $userData['User']['id'];
        } else {
            $userDataOutput['ID'] = null;
        }
        return $userDataOutput;
    }

//--------------------------------------------------------------------

    /**
     * Registers a new user
     *
     * @param array $postData Post data from controller
     * @param boolean $useEmailVerification If set to true a token will be generated
     * @param boolean $regClient. If true we need to generate password as a random str and send the details to user.
     * 
     * @return mixed
     */
    public function register($postData = array(), $useEmailVerification = true, $regClient = false) {
        if ($useEmailVerification == true) {
            $postData[$this->alias]['email_token'] = $this->generateToken();
            $postData[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', time() + 86400);
        } else {
            $postData[$this->alias]['email_authenticated'] = 1;
        }
        $postData[$this->alias]['active'] = 1;

        //@todo must be in admin panel. We need new logic here;
        //$this->_removeExpiredRegistrations();

        $this->set($postData);
        if ($this->validates()) {
            App::import('Core', 'Security');
            $postData[$this->alias]['password'] = Security::hash($postData[$this->alias]['password1'], null, true);
            $this->create();
            return $this->save($postData, false);
        }

        return false;
    }

    
 
    
    
    /**
     * After save callback
     *
     * @param boolean $created
     * @return void
     */
    public function afterSave($created) {
        if ($created) {
            if (!empty($this->data[$this->alias]['slug'])) {
                if ($this->hasField('url')) {
                    $this->saveField('url', '/user/' . $this->data[$this->alias]['slug'], false);
                }
            }
        }
    }

    /**
     * Checks if an email is in the system, validated and if the user is active so that the user is allowed to reste his password
     *
     * @param array $postData post data from controller
     * @return mixed False or user data as array on success
     */
    public function passwordReset($postData = array()) {

        $user = $this->find('first', array(
                    'conditions' => array(
                        $this->alias . '.active' => 1,
                        $this->alias . '.email' => $postData[$this->alias]['email'])));

        if (!empty($user) && $user[$this->alias]['email_authenticated'] == 1) {
            $sixtyMins = time() + 43000;
            $token = $this->generateToken();
            $user[$this->alias]['password_token'] = $token;
            $user[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', $sixtyMins);
            $user = $this->save($user, false);
            return $user;
        } elseif (!empty($user) && $user[$this->alias]['email_authenticated'] == 0) {
            $this->invalidate('email', __d('users', 'This Email Address exists but was never validated.', true));
        } else {
            $this->invalidate('email', __d('users', 'This Email Address does not exist in the system.', true));
        }
        return $user[$this->alias]['email_authenticated'];//false;
    }

    /**
     * Checks the token for a password change
     * 
     * @param string $token Token
     * @return mixed False or user data as array
     */
    public function checkPasswordToken($token = null) {
        $user = $this->find('first', array(
                    'contain' => array(),
                    'conditions' => array(
                        $this->alias . '.active' => 1,
                        $this->alias . '.password_token' => $token,
                        $this->alias . '.email_token_expires >=' => date('Y-m-d H:i:s'))));
        if (empty($user)) {
            return false;
        }
        return $user;
    }

    /**
     * Generate token used by the user registration system
     *
     * @param int $length Token Length
     * @return string
     */
    public function generateToken($length = 10) {
        $possible = '0123456789abcdefghijklmnopqrstuvwxyz';
        $token = "";
        $i = 0;

        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if (!stristr($token, $char)) {
                $token .= $char;
                $i++;
            }
        }
        return $token;
    }

    /**
     * Generates a password
     *
     * @param int $length Password length
     * @return string
     */
    public function generatePassword($length = 10) {
        srand((double) microtime() * 1000000);
        $password = '';
        $vowels = array("a", "e", "i", "o", "u");
        $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
            "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");
        for ($i = 0; $i < $length; $i++) {
            $password .= $cons[mt_rand(0, 31)] . $vowels[mt_rand(0, 4)];
        }
        return substr($password, 0, $length);
    }

    /**
     * Updates the last activity field of a user
     *
     * @param string $user User ID
     * @return boolean True on success
     */
    public function updateLastActivity($userId = null) {
        if (!empty($userId)) {
            $this->id = $userId;
        }
        if ($this->exists()) {
            return $this->saveField('last_activity', date('Y-m-d H:i:s', time()));
        }
        return false;
    }

    /**
     * Resets the password
     * 
     * @param array $postData Post data from controller
     * @return boolean True on success
     */
    public function resetPassword($postData = array()) {
        $result = false;
        $tmp = $this->validate;
        //validating only pass fields 
        $this->validate = array(
            'password1' => $this->validate['password1'],
            'password2' => $this->validate['password2']
        );

        $this->set($postData);

        if ($this->validates()) {
            App::import('Core', 'Security');
            $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password1'], null, true);
            $this->data[$this->alias]['password_token'] = null;
            $result = $this->save($this->data, false);
        }
        $this->validate = $tmp;
        return $result;
    }

    /**
     * Validates the user token
     *
     * @param string $token Token
     * @param boolean $reset Reset boolean
     * @param boolean $now time() value
     * @return mixed false or user data
     */
    public function validateToken($token = null, $now = null) {
        if (!$now) {
            $now = time();
        }

        $this->recursive = -1;
        $data = false;
        $match = $this->find(array(
                    $this->alias . '.email_token' => $token), 'id, email, email_token_expires');

        if (!empty($match)) {
            $expires = strtotime($match[$this->alias]['email_token_expires']);
            if ($expires > $now) {
                $data[$this->alias]['id'] = $match[$this->alias]['id'];
                $data[$this->alias]['email'] = $match[$this->alias]['email'];
                $data[$this->alias]['email_authenticated'] = '1';


                $data[$this->alias]['email_token'] = null;
                $data[$this->alias]['email_token_expires'] = null;
            }
        }
        return $data;
    }

    /**
     * Changes the password for a user
     *
     * @param array $postData Post data from controller
     * @return boolean True on success
     */
    public function changePassword($postData = array()) {
        $this->set($postData);
        //$tmp = $this->validate;
        //$this->validate = $this->validatePasswordChange;
        //validating only pass fields 
        $this->validate = array(
            'password1' => $this->validate['password1'],
            'password2' => $this->validate['password2'],
            'old_password' => array(
                'wrong' => array('rule' => 'validateOldPassword', 'required' => true, 'message' => __d('users', 'Invalid password.', true))
            )
        );

        if ($this->validates()) {
            App::import('Core', 'Security');
            $this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password1'], null, true);
            $this->save($postData, array(
                'validate' => false,
                'callbacks' => false));
            //$this->validate = $tmp;
            return true;
        }

        //$this->validate = $tmp;
        return false;
    }

    /**
     * Validation method to check the old password
     *
     * @param array $password 
     * @return boolean True on success
     */
    public function validateOldPassword($password) {
        if (!isset($this->data[$this->alias]['id']) || empty($this->data[$this->alias]['id'])) {
            if (Configure::read('debug') > 0) {
                throw new OutOfBoundsException(__d('users', '$this->data[\'' . $this->alias . '\'][\'id\'] has to be set and not empty', true));
            }
        }
        $passwd = $this->field('password', array($this->alias . '.id' => $this->data[$this->alias]['id']));
        App::import('Core', 'Security');
        if ($passwd === Security::hash($password['old_password'], null, true)) {
            return true;
        }
        return false;
    }

    /**
     * Removes all users from the user table that are outdated
     *
     * Override it as needed for your specific project
     *
     * @return void
     */
    protected function _removeExpiredRegistrations() {
        $this->deleteAll(array(
            $this->alias . '.email_authenticated' => 0,
            $this->alias . '.email_token_expires <' => date('Y-m-d H:i:s')
                )
        );
    }

}

?>
