<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html', 'Session');

/**
 * This controller does not use a model
 *
 * @var array
 * @access public
 */
	var $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
                //just in case if user type home page in manualy. all links 'home' are ajusted in other places (default.ctp)
                if($this->Auth->user('group_id') == 3 ){
                    $this->redirect(array('plugin'=> null,'controller'=>'clients','action'=>'index'));
                } elseif ($this->Auth->user('group_id') == 4) {
                    $this->redirect(array('plugin'=> null,'controller'=>'campaigns','action'=>'index','client'=>$this->Auth->user('ynLogin')));
                } else {
                    
                }
            
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}
