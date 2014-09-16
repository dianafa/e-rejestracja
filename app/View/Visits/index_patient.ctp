<h2>Lista wizyt zapisanych w systemie</h2>
<?php if ($visits): ?>
	<table>
		<tr>
			<th>Kiedy</th>
			<th>U kogo (lekarz)</th>
			<th>Przypomnienia</th>
			<th>Akcje</th>
		</tr>
		<?php foreach ($visits as $visit): ?>
			<tr>
				<td><?php echo $visit['Visit']['time']; ?></td>
				<td><?php echo $this->User->fullName($visit['Doctor']['User']); ?></td>
				<td>
					<?php
						foreach ($visit['Reminder'] as $r):
							echo $r['time'];
							if ($r['sent'] == 1)
								echo ' (wysłane)';
							echo '<br>';
						endforeach;
						if (empty($visit['Reminder'])) {
							echo 'Brak. ';
							echo $this->Html->link(__('Ustaw'), array('controller' => 'reminders', 'action' => 'add', $visit['Visit']['id']));
						}
					?>
				</td>
				<td><?php echo $this->Html->link(__('Dodaj przypomnienie'), array('controller' => 'reminders', 'action' => 'add', $visit['Visit']['id'])); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aktualnie nie ma żadnych zapisanych wizyt.</p>
<?php endif; ?>