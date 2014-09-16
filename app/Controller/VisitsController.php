<?php

ini_set('memory_limit', '-1');
App::uses('AppController', 'Controller');

class VisitsController extends AppController {

    public $uses = array(
        'User',
        'Role',
        'Doctor',
        'Receptionist',
        'Patient',
        'Visit',
        'Registration',
        'Speciality',
        'Patient',
        'Diagnose',
        'Medicine',
        'Medicines_visit',
        'MedicinesVisit',
        'Procedure'
    );
    public $components = array(
        'Session',
        'TimeSlot'
    );
    
    //var $helpers = array('Html','Ajax','Javascript', 'Js');

    public function isAuthorized($user) {
        switch ($this->action) {
            case 'add':
                if ($user['Role']['name'] !== 'patient')
                    return true;
                else
                // tylko doktor albo pani Bożena z rejestracji mogą stworzyc wizytę bo wiedzą kiedy lekarz ma wolne
                    return false;
            case 'index':
                // No tylko zwracamy inne rzeczy
                return true;
            case 'view':
                if (($user['Role']['name'] == 'doctor') || ($user['Role']['name'] == 'receptionist') || ($user['Role']['name'] == 'admin')) {
                    return true;
                } else {
                    $visit_id = $this->params['pass'][0];
                    $visit = $this->Visit->findById($visit_id);
                    if ($visit)
                        if ($visit['Patient']['id'] == $user['Patient']['id'])
                            return true;
                }
                return false;
            case 'edit':
                if ($user['Role']['name'] !== 'patient')
                    return true;
                return parent::isAuthorized($user);
        }
        return parent::isAuthorized($user);
    }

    public function index($method = null) {
        if ($method != 'happenned' && $method != 'planned')
            $method = 'all';
        $conditions = array();
        if ($this->Auth->user('Role.name') == 'patient') {
            $conditions['Patient.user_id'] = $this->Auth->user('id');
        }
        if ($this->Auth->user('Role.name') == 'doctor') {
            $conditions['Doctor.user_id'] = $this->Auth->user('id');
        }
        $this->set('visits', $this->Visit->find($method, array(
                    'recursive' => 2,
                    'conditions' => $conditions
        )));
        if ($this->Auth->user('Role.name') == 'patient') {
            $this->render('index_patient');
        }
        if ($this->Auth->user('Role.name') == 'doctor') {
            $this->render('index_doctor');
        }
    }

