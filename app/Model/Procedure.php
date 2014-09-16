<?php
App::uses('AppModel', 'Model');

class Procedure extends AppModel {
    
	public $hasMany = array(
		'Visit'
	);
}