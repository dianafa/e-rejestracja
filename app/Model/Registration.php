<?php
App::uses('AppModel', 'Model');

class Registration extends AppModel {
	public $belongsTo = array(
		'Doctor',
		'Patient',
		'Speciality'
	);
}