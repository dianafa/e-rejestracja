<?php

class SpecialitiesController extends AppController {

	public $uses = array(
		'Speciality',
		'Doctor',
		'DoctorsSpeciality'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'index':
			case 'view':
				return true;
			case 'getByDoctor':
			case 'getAll':
				if ($this->request->is('ajax') || Configure::read('debug') == 2)
					return true;
				break;
			case 'connections':
				if ($user['Role']['name'] == 'receptionist')
					return true;
				break;
		}
		return parent::isAuthorized($user);
	}

	public function index() {
		$specialities = $this->Speciality->find('all', array(
			'recursive' => 2
		));
		$this->set('specialities', $specialities);
	}

	public function getAll() {
		$this->set('specialities', $this->Speciality->find('all', array(
			'fields' => array(
				'Speciality.id',
				'Speciality.name'
			),
			'recursive' => -1
		)));
		$this->layout = 'ajax';
		$this->render('get');
	}

	public function getByDoctor($doctor_id = null) {
		if (is_numeric($doctor_id)) {
			$this->set('specialities', $this->Speciality->find('all', array(
				'fields' => array(
					'Speciality.id',
					'Speciality.name'
				),
				'joins' => array(
					array(
						'table' => 'doctors_specialities',
						'alias' => 'DS',
						'conditions' => array(
							'Speciality.id = DS.speciality_id'
						)
					)
				),
				'conditions' => array(
					'DS.doctor_id' => $doctor_id
				)
			)));
			$this->layout = 'ajax';
			$this->render('get');
		} else
			$this->redirect('/');
	}

	public function view($speciality_id) {
		$speciality = $this->Speciality->find('first', array(
			'recursive' => 2,
			'conditions' => array(
				'Speciality.id' => $speciality_id
			)
		));
		if ($speciality) {
			$this->set('speciality', $speciality);
		} else {
			$this->Session->setFlash(__('Niestety taka specjalność nie istnieje'));
			$this->redirect(array(
				'action' => 'index'
			));
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Speciality->create();
			if ($this->Speciality->save($this->request->data)) {
				$this->Session->setFlash(__('Dodano specjalizację'));
				return $this->redirect(array(
					'action' => 'index'
				));
			}
			$this->Session->setFlash(__('Nie dodano specjalizacji'));
		}
	}

	public function connections() {
		$this->set('doctors', $this->Doctor->find('all', array(
			'fields' => array(
				'Doctor.id',
				'User.name',
				'User.surname'
			)
		)));
		$specialities = $this->Speciality->find('all');
		$this->set('specialities', $specialities);
		$connections = array();
		foreach ($specialities as $s)
			foreach ($s['Doctor'] as $d)
				$connections[$s['Speciality']['id']][$d['id']] = true;
		$this->set('connections', $connections);
	}

	public function connect($speciality_id, $doctor_id) {
		$c = $this->Speciality->DoctorsSpeciality->find('first', array(
			'conditions' => array(
				'speciality_id' => $speciality_id,
				'doctor_id' => $doctor_id
			)
		));
		if (!$c) {
			$this->Speciality->DoctorsSpeciality->save(array(
				'DoctorsSpeciality' => array(
					'speciality_id' => $speciality_id,
					'doctor_id' => $doctor_id
				)
			));
		}
		return $this->redirect(array(
			'action' => 'connections'
		));
	}

	public function disconnect($speciality_id, $doctor_id) {
		$this->Speciality->DoctorsSpeciality->deleteAll(array(
			'speciality_id' => $speciality_id,
			'doctor_id' => $doctor_id
		));
		return $this->redirect(array(
			'action' => 'connections'
		));
	}
}