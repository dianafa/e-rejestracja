<h3>Połączenia specjalności z lekarzami</h3>
<table class="specialities-connections specialities">
	<tr>
		<td></td>
		<?php $listedIds = array(); $i = 0; ?>
		<?php foreach ($doctors as $d): ?>
			<th><?php echo $this->User->fullName($d['User']); ?></th>
			<?php $listedIds[$i++] = $d['Doctor']['id']; ?>
		<?php endforeach; ?>
	</tr>
	<?php foreach ($specialities as $s): ?>
		<tr>
			<td><?php echo $s['Speciality']['name']; ?></td>
			<?php
				for ($j = 0; $j < $i; ++$j)
					if (isset($connections[$s['Speciality']['id']][$listedIds[$j]]))
						echo '<td>Tak, '.$this->Html->link(__('Rozłącz'), array('controller' => 'specialities', 'action' => 'disconnect', $s['Speciality']['id'], $listedIds[$j])).'</td>';
					else
						echo '<td>Nie, '.$this->Html->link(__('Połącz'), array('controller' => 'specialities', 'action' => 'connect', $s['Speciality']['id'], $listedIds[$j])).'</td>';
			?>
		</tr>
	<?php endforeach; ?>
</table>