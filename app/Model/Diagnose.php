<?php
App::uses('AppModel', 'Model');

class Diagnose extends AppModel {
    
	//public $hasAndBelongsToMany = array(
	//	'Visit'
	//);
        
        public $validate = array(
		'code' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Kod ICD-10 choroby jest wymagany'
			)
		),
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Nazwa choroby jest wymagana'
			)
		)
	);
}