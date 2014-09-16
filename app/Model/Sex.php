<?php
App::uses('AppModel', 'Model');

class Sex extends AppModel {
	public $hasMany = array(
		'Patient'
	);
}