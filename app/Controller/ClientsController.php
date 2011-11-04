<?php

App::import('Sanitize');

class ClientsController extends AppController {

    var $name = 'Clients';
    var $publicActions = array('getYnClData', 'regYnCl','todel');
    var $helpers = array('Text');

    private $pathToCerts = null;
   /**
     * Components
     * 
     * @var array
     */
    public $components = array('Users.Upload');

//--------------------------------------------------------------------

    function beforeFilter() {

        //default title
        $this->set('title_for_layout', __('Companies', true));
        //allowed actions
        //$this->Auth->allow('index', 'view','getYnClData');

        parent::beforeFilter();
        $this->Auth->autoRedirect = false;

        // swiching off Security component for ajax call

        if ($this->RequestHandler->isAjax() && in_array($this->action, $this->publicActions)) {
            $this->Security->validatePost = false;
        }

        $this->disableCache();
 
        if($this->Auth->user('id') != null && $this->Auth->user('group_id') == 3){
            
             $user = $this->Client->User->find('first', array(
                        'contain' => FALSE,
                        'conditions' => array('User.id' => $this->Auth->user('id'))
                            )
            );
            $certNotBefore = $user['User']['certnotbefore'];
            $certNotAfter = $user['User']['certnotafter'];
            $certMd5 = $user['User']['md5cert'];   
 
            
            
            if (!$this->Upload->checkCertValidity($certNotBefore, $certNotAfter, $certMd5)) {
                $this->Session->setFlash(__('You must upload yandex certificate to use system', TRUE), 'default', array('class' => 'fler'));
                $this->redirect(array('plugin' => null, 'controller' => 'details', 'action' => 'index'), null, true);
            } else {
                $this->pathToCerts = APP."certs".DS. $this->Auth->user('id');
            } 
            
         
        }
        




       
        
        
        
    }

    /**
     * @return type
     * 
     */
    public function index() {
        $this->set('title_for_layout', __('Clients', true));
        $this->set('menuType', 'regged');


    }

    

    /**
     *  retriving data from api.direct.yandex.ru via ajax
     * @param
     * 
     * @return type json
     * @access public
     */
    public function getYnClData() {

        if ($this->RequestHandler->isAjax()) {

            Configure::write('debug', 0);
            $this->autoLayout = false;
            $this->autoRender = FALSE;

            $contents = array();
            $resAllClients = array();
            $existedClients = array();
            
           
 
            
            if($this->pathToCerts == null){
                $contents = json_encode(array('error' =>__('No client\'s data from yandex!',true)));
                $this->header('Content-Type: application/json');
                return ($contents);
            } 
            


            
            $params = "";
            
            $resAllClients = json_decode($this->getYnData->getYnData($this->pathToCerts,'GetClientsList', $params), TRUE);           
                            
                if( isset($resAllClients["data"]) && $resAllClients['data'] != array() ) {
                    
                    
                    
                     $existedClients = $this->Client->find('all', array(
                                'conditions' => array('Client.agent_id'=> $this->Auth->user('id')),
                                'fields' => array('ynname','pass','User.email'),
                                'contain' => false
                            ));
                     
                    if( $existedClients != array()){
                       
                        
                        $newContens = $resAllClients;//json_decode($contents, TRUE);

                          foreach ($newContens['data'] as $k => $v) {

                            foreach ($existedClients as $val){

                                if($newContens['data'][$k]['Login'] == $val['Client']['ynname']){

                                    $newContens['data'][$k]['reg'] = 'yes';
                                    $newContens['data'][$k]['pass'] = $val['Client']['pass'];
                                    $newContens['data'][$k]['regemail'] = $val['User']['email'];
                                    break;

                                } else {
                                    $newContens['data'][$k]['reg'] = 'no';
                                }

                            }


                        }
                        
                       $contents = json_encode($newContens);
                       
                    } else {
                       $contents = json_encode($resAllClients); 
                    }
                    
                    
                } else {
                   //here we returning mistake from yandex
                   if($resAllClients){
                        $contents = json_encode($resAllClients);
                   }else{
                       $contents = json_encode(array('error' =>__('No client\'s data from yandex',true)));
                   }
                }
             
                
    
            
            $this->header('Content-Type: application/json');
            return ($contents);
        }
    }

    public function todel() {
        

        
        $this->set('clients',$resActiveClients);
        $this->set("campaignsId",$resAllCampaignsIdbatch10);
        $this->set("banners",$resAllBannersIDs);
        $this->set("bla",$resAllPhrases);
        
        //$this->set('campaigns',$resAllCampaigns);
    }
 

    /**
     * regging new client 
     * 
     * @param
     * @return type json
     * @access public
     */
    public function regYnCl() {

        $contents = array();

        if ($this->RequestHandler->isAjax()) {

            Configure::write('debug', 0);
            $this->autoLayout = FALSE;
            $this->autoRender = FALSE;

            if (isset($this->data['ynLogin']) && isset($this->data['email'])) {

                $userSavingRes = $this->Client->regclient($this->data['email']);

                if ($userSavingRes) {
                    //$contents = array('savedOne'=>'ok');
                    if (isset($userSavingRes['error'])) {
                        $contents["error"] = $userSavingRes;
                    } elseif (isset($userSavingRes['savedUserId'])) {
                        $this->data['Client']['ynname'] = Sanitize::paranoid($this->data['ynLogin'], array('-'));
                        $this->data['Client']['user_id'] = $userSavingRes['savedUserId'];
                        $this->data['Client']['agent_id'] = $this->Auth->user('id');
                        //@todo temp saving client's passwd in client table
                        $this->data['Client']['pass'] = $userSavingRes['savedUserPass'];
                        $this->Client->create();
                        if ($this->Client->save($this->data)) {



                            $this->Email->to = '4116457@mail.ru'; //$userSavingRes['savedUserEmail'];
                            $this->Email->subject = 'Welcome to our really cool thing';
                            $this->Email->replyTo = 'support@example.com';
                            $this->Email->template = 'simple_message';
                            $this->Email->sendAs = 'both';
                            
                            $this->Email->smtpOptions = array(
                                'port'=>'465',
                                'timeout'=>'30',
                                'host' => 'ssl://smtp.gmail.com',
                                'username'=>'quoondo@gmail.com',
                                'password'=>'Quoondo01',
                            );
                            
                            $this->set('User', $userSavingRes);
                            
                            
                            $this->Email->delivery = 'smtp';
                     //add  $contents['notSendEmail'] if email not send correct      
                            $this->Email->send();
                        }
                        $contents = $userSavingRes;
                    }
                } else {
                    $contents["error"] = 'User not saved';
                }
            } else {
                $contents["error"] = 'error';
            }



            $contents = json_encode($contents);
            $this->header('Content-Type: application/json');
            return ($contents);
        }
    }

}

?>
