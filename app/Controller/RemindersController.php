<?php
App::uses('AppController', 'Controller');

class RemindersController extends AppController {

	public $uses = array(
		'Reminder',
		'Visit',
		'Setting'
	);

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'add':
				return true;
		}
		return parent::isAuthorized($user);
	}

	public function add($visit_id) {
		if ($visit_id) {
			$visit = $this->Visit->find('first', array(
				'recursive' => 2,
				'conditions' => array(
					'Visit.id' => $visit_id
				)
			));
			if ($visit) {
				$this->set('visit', $visit);
				if ($this->request->is('post')) {
					$this->Reminder->create();
					$this->request->data['Reminder']['visit_id'] = $visit_id;
					$y = $this->request->data['Reminder']['time']['year'];
					$m = $this->request->data['Reminder']['time']['month'];
					$d = $this->request->data['Reminder']['time']['day'];
					$h = $this->request->data['Reminder']['time']['hour'];
					$mi = $this->request->data['Reminder']['time']['min'];
					$this->request->data['Reminder']['time'] = date('Y-m-d H:i', mktime($h, $mi, 0, $m, $d, $y));
					if ($this->Reminder->save($this->request->data)) {
						$this->Session->setFlash(__('Udało się pomyślnie zapisać przypomnienie'));
						return $this->redirect(array(
							'controller' => 'visits',
							'action' => 'index',
							'planned'
						));
					}
				}
			} else {
				$this->Session->setFlash(__('Niestety taka wizyta nie istnieje'));
				return $this->redirect(array(
					'controller' => 'visits',
					'action' => 'index',
					'planned'
				));
			}
		} else {
			$this->Session->setFlash(__('Przypomnienie musi być powiązane z wizytą. Wizytę proszę wybrać z listy'));
			return $this->redirect(array(
				'controller' => 'visits',
				'action' => 'index',
				'planned'
			));
		}
	}

	public function run($code = null) {
		if ($code) {
			$codeOk = $this->Setting->find('count', array(
				'conditions' => array(
					'name' => 'remindersCron_code',
					'value' => $code
				)
			));
			if ($codeOk) {
				$reminders = $this->Reminder->find('all', array(
					/* Pola miały być ograniczone ze względu na wydajność przy recursive => 3, ale coś nie chciało działać */
					/*
					 * 'fields' => array( 'Reminder.id', 'Visit.Patient.id', 'Visit.Patient.phone', 'Visit.note', 'Visit.id', 'Visit.Doctor.User.name', 'Visit.Doctor.User.surname' ),
					 */
					'recursive' => 3,
					'conditions' => array(
						'Reminder.time <=' => date('Y-m-d H:i'),
						'Reminder.sent' => 0
					)
				));
				if ($reminders) {
					$ozeki_username = $this->Setting->find('first', array(
						'fields' => array(
							'value'
						),
						'conditions' => array(
							'name' => 'ozeki_username'
						)
					));
					$ozeki_username = $ozeki_username['Setting']['value'];
					$ozeki_password = $this->Setting->find('first', array(
						'fields' => array(
							'value'
						),
						'conditions' => array(
							'name' => 'ozeki_password'
						)
					));
					$ozeki_password = $ozeki_password['Setting']['value'];
					$ozeki_url = $this->Setting->find('first', array(
						'fields' => array(
							'value'
						),
						'conditions' => array(
							'name' => 'ozeki_url'
						)
					));
					$ozeki_url = $ozeki_url['Setting']['value'];
					foreach ($reminders as $r) {
						if ($r['Visit']['patient_id'] != NULL) {
							$nr = $r['Visit']['Patient']['phone'];
							if (substr($nr, 0, 1) != '+')
								$nr = '+48' . $nr;
							$content = urlencode("Przypomnienie o wizycie u lekarza {$r['Visit']['Doctor']['User']['name']} {$r['Visit']['Doctor']['User']['surname']}. {$r['Visit']['note']}");
							$url = $ozeki_url . "username={$ozeki_username}&password={$ozeki_password}&action=sendmessage&messagetype=SMS:TEXT&recipient={$nr}&messagedata={$content}";
							$x;
							$x = fopen($url, 'r');
							if (!$x) {
								$this->Session->setFlash("Nie wysłano przypomnienia {$r['Reminder']['id']} dla wizyty {$r['Visit']['id']}");
							} else {
								$r['Reminder']['sent'] = 1;
								$this->Reminder->save($r);
							}
						}
					}
					$this->Session->setFlash(__('Pomyślnie rozesłano przypomnienia'));
				} else {
					$this->Session->setFlash(__('Nie było przypomnień do rozesłania'));
				}
			}
		}
		return $this->redirect('/');
	}

	public function adminRun() {
		$code = $this->Setting->find('first', array(
			'conditions' => array(
				'name' => 'remindersCron_code'
			)
		));
		return $this->run($code['Setting']['value']);
	}
}