<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $uses = array(
		'User',
		'Role',
		'Doctor',
		'Receptionist',
		'Patient',
		'Visit',
		'Sex'
	);

	public function beforeFilter() {
		$this->Auth->allow(array(
			'login'
		));
	}

	public function isAuthorized($user) {
		switch ($this->action) {
			case 'logout':
			case 'change_pass':
				return true;
			case 'profile':
			case 'editProfile':
				if ($this->Auth->user('Role.name') === 'patient')
					return true;
				else
					return false;
		}
		return parent::isAuthorized($user);
	}

	public function add($role) {
		if (empty($role))
			return $this->redirect('/');
		$Role = $this->Role->find('first', array(
			'fields' => array(
				'id',
				'description'
			),
			'conditions' => array(
				'name' => $role
			),
			'recursive' => -1
		));
		if (count($Role) === 0)
			return $this->redirect('/');
		if ($this->request->is('post')) {
			if ($this->request->data['User']['PESEL2'] != $this->request->data['User']['PESEL']) {
				$this->Session->setFlash(__('Błędnie powtórzono PESEL, spróbuj jeszcze raz'));
				$this->request->data['User']['PESEL2'] = $this->request->data['User']['PESEL'] = '';
				$this->request->data['User']['password2'] = $this->request->data['User']['password'] = '';
				return;
			}
			if ($this->request->data['User']['password2'] != $this->request->data['User']['password']) {
				$this->Session->setFlash(__('Błędnie powtórzono hasło, spróbuj jeszcze raz'));
				$this->request->data['User']['password2'] = $this->request->data['User']['password'] = '';
				return;
			}
			if ($this->User->find('count', array(
				'conditions' => array(
					'PESEL' => $this->request->data['User']['PESEL'],
					'Role.id' => $Role['Role']['id']
				)
			)) > 0) {
				$this->Session->setFlash(__($Role['Role']['description']) . __(' o takim PESELu już istnieje. Upewnij się, że dobrze wpisałeś/aś'));
				$this->request->data['User']['password2'] = $this->request->data['User']['password'] = '';
				return;
			}
			$this->User->create();
			$this->request->data['User']['role_id'] = $Role['Role']['id'];
			if ($this->User->save($this->request->data)) {
				switch ($role) {
					case 'doctor':
						{
							$this->Doctor->save(array(
								'user_id' => $this->User->id
							));
							break;
						}
					case 'receptionist':
						{
							$this->Receptionist->save(array(
								'user_id' => $this->User->id
							));
							break;
						}
					case 'patient':
						{
							// TODO: SAVING ONE-to-ONE profiles!!!
							break;
						}
				}
				$this->Session->setFlash(__($Role['Role']['description']) . __(' został zapisany'));
				return $this->redirect('/');
			}
			$this->Session->setFlash(__('Błąd podczas zapisywania użytkownika'));
		}
	}

	public function login() {
		// if ($this->request->is('post')) {
		// if ($this->Auth->login())
		// return $this->redirect($this->Auth->redirect());
		// $this->Session->setFlash(__('Niepoprawna nazwa użytkownika lub hasło'));
		if ($this->Auth->loggedIn()) { // sprawdzamy czy już zalogowany a jeśli tak przekierowanie na stronę główną
			$this->redirect('/');
		}
		if ($this->request->is('post')) { // kontrolujemy czy zapytanie zostało wysłane metodą 'post'
			if ($this->Auth->login()) { // logujemy się
				if ($this->request->data['User']['remember_me'] == 1) { // jeśli w formularzu zaznaczyliśmy 'zapamiętaj mnie' niszczymy ewentualnie już istniejace ciasteczko oraz tworzymy nowe
					unset($this->request->data['User']['remember_me']);
					$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
					$this->Cookie->write('remember_me_cookie', $this->request->data['User'], true, '2 weeks');
				}
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s')); // zapisujemy czas logowania
				$this->redirect($this->Auth->redirect('/')); // przekierowanie na stronę główną
			} else {
				$this->Session->setFlash('Nieprawidłowy login lub hasło'); // jeśli nie powiodło się
			}
		}
	}

	public function logout() {
		// eturn $this->redirect($this->Auth->logout());
		if ($this->Auth->loggedIn()) {
			$this->Cookie->delete('remember_me_cookie'); // niszczymy ciasteczko
			$this->redirect($this->Auth->logout()); // logout
		} else {
			$this->redirect(array(
				'action' => 'login'
			));
		}
	}

	public function index() {
		$this->set('users', $this->User->find('all'));
	}

	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid user'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			throw new NotFoundException(__('Nie ma użytkownika o takim id'));
		}
		$this->set('user', $user);
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User o id: %s został usunięty z bazy', h($id)));
			return $this->redirect(array(
				'action' => 'index'
			));
		}
	}

	/*
	 * public function change_pass() { // kontrolujemy czy zapytanie zostało wysłane metodą 'post' if ($this->request->is('post')) { if ($this->request->data ['User'] ['npassword2'] != $this->request->data ['User'] ['npassword']) { $this->Session->setFlash(__('Błędnie powtórzono hasło, spróbuj jeszcze raz')); $this->request->data ['User'] ['npassword2'] = $this->request->data ['User'] ['npassword'] = ''; return; } if ($this->request->data ['User'] ['password'] == $this->Auth->password($this->request->data ['User'] ['password'])) { //jesli ponadto haslo jest poprawne $this->request->data ['User'] ['password'] = $this->Auth->password($this->request->data ['User'] ['npassword2']); $this->redirect($this->Auth->redirect()); // przekierowanie na stronę główną } else { $this->Session->setFlash('Nieprawidłowe hasło'); // jeśli nie powiodło się } } }
	 */
	public function change_pass() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if (!empty($this->request->data['User']['npassword2']) && !empty($this->request->data['User']['npassword1'])) {
				$this->User->id = $this->Auth->user('id');
				$this->request->data['User']['password'] = $this->request->data['User']['npassword1'];
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash('Hasło zostało zmienione');
					return $this->redirect('/');
				} else {
					$this->Session->setFlash('Hasło nie zostało zmienione');
				}
			} else {
				$this->Session->setFlash('Password or retype not sent');
			}
		}
	}

	public function profile() {
		$patient = $this->Patient->find('first', array(
			'conditions' => array(
				'User.id' => $this->Auth->user('id')
			)
		));
		if (!$patient) {
			$this->Session->setFlash('Uzupełnij proszę swoje dane');
			$this->redirect(array(
				'action' => 'editProfile'
			));
		}
		$this->set('patient', $patient);
	}

	public function editProfile() {
		$patient = $this->Patient->find('first', array(
			'conditions' => array(
				'User.id' => $this->Auth->user('id')
			)
		));
		$this->set('sexes', $this->Sex->find('all'));
		if ($this->request->is('post')) {
			$y = $this->request->data['Patient']['birthdate']['year'];
			$m = $this->request->data['Patient']['birthdate']['month'];
			$d = $this->request->data['Patient']['birthdate']['day'];
			$this->request->data['Patient']['birthdate'] = "$y-$m-$d";
			if ($patient) {
				// Profil już był, edytujemy
				$this->request->data['Patient']['id'] = $patient['Patient']['id'];
				$this->request->data['Patient']['user_id'] = $patient['Patient']['user_id'];
				if ($this->Patient->save($this->request->data)) {
					$this->Session->setFlash('Dane zostały zapisane');
					$this->redirect(array(
						'action' => 'profile'
					));
				} else {
					$this->Session->setFlash('Wystąpił błąd, spróbuj ponownie');
				}
			} else {
				// Świeżutki profil, tworzymy nowy
				$this->Patient->create();
				$this->request->data['Patient']['user_id'] = $this->Auth->user('id');
				if ($this->Patient->save($this->request->data)) {
					$this->Session->setFlash('Dane zostały zapisane');
					$this->redirect(array(
						'action' => 'profile'
					));
				} else {
					$this->Session->setFlash('Wystąpił błąd, spróbuj ponownie');
				}
			}
		}
		if ($patient) {
			$this->set('patient', $patient);
			$this->render('profile_edit');
		} else {
			$this->render('profile_new');
		}
	}
}
