<?php
App::uses('AppModel', 'Model');

class Doctor extends AppModel {
	public $belongsTo = array(
		'User'
	);

	public $hasMany = array(
		'Registration',
		'Visit'
	);

	public $hasAndBelongsToMany = array(
		'Speciality'
	);
}