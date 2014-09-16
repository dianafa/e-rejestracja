<h2>Edycja ustawień systemu</h2>

<p><?php echo $this->Html->link(__('Powrót do spisu'), array('action' => 'index')); ?></p>

<table>
	<tr>
		<th>Nazwa</th>
		<th>Wartość/Nowa/Zapisz</th>
	</tr>
	<?php foreach ($settings as $s): ?>
		<tr>
			<td><?php echo $s['Setting']['description']; ?></td>
			<td>
				<?php
					echo $this->Form->create('Setting', array('url' => array('action' => 'edit', $s['Setting']['id'])));
					echo $this->Form->input('value', array('value' => $s['Setting']['value']));
					echo $this->Form->end(__('Zapisz'));
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>