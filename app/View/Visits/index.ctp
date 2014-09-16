<h2>Lista wizyt zarejestrowanych w systemie</h2>
<h3><?php echo $this->Html->link("Dodaj nową wizytę", array('action' => 'add')); ?></h3>
<?php if ($visits): ?>
	<table>
		<tr>
			<th>ID wizyty</th>
			<th>Kiedy</th>
			<th>Pacjent</th>
			<th>Lekarz</th>
			<th>Przypomnienia</th>
			<th>Akcje</th>
		</tr>
		<?php foreach ($visits as $visit): ?>
			<tr>
				<td><?php echo $this->Html->link($visit['Visit']['id'], array('action' => 'view', $visit['Visit']['id'])); ?></td>
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
                                <td>
                                    <?php 
                                            echo $this->Form->postLink('Edytuj wizytę', array('action' => 'edit', $visit['Visit']['id'])); 
                                            echo " ";
                                            echo $this->Form->postLink('Usuń wizytę', array('action' => 'delete', $visit['Visit']['id']), array('confirm' => 'Jesteś pewien, że chcesz usunąć zaplanowaną wizytę?')); 
                                    ?>
                                </td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aktualnie nie ma żadnych zapisanych wizyt.</p>
<?php endif; ?>