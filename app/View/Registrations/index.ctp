<h2>Lista oczekujących rejestracji</h2>

<table>
	<tr>
		<th>Pacjent</th>
		<th>Telefon</th>
		<th>Czas rejestracji</th>
		<th>Lekarz</th>
		<th>Specjalność</th>
		<th colspan="2">Akcje</th>
	</tr>

	<?php foreach ($registrations as $reg): ?>
		<tr>
			<td><?php echo $this->Html->link($this->User->fullName($reg['Patient']['User']), array('controller' => 'patients', 'action' => 'view', $reg['Patient']['id'])); ?></td>
			<td><?php echo $reg['Patient']['phone']; ?></td>
			<td><?php echo $reg['Registration']['time']; ?></td>
			<td><?php echo $this->Html->link($this->User->fullName($reg['Doctor']['User']), array('controller' => 'doctors', 'action' => 'view', $reg['Doctor']['id'])); ?></td>
			<td><?php echo $reg['Speciality']['name']; ?> </td>
			<td><?php echo $this->Html->link(__('Umów wizytę'), array('controller' => 'visits', 'action' => 'add', $reg['Registration']['id'])); ?></td>
			<td><?php echo $this->Html->link(__('Anuluj rejestrację'), array('action' => 'delete', $reg['Registration']['id'])); ?></td>
		</tr>
	<?php endforeach; ?>
</table>