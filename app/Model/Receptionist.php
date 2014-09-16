<?php
App::uses('AppModel', 'Model');

class Receptionist extends AppModel {
	public $belongsTo = array(
		'User'
	);
}