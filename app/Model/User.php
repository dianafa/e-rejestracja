<?php

App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public $hasOne = array(
        'Patient' => array(
            'conditions' => array('User.role_id' => 0)
        ),
        'Receptionist' => array(
            'conditions' => array('User.role_id' => 1)
        ),
        'Doctor' => array(
            'conditions' => array('User.role_id' => 2)
        )
    );
    public $belongsTo = array(
        'Role'
    );
    public $validate = array(
        'PESEL' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'PESEL jest wymagany'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Hasło jest wymagane'
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Imię jest wymagane'
            )
        ),
        'surname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Nazwisko jest wymagane'
            )
        ),
        'current_password' => array(
            'rule' => 'checkCurrentPassword',
            'message' => 'Wprowadź poprawnie'
        ),
        'npassword1' => array(
            'rule' => 'passwordsMatch',
            'message' => '',
        ),
        'npassword2' => array(
            'rule' => 'passwordsMatch',
            'message' => 'Hasła muszą się zgadzać',
        )
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        if (isset($this->data[$this->alias]['npassword1'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['npassword1']
            );
        }
        return true;
    }

    public function checkCurrentPassword($data) {
    	$this->id = AuthComponent::user('id');
    	$password = $this->field('password');
    	return(AuthComponent::password($data['current_password']) == $password);
    }

    // To jest bardzo brzydkie ale nie mamy czasu myśleć nad ładnym rozwiązaniem

    private $tpass;

    public function passwordsMatch($data) {
    	if (isset($data['npassword1'])) {
    		$this->tpass = $data['npassword1'];
    		return true;
    	}
    	return $data['npassword2'] == $this->tpass;
    }

}