    public function add($registration_id = null) {
        $registration = null;

        $this->set('specialities', $this->Speciality->find('all'));
        $this->set('doctors', $this->Doctor->find('all'));

        if (is_numeric($registration_id)) {

            $registration = $this->Registration->find('first', array(
                'recursive' => 2,
                'conditions' => array(
                    'Registration.id' => $registration_id
                )
            ));
            $this->set('registration', $registration);
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
                            'DS.speciality_id' => $registration['Speciality']['id']
                        )
            )));
        } else {
            $this->view = 'add_empty';
        }
        if ($this->request->is('post')) {
            $this->Visit->create();
            if ($registration) {
                $this->request->data['Visit']['patient_id'] = $registration['Registration']['patient_id'];
            }
            if ($this->Auth->user('Role.name') === 'doctor') {
                $doctor = $this->Doctor->findByUserId($this->Auth->user('id'));
                if ($doctor) {
                    $this->request->data['Visit']['doctor_id'] = $doctor['Doctor']['id'];
                } else {
                    $this->Session->setFlash(__('Problem - jednocześnie jesteś lekarzem i nim nie jesteś :P'));
                    return $this->redirect(array(
                                'action' => 'index'
                    ));
                }
            }
            $y = $this->request->data['Visit']['time']['year'];
            $m = $this->request->data['Visit']['time']['month'];
            $d = $this->request->data['Visit']['time']['day'];
            $h = $this->request->data['Visit']['time']['hour'];
            $mi = $this->request->data['Visit']['time']['min'];
            if ($this->TimeSlot->checkDoctorFree($this->request->data['Visit']['doctor_id'], $this->request->data['Visit']['time'])) {
                $this->request->data['Visit']['time'] = date('Y-m-d H:i', mktime($h, $mi, 0, $m, $d, $y));
                if ($this->Visit->save($this->request->data)) {
                    if ($registration) {
                        $this->Registration->delete($registration_id);
                    }
                    $this->Session->setFlash(__('Dodano wizytę'));
                    return $this->redirect(array(
                                'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__('Nie dodano wizyty'));
                }
            } else {
                $this->Session->setFlash(__('Niestety ten lekarz ma tę godzinę zajętą lub wykracza ona poza ramy pracy przychodni'));
            }
        } else {
            if ($this->Auth->user('Role.name') === 'doctor') {
                $doctor = $this->Doctor->findByUserId($this->Auth->user('id'));
                if ($doctor) {
                    $this->set('specialities', $this->Speciality->find('all', array(
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
                                    'DS.doctor_id' => $doctor['Doctor']['id']
                                )
                    )));
                    $this->set('doctors', array(
                        $doctor
                    ));
                    $this->render('add_empty_doctor');
                } else {
                    $this->Session->setFlash(__('Problem - jednocześnie jesteś lekarzem i nim nie jesteś :P'));
                    return $this->redirect(array(
                                'action' => 'index'
                    ));
                }
            } else {
                $specialities = $this->Speciality->find('all');
                $doctors = $this->Doctor->find('all');
                $this->set('specialities', $specialities);
                $this->set('doctors', $doctors);
            }
        }
    }

    public function addRepeat($visit_id) {
        if ($visit_id) {
            $visit = $this->Visit->find('first', array(
                'recursive' => 2,
                'conditions' => array(
                    'Visit.id' => $visit_id
                )
            ));
            if ($visit) {
                if ($this->request->is('post')) {
                    $this->Visit->create();
                    $this->request->data['Visit']['patient_id'] = $visit['Patient']['id'];
                    $this->request->data['Visit']['doctor_id'] = $visit['Doctor']['id'];
                    $y = $this->request->data['Visit']['time']['year'];
                    $m = $this->request->data['Visit']['time']['month'];
                    $d = $this->request->data['Visit']['time']['day'];
                    $h = $this->request->data['Visit']['time']['hour'];
                    $mi = $this->request->data['Visit']['time']['min'];
                    $this->request->data['Visit']['time'] = date('Y-m-d H:i', mktime($h, $mi, 0, $m, $d, $y));
                    if ($this->Visit->save($this->request->data)) {
                        $this->Session->setFlash(__('Udało się zapisać wizytę kontrolną'));
                        return $this->redirect(array(
                                    'action' => 'index',
                                    'planned'
                        ));
                    } else {
                        $this->Session->setFlash(__('Nie udało się zapisać wizyty. Nie wiemy czemu.'));
                    }
                }
            } else {
                $this->Session->setFlash(__('Niestety podana wizyta pierwotna nie istnieje'));
                return $this->redirect(array(
                            'action' => 'index'
                ));
            }
        } else {
            $this->Session->setFlash(__('Najpierwiej wybierz wizytę pierwotną'));
            return $this->redirect(array(
                        'action' => 'index'
            ));
        }
    }

    public function view($id = null) {
        if (!$id or ! is_numeric($id)) {
            throw new NotFoundException(__('Invalid visit'));
        }
        $visit = $this->Visit->find('first', array(
            'recursive' => 2,
            'conditions' => array(
                'Visit.id' => $id
            )
        ));
        if (!$visit) {
            throw new NotFoundException(__('Nie ma wizyty o takim id'));
        }
        $this->set('visit', $visit);
        $medicines_v = $this->Visit->MedicinesVisit->find('all', array(
            'recursive' => 2,
            'conditions' => array(
                'MedicinesVisit.visit_id' => $id
            )
        ));
        $medicines = array();
        foreach ($medicines_v as $m):
            $med = $this->Medicine->find('first', array(
                //'recursive' => 2,
                'conditions' => array(
                    'Medicine.id' => $m['medicine_id']
            )));
            array_push($medicines, $med);
        endforeach;
        $this->set('medicines_v', $medicines_v);
        $this->set('medicines', $medicines);
    }

    public function edit($id) {
        if (!$id) {
            throw new NotFoundException(__('Wizyta którą chcesz edytować nie istnieje'));
        }
        $visit = $this->Visit->findById($id);
        if (!$visit) {
            throw new NotFoundException(__('Wizyta którą chcesz edytować nie istnieje!'));
        }

        $this->set('medicines', $this->Medicine->find('all', array('limit' => 5000)));
        $this->set('diagnoses', $this->Diagnose->find('all', array('limit' => 5000)));
        $this->set('procedures', $this->Procedure->find('all', array('limit' => 5000)));
        $this->set('visit', $visit);
        $patient = $this->User->findById($visit['Patient']['user_id']);
        $this->set('patient', $patient);
        $doctor_id = $visit['Visit']['doctor_id'];
        $doctor = $this->Doctor->findById($doctor_id);
        $this->set('doctor', $doctor);

        if ($this->request->is(array(
                    'post',
                    'put'
                ))) {
            $this->Visit->id = $id;
            $this->connect($this->request->data['Visit']['medicine_id'], $id);
            $y = $this->request->data['Visit']['time']['year'];
            $m = $this->request->data['Visit']['time']['month'];
            $d = $this->request->data['Visit']['time']['day'];
            $h = $this->request->data['Visit']['time']['hour'];
            $mi = $this->request->data['Visit']['time']['min'];
            $this->request->data['Visit']['time'] = date('Y-m-d H:i', mktime($h, $mi, 0, $m, $d, $y));
            if ($this->Visit->save($this->request->data)) {
                $this->Session->setFlash(__('Zmiany zostały zapisane'));
                return $this->redirect(array(
                            'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__('Edycja zakończona niepowodzeniem'));
            }
        }
        if (!$this->request->data) {
            $this->request->data = $visit;
        }
    }
    /*
    public function autoComplete() {
        //Partial strings will come from the autocomplete field as
        //$this->data['Post']['subject']
        $this->set('found_meds', $this->Medicine->find('all', array(
                    'conditions' => array(
                        'Medicine.name LIKE' => $this->data['Visit']['medicine_id'] . '%'
                    ),
                    'fields' => array('name')
        )));
        $this->layout = 'ajax';
    }*/

    public function connect($medicines, $visit_id) {
        if ($medicines) {
            foreach ($medicines as $medicine_id):
                $this->Visit->MedicinesVisit->save(array(
                    'MedicinesVisit' => array(
                        'medicine_id' => $medicine_id,
                        'visit_id' => $visit_id
                    )
                ));
            endforeach;
        }
    }

}
