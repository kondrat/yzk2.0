<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {

    var $components = array(
        'Security',
        'Cookie',
        'Session',
        'Auth' => array(
            'authorize' => 'actions',
            'actionPath' => 'controllers/',
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
                'plugin' => null,
                'admin' => false,               
            ),
            'fields'=> array(
                'username' => 'email',
                'password' => 'password'
            )
        //'allowedActions' => array('')
        ),
        //'Acl',
        //'getYnData',
        'RequestHandler',
        'Email',
        'DebugKit.Toolbar'
    );
    var $helpers = array('Session', 'Js', 'Html', 'Form', 'Cache');
    var $publicControllers = array('pages', 'test');

//--------------------------------------------------------------------
    function beforeFilter() {
        //loading file vars.php from conf directory. contains configuration
        Configure::load('vars');
 
        if (isset($this->Auth)) {

            if ($this->viewPath == 'pages' && $this->params['action'] != 'admin_index') {
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

    function isAuthorized() {

        if ('1' == '1') {
            return true;
        } else {
            return false;
        }
        return true;
    }

//--------------------------------------------------------------------

    function beforeRender() {
        if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
            $this->layout = 'admin';
        }
    }

//--------------------------------------------------------------------
}

?>
