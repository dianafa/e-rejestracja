<h2>Lista rejestracji oczekujących na umówienie wizyty</h2>

<table>
	<tr>
		<th>Czas wpisu</th>
		<th>Specjalność</th>
		<th>Preferowany lekarz</th>
		<th>Akcje</th>
	</tr>

	<?php foreach ($registrations as $reg): ?>
		<tr>
			<td><?php echo $reg['Registration']['time']; ?> </td>
			<td><?php echo $this->Html->link($reg['Speciality']['name'], array('controller' => 'specialities', 'action' => 'view', $reg['Speciality']['id'])); ?></td>
			<td><?php echo $this->Html->link($this->User->fullname($reg['Doctor']['User']), array('controller' => 'doctors', 'action' => 'view', $reg['Doctor']['id'])); ?></td>
			<td><?php echo $this->Html->link(__('Anuluj'), array('controller' => 'registrations', 'action' => 'delete', $reg['Registration']['id'])); ?></td>
		</tr>
	<?php endforeach; ?>
</table>