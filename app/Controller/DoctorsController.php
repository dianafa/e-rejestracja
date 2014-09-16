<?php

class DoctorsController extends AppController {

	public $components = array(
		'Session',
		'TimeSlot'
	);

	public $uses = array(
		'Doctor',
		'Setting'
	);

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'index':
			case 'view':
			case 'viewFreeWeek':
				return true;
			case 'getBySpeciality':
			case 'getAll':
				if ($this->request->is('ajax') || Configure::read('debug') == 2)
					return true;
				break;
		}
		return parent::isAuthorized($user);
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function index() {
		$doctors = $this->Doctor->find('all');
		$this->set('doctors', $doctors);
	}

	public function getAll() {
		$this->set('doctors', $this->Doctor->find('all', array(
			'fields' => array(
				'Doctor.id',
				'User.name',
				'User.surname'
			)
		)));
		$this->layout = 'ajax';
		$this->render('get');
	}

	public function getBySpeciality($speciality_id = null) {
		if (is_numeric($speciality_id)) {
			$this->set('doctors', $this->Doctor->find('all', array(
				'fields' => array(
					'Doctor.id',
					'User.name',
					'User.surname'
				),
				'joins' => array(
					array(
						'table' => 'doctors_specialities',
						'alias' => 'DS',
						'conditions' => array(
							'Doctor.id = DS.doctor_id'
						)
					)
				),
				'conditions' => array(
					'DS.speciality_id' => $speciality_id
				)
			)));
			$this->layout = 'ajax';
			$this->render('get');
		} else
			$this->redirect('/');
	}

	public function view($doctor_id) {
		$doctor = $this->Doctor->findById($doctor_id);
		if ($doctor) {
			$this->set('doctor', $doctor);
		} else {
			$this->Session->setFlash(__('Niestety taki lekarz nie istnieje'));
			$this->redirect(array(
				'action' => 'index'
			));
		}
	}

	public function viewFreeWeek($doctor_id) {
		if ($this->request->is('ajax'))
			$this->layout = 'ajax';
		$this->set('beginning', $this->request->data['beginning']);
		$slotSize = $this->Setting->find('first', array(
			'fields' => array(
				'value'
			),
			'conditions' => array(
				'name' => 'timeslotSize'
			)
		));
		$openTime = $this->Setting->find('first', array(
			'fields' => array(
				'value'
			),
			'conditions' => array(
				'name' => 'openTime'
			)
		));
		$openTime = explode(':', $openTime['Setting']['value']);
		$openTime = array('hour' => $openTime[0], 'min' => $openTime[1]);
		$closeTime = $this->Setting->find('first', array(
			'fields' => array(
				'value'
			),
			'conditions' => array(
				'name' => 'closeTime'
			)
		));
		$closeTime = explode(':', $closeTime['Setting']['value']);
		$closeTime = array('hour' => $closeTime[0], 'min' => $closeTime[1]);
		$futureDays = $this->Setting->find('first', array(
			'fields' => array(
				'value'
			),
			'conditions' => array(
				'name' => 'futurePreviewDays'
			)
		));
		$date = $this->request->data['beginning'];
		$days = array();
		for ($i=0; $i < $futureDays['Setting']['value']; $i++) {
			$date = $this->TimeSlot->correctDate($date);
			$date['month'] = sprintf("%02d", $date['month']);
			$date['day'] = sprintf("%02d", $date['day']);
			$days[$i] = $date;
			$date['day']++;
		}
		$this->set('days', $days);

		$hours = array();
		$time = $openTime;
		$hour = array();
		while ($this->TimeSlot->timeLess($time, $closeTime)) {
			$time['hour'] = sprintf("%02d", $time['hour']);
			$time['min'] = sprintf("%02d", $time['min']);
			$hour['time'] = $time;

			$hour['days'] = array();
			for ($i = 0; $i < $futureDays['Setting']['value']; $i++) {
				$t = array(
					'year' => $days[$i]['year'],
					'month' => $days[$i]['month'],
					'day' => $days[$i]['day'],
					'hour' => $time['hour'],
					'min' => $time['min']
				);
				$hour['days'][$i] = $this->TimeSlot->checkDoctorFree($doctor_id, $t);
			}

			$hours[] = $hour;

			$time['min'] += $slotSize['Setting']['value'];
			$time['hour'] += (int)($time['min'] / 60);
			$time['min'] = $time['min'] % 60;
		}

		$this->set('hours', $hours);
	}
}