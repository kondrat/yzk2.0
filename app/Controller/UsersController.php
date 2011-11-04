<?php

/**
 * Users Users Controller
 *
 * @package users
 * @subpackage users.controllers
 */


class UsersController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Users';
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array();
    /**
     * Components
     *
     * @var array
     */
    public $components = array('Kcaptcha');
    /**
     * $paginate
     *
     * @var array $paginate
     */
    public $paginate = array('limit' => 5);

    /**
     * beforeFilter callback
     *
     * @return void
     */
    public function beforeFilter() {
        //default title
        $this->set('title_for_layout', __('Users data'));

        parent::beforeFilter();
        $this->Auth->allow(
                'reg', 'logout', 'kcaptcha', 'verify', 'reset_password', 'userNameCheck', 'change_password'
                , 'index', 'view', 'dashboard'
                //'acoset','aroset','permset','buildAcl'
        );
        $this->Auth->autoRedirect = false;
        $this->Auth->loginError = "auth err";

        $this->set('model', $this->modelClass);

        if ($this->action == 'login' && !empty($this->data)) {
            $data = $this->data;

            //for quick login. not to use it
//            if ($this->referer() === '/' || $this->referer() === 'cards/index') {
//
//                if (!isset($data['_Token']) || !isset($data['_Token']['fields']) || !isset($data['_Token']['key'])) {
//                    return false;
//                }
//                $token = $data['_Token']['key'];
//                if ($this->Session->check('_Token')) {
//                    $tokenData = unserialize($this->Session->read('_Token'));
//                    if ($tokenData['expires'] < time() || $tokenData['key'] !== $token) {
//                        return false;
//                    }
//                }
//            }
        }




        // swiching off Security component for ajax call				
        if ($this->RequestHandler->isAjax() && $this->action == 'userNameCheck') {
            $this->Security->validatePost = false;
        }
    }

    /**
     * ajax staff
     *
     */
    public function userNameCheck() {

        $contents = array();
        $token = '';
        $errors = array();
  

        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;

        if ($this->RequestHandler->isAjax()) {

            if (strpos(env('HTTP_REFERER'), trim(env('HTTP_HOST'), '/')) === false) {
                $this->Security->blackHole($this, 'Invalid referrer detected for this request!');
            }


            if (!isset($this->data['_Token']['key']) || ( $this->data['_Token']['key'] !== $this->params['_Token']['key'] )) {
                $this->Security->blackHole($this, 'Invalid referrer detected for this request!');
            }



            //don't foreget about santization and trimm
            if ( !isset($this->data['User']['email']) || $this->data['User']['email'] == null) {
                $this->Security->blackHole($this, 'Invalid referrer detected for this request!');
            }



            $this->User->set($this->data);


            $errors = $this->User->invalidFields();


            if (!isset($errors['email'])) {
                $contents['stat'] = 1;
            } else {
                $contents['stat'] = 0;
                $contents['error'] = $errors['email'];
            }

            $contents = json_encode($contents);
            $this->header('Content-Type: application/json');
            return ($contents);
        } else {
            $this->Security->blackHoleCallback = '_gotov';
            $this->Security->blackHole($this, 'You are not authorized to process this request!');
        }
    }

    /**
     * blackhole redirection
     *
     * @return void
     */
    private function _gotov() {
        $this->redirect(null, 404, true);
    }



    /**
     * kcaptcha stuff
     *
     * @return void
     */
    public function kcaptcha() {
        $this->kcaptcha->render();
    }

    /**
     * User register action
     *
     * @return void
     */
    public function reg() {

//        debug($this->Session->read('captcha'));

        $this->set('title_for_layout', __d('users','SignUp', true));
        $this->set('menuType', 'reg');

        
                if($this->Auth->user() && $this->Auth->user('group_id') == 3 ){
                    $this->redirect(array('plugin'=> null,'controller'=>'clients','action'=>'index'));
                } elseif ($this->Auth->user('group_id') == 4) {
                    $this->redirect(array('plugin'=> null,'controller'=>'campaigns','action'=>'index','client'=>$this->Auth->user('ynLogin')));
                }


        if (!empty($this->data)) {
            
            /*
             * @todo remove this 3 lines after we open public registation
             */
            
//            unset($this->data['User']['captcha']);
//            $this->Session->setFlash(__d('users', 'Registration hasn\'t been opened yet. Sorry for inconvinience', true), 'default', array('class' => 'fler'));       
//            $this->redirect('/');           
            
            //prepering data from kcaptch component to check.			
            $this->data['User']['captcha2'] = $this->Session->read('captcha');

            $this->data['User']['group_id'] = '3';

            $user = $this->User->register($this->data);

            if ($user !== false) {
                //$a = $this->User->read();
                $this->Auth->login($user);
                $this->Session->setFlash(__d('users', 'Your account has been created.', true), 'default', array('class' => 'flok'));
                $this->redirect(array('plugin'=>'users','controller' => 'details', 'action' => 'index'), null, true);

                //verification email version
                /*
                  $this->set('user', $user);
                  $this->_sendVerificationEmail($user[$this->modelClass]['email']);
                  $this->Session->setFlash(__d('users', 'Your account has been created. You should receive an e-mail shortly to authenticate your account. Once validated you will be able to login.', true),'default', array('class' => 'flok'));
                  $this->redirect(array('action'=> 'login'));
                 */
            } else {

                $errors = $this->User->invalidFields();


                unset($this->data['User']['captcha']);
                $this->Session->setFlash(__d('users', 'New user\'s accout hasn\'t been created', true), 'default', array('class' => 'fler'));
            }
        }
    }



    /**
     * Confirm email action
     *
     * @param string $type Type
     * @return void
     */
    public function verify($type = 'email') {
        if (isset($this->passedArgs['1'])) {
            $token = $this->passedArgs['1'];
        } else {
            $this->redirect(array('action' => 'login'), null, true);
        }

        if ($type === 'email') {
            $data = $this->User->validateToken($token);
        } else {
            $this->Session->setFlash(__d('users', 'There url you accessed is not longer valid', true));
            $this->redirect('/');
        }

        if ($data !== false) {
            $email = $data[$this->modelClass]['email'];
            unset($data[$this->modelClass]['email']);


            if ($type === 'email') {
                $data[$this->modelClass]['active'] = 1;
            }

            if ($this->User->save($data, false)) {

                unset($data);
                //$data[$this->modelClass]['active'] = 1;
                //$this->User->save($data);
                $this->Session->setFlash(__d('users', 'Your e-mail has been validated!', true));
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(__d('users', 'There was an error trying to validate your e-mail address. Please check your e-mail for the URL you should use to verify your e-mail address.', true));
                $this->redirect('/');
            }
        } else {
            $this->Session->setFlash(__d('users', 'The url you accessed is not longer valid', true));
            $this->redirect('/');
        }
    }

    /**
     * Allows the user to enter a new password, it needs to be confirmed
     *
     * @return void
     */
    public function change_password() {
        if (!empty($this->data)) {
            //$this->data[$this->modelClass]['id'] = $this->Auth->user('id');
            $user = $this->Auth->user();
            if ($this->User->changePassword(Set::merge($user, $this->data))) {
                $this->Session->setFlash(__d('users', 'Password changed.', true));
                $this->redirect('/');
            }
        }
    }

    /**
     * Common login action
     *
     * @return void
     */
    public function login() {

        $this->set('title_for_layout', __('Login'));
        $this->set('menuType', 'login');

        if ($this->Auth->user()) {
            //debug($this->Auth->user('id'));
            $userId = $this->User->id = $this->Auth->user('id');
            $this->User->saveField('last_login', date('Y-m-d H:i:s'));

            
            if (!empty($this->data)) {
                $data = $this->data[$this->modelClass];
                $this->_setCookie();
            }
            $userGroupId = $this->Auth->user('group_id');

            if ($userGroupId == 3) {
                $this->redirect(array('plugin' => null, 'controller' => 'clients', 'action' => 'index'));
            } elseif ($userGroupId == 4) {
                
                $regUserYnLogin = $this->User->Client->find('first',array(
                    'conditions'=>array( 'Client.user_id'=>$userId),
                    'contain'=>false
                ));
                
                $this->Session->write('Auth.User.ynLogin', $regUserYnLogin['Client']['ynname']);
                $this->Session->setFlash(sprintf(__d('users', '%s you u have successfully logged in group 3', true), $this->Auth->user('username')));
                $this->redirect(array('plugin' => null, 'controller' => 'campaigns', 'action' => 'index','client'=>$regUserYnLogin['Client']['ynname']));
            }

            if ($this->here == $this->Auth->loginRedirect) {
                //debug($this->Auth->loginRedirect);
                $this->Auth->loginRedirect = '/';
                //temp solution
            }

            $this->Session->setFlash(sprintf(__d('users', '%s you u have successfully logged in', true), $this->Auth->user('username')));


            if (empty($data['return_to'])) {
                $data['return_to'] = null;
            }
            //$this->redirect($this->Auth->redirect($data['return_to']));
        }

        if (isset($this->params['named']['return_to'])) {
            $this->set('return_to', urldecode($this->params['named']['return_to']));
        } else {
            $this->set('return_to', false);
        }


        if (!empty($this->data)) {
            if (!$this->Auth->login($this->data)) {
                $this->data['User']['password'] = null;
                $this->Session->setFlash(__d('users', 'Check your login and password', true), 'default', array('class' => 'fler'));
            }
        }
    }

    /**
     * Common logout action
     *
     * @return void
     */
    public function logout() {
        $tempUserName = sprintf(__d('users', 'Good bay, %s', true), $this->Auth->user('username'));
        $this->Auth->logout();
        $this->Session->setFlash($tempUserName, 'default', array('class' => 'flok'));
        $this->redirect('/');
    }

    /**
     * Reset Password Action
     *
     * Handles the trigger of the reset, also takes the token, validates it and let the user enter
     * a new password.
     *
     * @param string $token Token
     * @param string $user User Data
     * @return void
     */
    public function reset_password($token = null, $user = null) {
        if (empty($token)) {
            $admin = false;
            if ($user) {
                $this->data = $user;
                $admin = true;
            }
            $this->_sendPasswordReset($admin);
        } else {
            $this->__resetPassword($token);
        }
    }

    /**
     * Checks if the email is in the system and authenticated, if yes create the token
     * save it and send the user an email
     *
     * @param boolean $admin Admin boolean
     * @param array $options Options
     * @return void
     */
    protected function _sendPasswordReset($admin = null, $options = array()) {
        $defaults = array(
            'from' => 'noreply@' . env('HTTP_HOST'),
            'subject' => __d('users', 'Password Reset', true),
            'template' => 'password_reset_request');

        $options = array_merge($defaults, $options);

        if (!empty($this->data)) {
            $user = $this->User->passwordReset($this->data);

            if (!empty($user)) {
                $this->set('token', $user[$this->modelClass]['password_token']);
                //$this->Email->to = $user[$this->modelClass]['email'];
                $this->Email->to = '4116457@mail.ru';
                $this->Email->from = $options['from'];
                $this->Email->subject = $options['subject'];
                $this->Email->template = $options['template'];

                /* SMTP Options */

                            $this->Email->smtpOptions = array(
                                'port'=>'465',
                                'timeout'=>'30',
                                'host' => 'ssl://smtp.gmail.com',
                                'username'=>'quoondo@gmail.com',
                                'password'=>'Quoondo01',
                            );
                            
                $this->Email->delivery = 'smtp';
                $this->set('smtp-errors', $this->Email->smtpError);
                //$this->Email->delivery = 'debug';
                $this->Email->send();

                if ($admin) {
                    $this->Session->setFlash(sprintf(__d('users', '%s has been sent an email with instruction to reset their password.', true), $user[$this->modelClass]['email']));
                    $this->redirect(array('action' => 'index', 'admin' => true));
                } else {
                    $this->Session->setFlash(__d('users', 'You should receive an email with further instructions shortly', true));
                    $this->redirect(array('action' => 'login'));
                }
            } else {
                $this->Session->setFlash(__d('users', 'No user was found with that email.', true));
                $this->redirect(array('plugin'=>'users','controller'=>'users','action'=>'reset_password'));
            }
        }
        $this->render('request_password_change');
    }

    /**
     * This method allows the user to change his password if the reset token is correct
     *
     * @param string $token Token
     * @return void
     */
    private function __resetPassword($token) {
        $user = $this->User->checkPasswordToken($token);
        if (empty($user)) {
            $this->Session->setFlash(__d('users', 'Invalid password reset token, try again.', true));
            $this->redirect(array('action' => 'reset_password'));
        }

        if (!empty($this->data)) {
            if ($this->User->resetPassword(Set::merge($user, $this->data))) {
                $this->Session->setFlash(__d('users', 'Password changed, you can now login with your new password.', true));
                $this->redirect($this->Auth->loginAction);
            }
        }

        $this->set('token', $token);
    }

    /**
     * Sends the verification email
     *
     * This method is protected and not private so that classes that inherit this
     * controller can override this method to change the varification mail sending
     * in any possible way.
     *
     * @param string $to Receiver email address
     * @param array $options EmailComponent options
     * @return boolean Success
     */
    protected function _sendVerificationEmail($to = null, $options = array()) {
        $defaults = array(
            'from' => 'noreply@' . env('HTTP_HOST'),
            'subject' => __d('users', 'Account verification', true),
            'template' => 'account_verification');

        $options = array_merge($defaults, $options);

        $this->Email->to = $to;
        $this->Email->from = $options['from'];
        $this->Email->subject = $options['subject'];
        $this->Email->template = $options['template'];

        return $this->Email->send();
    }
    /**
     * Sets the cookie to remember the user
     *
     * @param array Cookie component properties as array, like array('domain' => 'yourdomain.com')
     * @param string Cookie data keyname for the userdata, its default is "User". This is set to User and NOT using the model alias to make sure it works with different apps with different user models accross different (sub)domains.
     * @return void
     * @link http://api13.cakephp.org/class/cookie-component
     */
    protected function _setCookie($options = array(), $cookieKey = 'User') {
        if (empty($this->data[$this->modelClass]['remember_me'])) {
            $this->Cookie->delete($cookieKey);
        } else {
            $validProperties = array('domain', 'key', 'name', 'path', 'secure', 'time');
            $defaults = array(
                'name' => 'rememberMe');

            $options = array_merge($defaults, $options);
            foreach ($options as $key => $value) {
                if (in_array($key, $validProperties)) {
                    $this->Cookie->{$key} = $value;
                }
            }

            $cookieData = array();
            $cookieData[$this->Auth->fields['username']] = $this->data[$this->modelClass][$this->Auth->fields['username']];
            $cookieData[$this->Auth->fields['password']] = $this->data[$this->modelClass][$this->Auth->fields['password']];
            $this->Cookie->write($cookieKey, $cookieData, true, '1 Month');
        }
        unset($this->data[$this->modelClass]['remember_me']);
    }
//--------------------------------------------------------------------	

    /**
     * user management part.
     *
     * Not done yet.
     */
    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    /**
     * The homepage of a users giving him an overview about everything
     *
     * @return void
     */
    public function dashboard() {
        $user = $this->User->read(null, $this->Auth->user('id'));
        $this->set('user', $user);
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__d('users','Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->User->create();
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__d('users','The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('users','The user could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__d('users','Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__d('users','The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('users','The user could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__d('users','Invalid id for user', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__d('users','User deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__d('users','User was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__d('users','Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    function admin_add() {
        if (!empty($this->data)) {
            $this->User->create();
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__d('users','The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('users','The user could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__d('users','Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__d('users','The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('users','The user could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__d('users','Invalid id for user', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__d('users','User deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__d('users','User was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
