<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class PatientsController extends AppController {

    public $uses = array(
        'User',
        'Role',
        'Doctor',
        'Receptionist',
        'Patient',
        'Visit',
        'Validation',
        'Sex'
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function isAuthorized($user) {
        switch ($this->action) {
            case 'add':
            case 'index':
            case 'view':
                if ($this->Auth->user('Role.name') !== 'patient')
                    return true;
                else
                    return false;
            case 'profile':
            case 'editProfile':
                if ($this->Auth->user('Role.name') === 'patient')
                    return true;
                else
                    return false;
        }
        return parent::isAuthorized($user);
    }

    public function send_email($name, $mail) {
        $hash=sha1($name.rand(0,100)); //tworzymy unikalny hash na podstawie imienia usera
        $this->User->data['User']['tokenhash']=$hash;
        $link = Router::url( array('controller'=>'patients','action'=>'activate_account'), true ).'/'.$hash;
        $message = 'Witaj w e-rejestracji ' . $name . "!\n\n Aby aktywować konto i w pełni korzystać z możliwości naszego serwisu, kliknij w poniższy link: \n\n" . $link;

        $Email = new CakeEmail('gmail');
        $Email->to($mail); 
        $Email->subject('Witaj w e-rejestracji!');
        $Email->emailFormat('html');
        $Email->send($message);
        $this->Session->setFlash(__('Na podany e-mail został wysłany link potwierdzający.'));
    }
    
    public function activate_account($hashcode = null) {
        if($hashcode){
            //$this->Session->setFlash(__('Twoje konto pacjenta jest już aktywne.\nZapraszamy do korzystania z serwisu.'));
            //$this->redirect(array('controller'=>'users','action'=>'login'));
        }
        else {
            $this->Session->setFlash(__('Coś poszło nie tak.'));
            $this->redirect('/');
        }
    }

    public function add() {
        $this->set('sexes', $this->Sex->find('all'));
        //$this->send_email('Diana', 'diana.falkowska@gmail.com');
        
        
        if ($this->request->is('post')) {
            $Role = $this->Role->find('first', array(
                'fields' => array(
                    'id',
                    'description'
                ),
                'conditions' => array(
                    'name' => 'patient'
                ),
                'recursive' => -1
            ));
            if ($this->request->data['Patient']['PESEL2'] != $this->request->data['Patient']['PESEL']) {
                $this->Session->setFlash(__('Błędnie powtórzono PESEL, spróbuj jeszcze raz'));
                $this->request->data['Patient']['PESEL2'] = $this->request->data['Patient']['PESEL'] = '';
                $this->request->data['Patient']['password2'] = $this->request->data['Patient']['password'] = '';
                return;
            }
            if ($this->request->data['Patient']['password2'] != $this->request->data['Patient']['password']) {
                $this->Session->setFlash(__('Błędnie powtórzono hasło, spróbuj jeszcze raz'));
                $this->request->data['Patient']['password2'] = $this->request->data['Patient']['password'] = '';
                return;
            }
            if ($this->User->find('count', array(
                        'conditions' => array(
                            'PESEL' => $this->request->data['Patient']['PESEL'],
                            'role_id' => $Role['Role']['id']
                        )
                    )) > 0) {
                $this->Session->setFlash(__($Role['Role']['description']) . __(' o takim PESELu już istnieje. Upewnij się, że dobrze wpisałeś/aś'));
                $this->request->data['Patient']['password2'] = $this->request->data['Patient']['password'] = '';
                return;
            }
            if ($this->Patient->validates()) {
                $this->Patient->create();
                $y = $this->request->data['Patient']['birthdate']['year'];
                $m = $this->request->data['Patient']['birthdate']['month'];
                $d = $this->request->data['Patient']['birthdate']['day'];
                $this->request->data['Patient']['birthdate'] = date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));
                $this->Patient->save($this->request->data);
            } else {
                // didn't validate logic
                $errors = $this->Patient->validationErrors;
                $this->Session->setFlash(__('Błąd podczas zapisywania pacjenta'));
            }
            // tutaj uzupelnij dane potrzebne dla stworzenia usera
            $this->request->data['User']['PESEL'] = $this->request->data['Patient']['PESEL'];
            $this->request->data['User']['role_id'] = $Role['Role']['id'];
            $this->request->data['User']['password'] = $this->request->data['Patient']['password'];
            $this->request->data['User']['name'] = $this->request->data['Patient']['name'];
            $this->request->data['User']['surname'] = $this->request->data['Patient']['surname'];

            $this->User->create();
            $this->User->save($this->request->data);
            $this->Session->setFlash(__('Pacjent został zapisany'));
            
            $this->send_email($this->request->data['Patient']['name'], $this->request->data['Patient']['email']);
            return $this->redirect('/');
        }
    }

    public function index() {
        $this->set('patients', $this->Patient->find('all'));
    }

    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid patient'));
        }
        $patient = $this->Patient->findById($id);
        if (!$patient) {
            throw new NotFoundException(__('Nie ma pacjenta o takim id'));
        }
        $this->set('patient', $patient);
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
            $this->request->data['Patient']['birthdate'] = date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));
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
