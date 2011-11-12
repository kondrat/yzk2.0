<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    var $components = array(
        'Security'=> array(
            'csrfUseOnce' => false
        ),
        'Cookie',
        'Auth' => array(
            //'loginRedirect' => array('controller' => 'posts', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authorize' => array('Controller'),
            //'authError' => __('Did you really think you are allowed to see that?'),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email','password'=>'password')
                )
            )
        ),
        'Session',
//        'Auth' => array(
//            'authorize' => 'actions',
//            'actionPath' => 'controllers/',
//            'loginAction' => array(
//                'controller' => 'users',
//                'action' => 'login',
//                'plugin' => null,
//                'admin' => false, 
//                'loginRedirect' => array('controller' => 'posts', 'action' => 'index'),
//                'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home')
//            ),
//            'fields'=> array(
//                'id'=>'id',
//                'username' => 'email',
//                'password' => 'password'
//            )
//        //'allowedActions' => array('')
//        ),
        'Acl',
        //'getYnData',
        'RequestHandler',
        'Email',
        'DebugKit.Toolbar'
    );
    public $helpers = array('Session', 'Js', 'Html', 'Form', 'Cache');
    public $publicControllers = array('pages', 'test');

//--------------------------------------------------------------------
    function beforeFilter() {
        parent::beforeFilter();
        //loading file vars.php from conf directory. contains configuration
        Configure::load('vars');
       
        if (isset($this->Auth)) {

            if ($this->viewPath == 'Pages' && $this->params['action'] != 'admin_index' ) {
                
                $this->Auth->allow('*');
            } else {
                $this->Auth->authorize = 'controller';
                if (in_array(strtolower($this->params['controller']), $this->publicControllers)) {
                    //$this->Auth->allow('*');
                    $this->Auth->deny('pages/admin_index');
                }
            }
            //$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
        }
    }
    
    public function isAuthorized($user) {

        if ('1' == '1') {
            return true;
        } else {
            return false;
        }
        return false;
    }

//--------------------------------------------------------------------

    function beforeRender() {
        if ( isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
            $this->layout = 'admin';
        }
    }

//--------------------------------------------------------------------
}

?>
