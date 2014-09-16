<h1>Lista leków</h1>
<p><?php echo $this->Html->link("Dodaj nowy lek", array('action' => 'add')); ?></p>
<table>
<tr>
<th>Nazwa leku</th>
<th>Substancja czynna</th>
<th>Akcje</th>
</tr>

<?php foreach ($medicines as $medicine): ?>
<tr>
<td><?php echo $this->Html->link($medicine['Medicine']['name'], array('action' => 'view', $medicine['Medicine']['id'])); ?> </td>
<td><?php echo $medicine['Medicine']['substance']; ?></td>
<td>
<?php echo $this->Form->postLink('Usuń lek', array('action' => 'delete', $medicine['Medicine']['id']), array('confirm' => 'Jesteś pewien, że chcesz usunąć ten lek?')); ?>
<?php echo ' ',$this->Html->link('Edytuj', array('action' => 'edit', $medicine['Medicine']['id'])); ?>
</td>
</tr>
<?php endforeach; ?>
</table>