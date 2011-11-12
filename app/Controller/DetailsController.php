<?php

class DetailsController extends AppController {

    /**
     * Name
     *
     * @var string
     */
    public $name = 'Details';
    /**
     * Components
     * 
     * @var array
     */
    public $components = array('Upload');
    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Html', 'Form');

    /**
     * beforeFilter callback
     *
     * @return void
     */
    public function beforeFilter() {
        //default title
        $this->set('title_for_layout', __('Users data'));

        parent::beforeFilter();
        $this->Auth->allow();
//        $this->Auth->autoRedirect = false;
//        $this->Auth->loginError = "auth err";
    }

    /**
     * Index
     *
     * @return void
     */
    public function index() {

        $this->set('title_for_layout', __('User data'));
        $this->set('menuType', 'settings');

//        debug($this->Auth->user('id'));
//        debug($this->Auth->user('email'));
//        debug($this->Auth->user('group_id'));

        $details = $this->Detail->find('all', array(
                    'contain' => array(),
                    'conditions' => array(
                        'Detail.user_id' => $this->Auth->user('id'),
                        'Detail.field LIKE' => 'user.%'),
                    'order' => 'Detail.position DESC'));

        $this->set('details', $details);

        $notAfter = null;
        
        $userDetail = $this->Detail->User->find('first', array(
                    'contain' => false,
                    'conditions' => array(
                        'User.id' => $this->Auth->user('id'),
                    ),
                    
            ));
        $notAfter = $userDetail['User']['certnotafter'];
        $this->set('notAfter',$notAfter);
        

        if (!is_dir(APP . 'Certs/' . $this->Auth->user('id'))) {
            mkdir(APP . 'Certs/' . $this->Auth->user('id'));
        }
    }

    /**
     * certupload
     * 
     * @param
     * @return
     */
    public function certupload() {
              
            $distanationDir = APP . 'Certs' . DS . $this->Auth->user('id');
            debug($distanationDir);        
        
    
        if (!empty($this->data)  && $this->Auth->user('id') != null  && $this->Auth->user('group_id') <= 4) {
            
            $file = array();
            
            // set the upload destination folder
            $distanationDir = APP . 'Certs' . DS . $this->Auth->user('id');
            
            //$distanationDirTmp = APP . 'certs' . DS . $this->Auth->user('id')."-tmp";
            if (!is_dir($distanationDir)) {
                mkdir($distanationDir);
            } 
            if (!is_dir($distanationDir."-mtp")) {
                mkdir($distanationDir."-mtp");
            }            
           
            
         

            // grab the file
            $file = $this->data['Detail']['cert'];
            //debug($file);
            if ($file['error'] == 4) {
                $this->Session->setFlash(__( 'File wasn\'t uploaded', true));
            } else {
                // upload the zip archive using the upload component
                $result = $this->Upload->upload($file, $distanationDir);

                if ($result == 1) {
                    // display error
                    $errors = $this->Upload->errors;
                    // piece together errors
                    if (is_array($errors)) {
                        $errors = implode(" ", $errors);
                    }

                      $this->Session->setFlash($errors);

                } else {
                    
                    $currentUser = $this->Detail->User->find('first', array(
                                'conditions' => array('User.id' => $this->Auth->user('id')),
                                'contain' => false
                                    )
                    );

                    $this->request->data['User']['id'] = $currentUser['User']['id'];
                    $this->request->data['User']['md5cert'] = $result['md5cert'];
                    $this->request->data['User']['certnotbefore'] = $result['notBefore'];
                    $this->request->data['User']['certnotafter'] = $result['notAfter'];
                    

                    $this->Detail->User->set($this->data);


                    $errors = $this->Detail->User->invalidFields();
                    

                    if(!isset($errors["md5cert"]) && isset($currentUser['User']['id'])){
                         if ($this->Detail->User->save($this->data, false) && $this->Upload->moveCertFiles($distanationDir) ) {
                            $this->Session->setFlash(__('Certificate was succesfully uploaded',true),'default',array('class'=>'flok'));
                        } else {
                            $this->Session->setFlash(__('Mistake with uploading zip archive. Try again'),'default',array('class'=>'fler'));
                        }                       
                    } else {
                        $this->Session->setFlash(__('This certificate isn\'t valid in system',true),'default',array('class'=>'fler'));
                         
                    }
                    

                    
                    //debug($currentUser);
                    //$this->Session->setFlash('uploaded');
                    
                }
            }
        } else {
            $this->Session->setFlash(__('Mistake with uploading zip archive.',TRUE),'default',array('class'=>'fler'));
        } 
        
        $this->redirect(array('plugin'=>null,'controller'=>'details','action' => 'index'), null, true);
        
    }

