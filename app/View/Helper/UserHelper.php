<?php
App::uses('AppHelper', 'View/Helper');

class UserHelper extends AppHelper {
	public $uses = array('Role');

	public function fullName($user) {
		return $user['name'].' '.$user['surname'];
	}

	public function roledName($user) {
		return $user['Role']['description'].' '.$this->fullName($user);
	}

	public function roledHTMLName($user) {
		return $user['Role']['description'].' '.'<strong class="username">'.$this->fullName($user).'</strong>';
	}
}