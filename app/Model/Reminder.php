<?php
App::uses('AppModel', 'Model');

class Reminder extends AppModel {
	public $belongsTo = array(
		'Visit'
	);
}