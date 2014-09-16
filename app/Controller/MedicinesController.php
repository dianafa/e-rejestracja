<?php
App::uses('AppController', 'Controller');
class MedicinesController extends AppController {

	public function index() {
		$this->set('medicines', $this->Medicine->find('all'));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Medicine->create();
			if ($this->Medicine->save($this->request->data)) {
				$this->Session->setFlash(__('Dodano lek'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			$this->Session->setFlash(__('Nie dodano leku'));
		}
	}

	public function view($id = null) {
            if (empty($id))
            return $this->redirect(array(
					'action' => 'index'
				));
		if (!$id) {
			throw new NotFoundException(__('Invalid medicine'));
		}
		$medicine = $this->Medicine->findById($id);
		if (!$medicine) {
			throw new NotFoundException(__('Nie ma leku o takim id'));
		}
		$this->set('medicine', $medicine);
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Lek który chcesz edytować nie istnieje'));
		}
		$medicine = $this->Medicine->findById($id);
		if (!$medicine) {
			throw new NotFoundException(__('Lek który chcesz edytować nie istnieje!'));
		}
		if ($this->request->is(array(
			'post',
			'put'
		))) {
			$this->Medicine->id = $id;
			if ($this->Medicine->save($this->request->data)) {
				$this->Session->setFlash(__('Zmiany zostały zapisane'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			$this->Session->setFlash(__('Edycja zakończona niepowodzeniem'));
		}
		if (!$this->request->data) {
			$this->request->data = $medicine;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		if ($this->Medicine->delete($id)) {
			$this->Session->setFlash(__('Lek o id: %s został usunięty z bazy', h($id)));
			return $this->redirect(array(
				'action' => 'index'
			));
		}
	}
}
