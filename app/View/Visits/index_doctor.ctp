<h2>Lista wizyt zapisanych w systemie</h2>
<?php if ($visits): ?>
	<table>
		<tr>
			<th>Kiedy</th>
			<th>Kto (pacjent)</th>
			<th>Przypomnienia</th>
			<th colspan="2">Akcje</th>
		</tr>
		<?php foreach ($visits as $visit): ?>
			<tr>
				<td><?php echo $visit['Visit']['time']; ?></td>
				<td>
					<?php
						if ($visit['Patient']['id'] != NULL) {
							echo $this->User->fullName($visit['Patient']['User']);
						} else {
							echo "Nieznany";
						}
					?>
				</td>
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
				<td><?php echo $this->Html->link(__('Otwórz'), array('controller' => 'visits', 'action' => 'edit', $visit['Visit']['id'])); ?></td>
				<td><?php echo $this->Html->link(__('Umów na kontrolę'), array('controller' => 'visits', 'action' => 'addRepeat')); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aktualnie nie ma żadnych zapisanych wizyt.</p>
<?php endif; ?>