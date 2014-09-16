<h1>Lista chorób i problemów zdrowotnych</h1>
<p><?php echo $this->Html->link("Dodaj nowe rozpoznanie", array('action' => 'add')); ?></p>
<table>
<tr>
<th>Kod ICD-10:</th>
<th>Nazwa choroby</th>
<th>Akcje</th>
</tr>

<?php foreach ($diagnoses as $diagnose): ?>
<tr>
<td><?php echo $diagnose['Diagnose']['code']; ?></td>
<td><?php echo $this->Html->link($diagnose['Diagnose']['name'], array('action' => 'view', $diagnose['Diagnose']['id'])); ?> </td>
<td>
<?php echo $this->Form->postLink('Usuń chorobę', array('action' => 'delete', $diagnose['Diagnose']['id']), array('confirm' => 'Jesteś pewien, że chcesz usunąć chorobę?')); ?>
<?php echo ' ',$this->Html->link('Edytuj', array('action' => 'edit', $diagnose['Diagnose']['id'])); ?>
</td>
</tr>
<?php endforeach; ?>
</table>