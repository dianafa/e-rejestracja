<?php
App::uses('PatientsController', 'Controller');

/**
 * PatientsController Test Case
 *
 */
class PatientsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.patient',
		'app.sex',
		'app.user',
		'app.role',
		'app.receptionist',
		'app.doctor',
		'app.registration',
		'app.speciality',
		'app.doctors_speciality',
		'app.visit',
		'app.reason',
		'app.reminder',
		'app.medicine',
		'app.medicines_visit'
	);

}
