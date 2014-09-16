<?php
App::uses('AppController', 'Controller');
class SettingsController extends AppController {
	public function isAuthorized($user) {
		return parent::isAuthorized($user);
	}

	public function index() {
		$this->set('settings', $this->Setting->find('all'));
	}

	public function edit($setting_id = null) {
		if ($this->request->is('post')) {
			if (is_numeric($setting_id)) {
				$setting = $this->Setting->findById($setting_id);
				if ($setting) {
					$s = array('id' => $setting_id, 'value' => $this->request->data['Setting']['value']);
					if ($this->Setting->save($s)) {
						$this->Session->setFlash(__('Udało się pomyślnie zapisać nową wartość ustawienia'));
					} else {
						$this->Session->setFlash(__('Niestety, coś poszło nie tak. Nie wiemy czemu.'));
					}
				} else {
					$this->Session->setFlash(__('Przeykro nam, takie ustawienie nie istnieje'));
				}
			}
			return $this->redirect(array('action' => 'edit'));
		}
		$this->set('settings', $this->Setting->find('all'));
	}

	public function get($setting_name) {
		$this->layout = 'ajax';
		$setting = $this->Setting->findByName($setting_name);
		if ($setting) {
			$this->set('setting', $setting);
		} else
			return 500;
	}
}