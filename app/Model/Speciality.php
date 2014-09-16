<?php
App::uses('AppModel', 'Model');

class Speciality extends AppModel {
	public $hasMany = array(
		'Registration'
	);

	public $hasAndBelongsToMany = array(
		'Doctor'
	);
}