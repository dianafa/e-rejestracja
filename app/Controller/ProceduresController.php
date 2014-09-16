<?php
App::uses('AppController', 'Controller');
class ProceduresController extends AppController {

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'index':
				if ($this->Auth->user('Role.name') === 'patient')
					return false;
		}
		return parent::isAuthorized($user);
	}

	
	public function index() {
		$this->set('procedures', $this->Procedure->find('all'));
	}
}
