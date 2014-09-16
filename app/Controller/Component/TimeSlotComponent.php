<?php
App::uses('Component', 'Controller');

class TimeSlotComponent extends Component {
	// 'Y-m-d H:i'
	// date('Y-m-d H:i', mktime($h, $mi, 0, $m, $d, $y));
	public function checkDoctorFree($doctor_id, $datetime) {
		$this->Setting = ClassRegistry::init('Setting');
		$this->Visit = ClassRegistry::init('Visit');
		$size = $this->Setting->find('first', array(
			'conditions' => array(
				'Setting.name' => 'timeslotSize'
			)
		));
		$size = $size['Setting']['value'];
		$open = $this->Setting->find('first', array(
			'conditions' => array(
				'Setting.name' => 'openTime'
			)
		));
		$open = $open['Setting']['value'];
		$t = explode(':', $open);
		$open = array(
			'hour' => $t[0],
			'min' => $t[1]
		);
		$close = $this->Setting->find('first', array(
			'conditions' => array(
				'Setting.name' => 'closeTime'
			)
		));
		$close = $close['Setting']['value'];
		$t = explode(':', $close);
		$close = array(
			'hour' => $t[0],
			'min' => $t[1]
		);
		$h = $datetime['hour'];
		$mi = $datetime['min'];
		$m = $datetime['month'];
		$d = $datetime['day'];
		$y = $datetime['year'];
		// Check if datetime isn't before opening
		if ($h < $open['hour'] || ($h == $open['hour'] && $mi < $open['min']))
			return false;
			// Or after closing
		if ($h > $close['hour'] || (($h+($mi+$size >= 60 ? 1 : 0) == $close['hour'] && (($mi+$size)%60) > $close['min'])))
			return false;
			// Check if slot isn't booked already
		$visit = $this->Visit->find('count', array(
			'conditions' => array(
				'Doctor.id' => $doctor_id,
				'Visit.time >' => date('Y-m-d H:i', mktime($h-($mi-$size < 0 ? 1 : 0), ($mi-$size+60)%60, 0, $m, $d, $y)),
				'Visit.time <' => date('Y-m-d H:i', mktime($h+($mi+$size >= 60 ? 1 : 0), ($mi+$size)%60, 0, $m, $d, $y))
			)
		));
		if ($visit)
			return false;
		// Aaaa, no booking, in duty hours...
		return true;
	}

	private function isYearLeap($year) {
		return ($year % 4 == 0) && (($year % 100 != 0) || $year % 400 == 0);
	}

	public function correctDate($datetime) {
		if (isset($datetime['sec'])) {
			$datetime['min'] += (int)($datetime['sec'] / 60);
			$datetime['sec'] = $datetime['sec'] % 60;
		}
		if (isset($datetime['min'])) {
			$datetime['hour'] += (int)($datetime['min'] / 60);
			$datetime['min'] = $datetime['min'] % 60;
		}
		if (isset($datetime['hour'])) {
			$datetime['day'] += (int)($datetime['hour'] / 24);
			$datetime['hour'] = $datetime['hour'] % 24;
		}
		$daysPerMonth = array(
			1 => 31,
			2 => $this->isYearLeap($datetime['year']) ? 29 : 28,
			3 => 31,
			4 => 30,
			5 => 31,
			6 => 30,
			7 => 31,
			8 => 31,
			9 => 30,
			10 => 31,
			11 => 30,
			12 => 31
		);
		$prevMon = (int)$datetime['month'];
		$datetime['month'] += (int)(($datetime['day']-1) / ($daysPerMonth[$prevMon]));
		$datetime['day'] = ($datetime['day']-1) % $daysPerMonth[$prevMon] + 1;

		$datetime['year'] += (int)(($datetime['month']-1) / 12);
		$datetime['month'] = ($datetime['month']-1) % 12 + 1;

		return $datetime;
	}

	public function timeLess($time1, $time2) {
		if ($time1['hour'] < $time2['hour'])
			return true;
		if ($time1['hour'] > $time2['hour'])
			return false;
		return $time1['min'] < $time2['min'];
	}
}