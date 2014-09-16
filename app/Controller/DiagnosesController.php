<?php
App::uses('AppController', 'Controller');
class DiagnosesController extends AppController {

	public function index() {
		$this->set('diagnoses', $this->Diagnose->find('all'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Diagnose->create();
			if ($this->Diagnose->save($this->request->data)) {
				$this->Session->setFlash(__('Dodano chorobę'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			$this->Session->setFlash(__('Nie dodano choroby'));
		}
	}

	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid diagnose'));
		}
		$diagnose = $this->Diagnose->findById($id);
		if (!$diagnose) {
			throw new NotFoundException(__('Nie ma choroby o takim id'));
		}
		$this->set('diagnose', $diagnose);
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Choroba którą chcesz edytować nie istnieje'));
		}
		$diagnose = $this->Diagnose->findById($id);
		if (!$diagnose) {
			throw new NotFoundException(__('Choroba którą chcesz edytować nie istnieje!'));
		}
		if ($this->request->is(array(
			'post',
			'put'
		))) {
			$this->Diagnose->id = $id;
			if ($this->Diagnose->save($this->request->data)) {
				$this->Session->setFlash(__('Zmiany zostały zapisane'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			$this->Session->setFlash(__('Edycja zakończona niepowodzeniem'));
		}
		if (!$this->request->data) {
			$this->request->data = $diagnose;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		if ($this->Diagnose->delete($id)) {
			$this->Session->setFlash(__('Choroba o id: %s został usunięty z bazy', h($id)));
			return $this->redirect(array(
				'action' => 'index'
			));
		}
	}
}
