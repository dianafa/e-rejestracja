<?php
App::uses('AppModel', 'Model');

class Medicine extends AppModel {
	public $hasAndBelongsToMany = array(
		'Visit'
	);
        public $validate = array(
		
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Nazwa leku jest wymagana'
			)
		),'substance' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Substancja czynna leku jest wymagana'
			)
		)
	);
}