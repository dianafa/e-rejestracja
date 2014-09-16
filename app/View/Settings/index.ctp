<h2>Przegląd ustawień systemu</h2>

<table>
	<tr>
		<th>Nazwa</th>
		<th>Wartość</th>
	</tr>
	<?php foreach ($settings as $s): ?>
		<tr>
			<td><?php echo $s['Setting']['description']; ?></td>
			<td><?php echo $s['Setting']['value']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>

<p><?php echo $this->Html->link(__('Edytuj powyższe ustawienia'), array('action' => 'edit')); ?></p>