    /**
     * View
     *
     * @param string $id Detail ID
     * @return void
     */
    public function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__( 'Invalid Detail.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('detail', $this->Detail->read(null, $id));
    }

    /**
     * Add
     *
     * @return void
     */
    public function add() {
        if (!empty($this->data)) {
            $userId = $this->Auth->user('id');
            foreach ($this->data as $group => $options) {
                foreach ($options as $key => $value) {
                    $field = $group . '.' . $key;
                    $this->Detail->updateAll(
                            array('Detail.value' => "'$value'"), array('Detail.user_id' => $userId, 'Detail.field' => $field));
                }
            }
            $this->Session->setFlash(__( 'Saved', true));
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Edit
     *
     * Allows a logged in user to edit his own profile settings
     *
     * @param string $section Section name
     * @return void
     */
    public function edit($section = 'user') {
        if (!isset($section)) {
            $section = 'user';
        }

        if (!empty($this->data)) {
            $this->Detail->saveSection($this->Auth->user('id'), $this->data, $section);
            $this->data['Detail'] = $this->Detail->getSection($this->Auth->user('id'), $section);
            $this->Session->setFlash(sprintf(__( '%s details saved', true), ucfirst($section)));
        }

        if (empty($this->data)) {
            $this->data['Detail'] = $this->Detail->getSection($this->Auth->user('id'), $section);
        }

        $this->set('section', $section);
    }

    /**
     * Delete
     *
     * @param string $id Detail ID
     * @return void
     */
    public function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__( 'Invalid id for Detail', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Detail->delete($id)) {
            $this->Session->setFlash(__( 'Detail deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Admin Index
     *
     * @return void
     */
    public function admin_index() {
        $this->Detail->recursive = 0;
        $this->set('details', $this->paginate());
    }

    /**
     * Admin View
     *
     * @param string $id Detail ID
     * @return void
     */
    public function admin_view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__( 'Invalid Detail.', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('detail', $this->Detail->read(null, $id));
    }

    /**
     * Admin Add
     *
     * @return void
     */
    public function admin_add() {
        if (!empty($this->data)) {
            $this->Detail->create();
            if ($this->Detail->save($this->data)) {
                $this->Session->setFlash(__( 'The Detail has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__( 'The Detail could not be saved. Please, try again.', true));
            }
        }
        $groups = $this->Detail->Group->find('list');
        $users = $this->Detail->User->find('list');
        $this->set(compact('groups', 'users'));
    }

    /**
     * Admin edit
     *
     * @param string $id Detail ID
     * @return void
     */
    public function admin_edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__( 'Invalid Detail', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Detail->save($this->data)) {
                $this->Session->setFlash(__( 'The Detail has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__( 'The Detail could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Detail->read(null, $id);
        }
        $groups = $this->Detail->Group->find('list');
        $users = $this->Detail->User->find('list');
        $this->set(compact('groups', 'users'));
    }

    /**
     * Admin Delete
     *
     * @param string $id Detail ID
     * @return void
     */
    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__( 'Invalid id for Detail', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Detail->delete($id)) {
            $this->Session->setFlash(__( 'Detail deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
