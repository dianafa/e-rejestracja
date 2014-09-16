<?php
App::uses('AppModel', 'Model');
App::uses('FormHelper', 'Validation', 'Controller/Component/Auth');
App::uses('CakeEmail', 'Network/Email');

class Patient extends AppModel {

	public $belongsTo = array(
		'Sex',
		'User'
	);

	public $hasMany = array(
		'Registration',
		'Visit'
	);

	public $validate = array(
		'name' => array(
			'alphabetic' => array(
				'rule' => '[A-Za-z]',
				'required' => true,
				'message' => 'Imię może zawierać tylko litery.'
			),
			'between' => array(
				'rule' => array(
					'between',
					5,
					15
				),
				'message' => 'Between 5 to 15 characters'
			)
		),
		'surname' => array(
			'alphaNumeric' => array(
				'rule' => '[A-Za-z]',
				'required' => true,
				'message' => 'Nazwisko może zawierać tylko litery.'
			),
			'between' => array(
				'rule' => array(
					'between',
					2,
					15
				),
				'message' => 'Between 2 to 15 characters'
			)
		),
		'address' => array(
			'required' => true
		),
		'sex_id' => array(
			'required' => true
		),
		'idcard' => array(
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'required' => true,
				'message' => 'Numer i seria mogą składać się tylko z liter i cyfr'
			),
			'between' => array(
				'rule' => array(
					'between',
					9,
					9
				),
				'message' => 'Numer i seria dowodu osobistego to razem 9 znaków.'
			)
		),
		'NIP' => array(
                        'required' => false,
			'correct' => array(
				'rule' => array(
					'NIPcorrectness'
				),
				'message' => 'Wpisz poprawny NIP'
			)
		),
		'email' => array(
			'rule' => array(
				'email',
				true
			),
			'required' => true,
			'message' => 'Wpisz poprawny adres e-mail'
		),
		'password' => array(
			'rule' => array(
				'minLength',
				'8'
			),
			'required' => true,
			'message' => 'Hasło musi mieć conajmniej 8 znaków'
		),
		'password2' => array(
			'rule' => array(
				'minLength',
				'8'
			),
			'required' => true,
			'message' => 'Hasło musi mieć conajmniej 8 znaków'
		)
	);

	public function PESELcorrectness($pesel) {
		$a = substr($pesel, 0, 1);
		$b = substr($pesel, 1, 1);
		$c = substr($pesel, 2, 1);
		$d = substr($pesel, 3, 1);
		$e = substr($pesel, 4, 1);
		$f = substr($pesel, 5, 1);
		$g = substr($pesel, 6, 1);
		$h = substr($pesel, 7, 1);
		$i = substr($pesel, 8, 1);
		$j = substr($pesel, 9, 1);
		$checksum = substr($pesel, 10, 1);

		$result = $a + 3 * $b + 7 * $c + 9 * $d + $e + 3 * $f + 7 * $g + 9 * $h + $i + 3 * $j;

		$check = 10 - substr($result, -1, 1);

		if (substr($result, -1, 1) == 0)
			$check = 0;

		if ($check == $checksum)
			return true;
		else
                        
			return false;
	}

	static public function NIPcorrectness($nip) {
		if (empty($nip['NIP']))
			// NIP nie jest obowiązkowy
			return true;
		if ($nip == 0) // A co to za magia?
			return false;
		$chr_to_replace = array(
			'-',
			' '
		); // nie potrzebujemy tych znakow
		$nip = str_replace($chr_to_replace, '', $nip);
		if (!is_numeric($nip))
			return false;
		$weights = array(
			6,
			5,
			7,
			2,
			3,
			4,
			5,
			6,
			7
		);
		$digits = str_split($nip);
		$digits_length = count($digits);
		for ($i = 1; $i < $digits_length; $i++) {
			if ($digits[0] != $digits[$i])
				break;
			if ($digits[0] == $digits[$i] && $i == $digits_length - 1)
				return false;
		} // end for
		$in_control_number = intval(array_pop($digits));
		$sum = 0;
		$weights_length = count($weights);
		for ($i = 0; $i < $weights_length; $i++) {
			$sum += $weights[$i] * intval($digits[$i]);
		} // end for
		$modulo = $sum % 11;
		$control_number = ($modulo == 10) ? 0 : $modulo;
		return $in_control_number == $control_number;
	}
}
