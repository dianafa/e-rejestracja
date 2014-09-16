<?php
App::uses('AppController', 'Controller');

class RegistrationsController extends AppController {

	public $uses = array(
		'Registration',
		'Doctor',
		'Speciality',
		'Patient',
		'Visit'
	);

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'add':
				if (($user['Role']['name'] === 'patient') || ($user['Role']['name'] === 'admin'))
					return true;
				else
					// Wyjątek: tylko pacjent może się zarejestrować, nikt inny (nawet admin, w celach technicznych)
					return false;
				break;
			case 'index':
				if ($user['Role']['name'] === 'patient')
					return false;
				else
					// Wyjątek: tylko pacjent nie moze ogladac rejestracji z calej przychodni
					return true;
			case 'my':
				if ($user['Role']['name'] === 'patient')
					return true;
				else
					return false;
			case 'delete':
				if ($user['Role']['name'] === 'patient') {
					// Pacjent może usunąć tylko swoje rejestracje, reszta zalogowanych może każdą
					$registration = $this->Registration->find('first', array(
						'conditions' => array(
							'Registration.id' => $this->request->params['pass'][0],
							'Patient.user_id' => $user['id']
						)
					));
					if ($registration)
						return true;
					else
						return false;
				} else
					return true;
		}
		return parent::isAuthorized($user);
	}

	public function index() {
		$this->set('registrations', $this->Registration->find('all', array(
			'recursive' => 2
		)));
	}

	public function my() {
		$this->set('registrations', $this->Registration->find('all', array(
			'conditions' => array(
				'Patient.user_id' => $this->Auth->user('id')
			),
			'recursive' => 2
		)));
	}

	public function add() {
		if ($this->request->is('post')) {
			$count = $this->Doctor->find('count', array(
				'conditions' => array(
					'Doctor.id' => $this->request->data['Registration']['doctor_id'],
					'DS.speciality_id' => $this->request->data['Registration']['speciality_id']
				),
				'joins' => array(
					array(
						'table' => 'doctors_specialities',
						'alias' => 'DS',
						'conditions' => array(
							'Doctor.id = DS.doctor_id'
						)
					)
				)
			));
			if ($count >= 1) {
				$patient = $this->Patient->find('first', array(
					'conditions' => array(
						'User.id' => $this->Auth->User('id')
					)
				));
				if ($patient) {
					$this->request->data['Registration']['patient_id'] = $patient['Patient']['id'];
					$this->request->data['Registration']['time'] = date("Y-m-d");
					if ($this->Registration->save($this->request->data)) {
						$this->Session->setFlash(__('Udało się zarejestrować'));
						$this->redirect(array(
							'controller' => 'registrations',
							'action' => 'my'
						));
					} else {
						$this->Session->setFlash(__('Niestety, nie udało się zarejestrować. Nie wiemy czemu.'));
						$this->redirect(array(
							'controller' => 'registrations',
							'action' => 'add'
						));
					}
				} else {
					$this->Session->setFlash(__('Najpierwiej uzuepłnij swój profil'));
					$this->redirect(array(
						'controller' => 'patients',
						'action' => 'editProfile'
					));
				}
			}
			$this->Session->setFlash(__('Niestety ten lekarz nie obsługuje tej specjalności'));
			$this->redirect(array(
				'controller' => 'registrations',
				'action' => 'add'
			));
		}
		$this->set('doctors', $this->Doctor->find('all'));
		$this->set('specialities', $this->Speciality->find('all'));
	}

	public function delete($registration_id) {
		if ($this->request->is('post')) {
			if (isset($this->request->data['yes'])) {
				if ($this->Registration->deleteAll(array(
					'Patient.user_id' => $this->Auth->user('id'),
					'Registration.id' => $registration_id
				)))
					$this->Session->setFlash(__('Rejestrację anulowano'));
				else
					$this->Session->setFlash(__('Coś poszło nie tak. Nie wiemy czemu.'));
			}
			$this->redirect(array(
				'action' => 'my'
			));
		} else {
			$reg = $this->Registration->find('first', array(
				'conditions' => array(
					'Registration.id' => $registration_id,
					'Patient.user_id' => $this->Auth->user('id')
				)
			));
			if ($reg)
				$this->set('registration', $reg);
			else
				$this->redirect(array(
					'action' => 'my'
				));
		}
	}
}
