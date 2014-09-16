<h2>Profil pacjenta</h2>

<table>
	<tr>
		<td>Pacjent</td>
		<td><?php echo $this->User->fullName($patient['User']); ?></td>
	</tr>
	<tr>
		<td>PESEL</td>
		<td><?php echo $patient['User']['PESEL']; ?></td>
	</tr>
	<tr>
		<td>Płeć</td>
		<td><?php echo __($patient['Sex']['name']); ?></td>
	</tr>
	<tr>
		<td>NIP</td>
		<td><?php echo ($patient['Patient']['NIP'] ? $patient['Patient']['NIP'] : 'n/d'); ?></td>
	</tr>
	<tr>
		<td>Adres</td>
		<td><?php echo $patient['Patient']['address']; ?></td>
	</tr>
	<tr>
		<td>Data urodzenia</td>
		<td><?php echo $patient['Patient']['birthdate']; ?></td>
	</tr>
	<tr>
		<td>Miejsce urodzenia</td>
		<td><?php echo $patient['Patient']['birthplace']; ?></td>
	</tr>
	<tr>
		<td>Nr dowodu osobistego</td>
		<td><?php echo $patient['Patient']['idcard']; ?></td>
	</tr>
	<tr>
		<td>Telefon kontaktowy</td>
		<td><?php echo $patient['Patient']['phone']; ?></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td><?php echo $patient['Patient']['email']; ?></td>
	</tr>
	<tr>
		<td>Telefon w nagłym wypadku</td>
		<td><?php echo $patient['Patient']['emergencyPhone']; ?></td>
	</tr>
</table>

<?php echo $this->Html->link(__('Edytuj powyższe dane'), array('action' => 'editProfile')); ?>