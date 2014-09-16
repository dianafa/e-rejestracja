<?php
App::uses('AppModel', 'Model');

class Visit extends AppModel {
	public $hasMany = array(
		'Reminder'
	);

	public $belongsTo = array(
		'Patient',
		'Doctor'
	);

	public $hasAndBelongsToMany = array(
		'Medicine'
	);

	public $findMethods = array('planned' => true, 'happenned' => true);

	protected function _findPlanned($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['Visit.time >='] = date('Y-m-d H:i');
			return $query;
		}
		return $results;
	}

	protected function _findHappenned($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['conditions']['Visit.time <'] = date('Y-m-d H:i');
			return $query;
		}
		return $results;
	}
}