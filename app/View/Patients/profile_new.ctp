<h2>Edycja danych pacjenta</h2>

<table>
	<tr>
		<th colspan="2">Dane podstawowe (nieedytowalne)</th>
	</tr>
	<tr>
		<td>Imię i nazwisko</td>
		<td><?php echo $this->User->fullName(AuthComponent::user()); ?></td>
	</tr>
	<tr>
		<td>PESEL</td>
		<td><?php echo AuthComponent::user('PESEL'); ?></td>
	</tr>
</table>

<?php
	echo $this->Form->create('Patient');
	
	$s = array();
	foreach ($sexes as $sex)
		$s[$sex['Sex']['id']] = __($sex['Sex']['name']);
	
	echo $this->Form->input('sex_id', array('label' => __('Płeć'), 'options' => $s));
	echo $this->Form->input('NIP', array('label' => 'NIP', 'type' => 'textbox', 'required' => false));
	echo $this->Form->input('address', array('label' => __('Adres zamieszkania'), 'type' => 'textbox'));
	echo $this->Form->input('birthdate', array('label' => __('Data urodzenia'), 'type' => 'date'));
	echo $this->Form->input('birthplace', array('label' => __('Miejsce urodzenia'), 'type' => 'textbox'));
	echo $this->Form->input('idcard', array('label' => __('Numer dowodu osobistego'), 'type' => 'textbox'));
	echo $this->Form->input('phone', array('label' => __('Telefon kontaktowy'), 'type' => 'textbox'));
	echo $this->Form->input('email', array('label' => __('E-mail'), 'type' => 'email'));
	echo $this->Form->input('emergencyPhone', array('label' => __('Telefon do osoby z rodziny w razie nagłego wypadku'), 'type' => 'textbox'));
	
	echo $this->Form->end(__('Zapisz'));
?>