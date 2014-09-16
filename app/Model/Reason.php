<?php
App::uses('AppModel', 'Model');

class Reason extends AppModel {
	public $hasMany = array(
		'Visit'
	);
}